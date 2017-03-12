<?php

namespace App\Modules\GooglePlus\Services;

use App\Interfaces\SocialMediaServiceInterface;
use App\Jobs\PostToGooglePlus;
use App\Models\Account;
use App\Models\Queue;
use App\Models\SocialMediaService;
use App\Models\Template;
use App\Modules\GooglePlus\Jobs\PostMessage;
use App\Modules\GooglePlus\Models\GoogleAccount;
use App\Modules\GooglePlus\Models\GoogleQueue;
use App\Services\CurlService;
use Cache;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use Illuminate\Http\Request;
use nxsAPI_GP;
use simple_html_dom;

/**
 * Class GooglePlusService
 * @package App\Services
 */
class ApiService
{
    /**
     * @var nxsAPI_GP
     */
    private $api;

    /**
     * @var \App\Services\CurlService
     */
    private $curl;

    /**
     * GooglePlusService constructor.
     *
     * @param nxsAPI_GP $api
     * @param CurlService $curl
     */
    public function __construct(nxsAPI_GP $api, CurlService $curl)
    {
        $this->api = $api;
        $this->curl = $curl;
    }


    /**
     * @param GoogleAccount $account
     * @return boolean
     */
    public function login(GoogleAccount $account)
    {
        $error = $this->api->connect($account->username, $account->password);

        if ($error) {
            return false;
        }
        return true;
    }

    /**
     * @param GoogleAccount $account
     * @return bool
     */
    public function check(GoogleAccount $account)
    {
        return $this->login($account);
    }

    /**
     * @return array
     */
    public function groups()
    {
        $groups = array_map(function ($group) {
            return [
                'id' => $group[0],
                'name' => $group[1]
            ];
        }, $this->api->grabGroups('/u/0', ''));

        $this->getGroupsData($groups);

        return $groups;
    }

    /**
     * @param array $postData
     * @return GoogleQueue
     */
    public function post(array $postData)
    {
        $client_id = $postData['client_id'];
        $template_id = $postData['template_id'];
        $delay = $postData['delay'];

        $groups = [];

        foreach ($postData['queue'] as $group) {
            $groups[] = [
                'account_id' => $group['account_id'],
                'id' => $group['groupId']
            ];
        }
        $this->getGroupsData($groups);

        $queue = GoogleQueue::create([
            'client_id' => $client_id,
            'template_id' => $template_id,
            'post_count' => 0,
            'backlinks' => 0,
            'jobs' => 0
        ]);
        $jobs = 0;

        $template = Template::find($template_id);

        $time = Carbon::now();

        foreach ($groups as $group) {
            if (empty($group['categories'])) {
                continue;
            }

            $url = $template->url;

            if ($template->image_url) {
                $url = [
                    'img' => $template->image_url
                ];
            }

            $spintax = Parser::parse($template->message);

            foreach ($group['categories'] as $categoryId) {
                $message = $spintax->generate();

                dispatch((new PostMessage(
                    $queue, $message, $url, $group, $categoryId
                ))->delay($time->addSeconds($delay)));

                $jobs++;
            }
        }

        $queue->update(['stats->jobs' => $jobs]);

        return $queue;
    }

    public function postGP($msg, $lnk = '', $pageID = '', $commOrColID = '', $commPageCatID = '')
    {
        return $this->api->postGP($msg, $lnk, $pageID, $commOrColID, $commPageCatID);
    }

    /*
    |--------------------------------------------------------------------------
    | Private helper functions
    |--------------------------------------------------------------------------
    */

    private function getGroupsData(&$groups)
    {
        $urls = [];
        foreach ($groups as $index => &$group) {
            // if we have cached group data, do not update
            if (Cache::has('GooglePlus.' . $group['id'])) {
                $group = array_merge($group, Cache::get('GooglePlus.' . $group['id']));
                continue;
            }
            $urls [$index] = 'https://plus.google.com/communities/' . $group['id'];
        }

        if (empty($urls)) {
            return;
        }

        $results = $this->curl->get_multi($urls);

        $html = new simple_html_dom();

        foreach ($results as $index => $result) {
            if (!$result) {
                $groups[$index]['categories'] = 'Loading failed.';
                $groups[$index]['members'] = 'Loading failed.';
                continue;
            }
            $html->load($result);

            // Groups
            $categories = $this->scrapeCategories($html);

            // Member count
            $member_count = $this->scrapeMembers($html);

            $data = [
                'categories' => $categories,
                'members' => $member_count
            ];

            $groups[$index] = array_merge($groups[$index], $data);

            if (!isset($groups[$index]['name'])) {
                // Name
                $name = $this->scrapeName($html);
                $groups[$index]['name'] = $name;
            }

            // Cache for 10 minutes
            Cache::put('GooglePlus.' . $groups[$index]['id'], $groups[$index], 10);
        }
    }

    private function scrapeCategories(simple_html_dom $html)
    {
        $categories = [];
        foreach ($html->find('.clmEye') as $element) {
            $category = $element->getAttribute('data-categoryid');
            if ($category) {
                $categories[] = $category;
            }
        }
        return $categories;
    }

    private function scrapeMembers(simple_html_dom $html)
    {
        $member_count_array = explode(' ', $html->find('.rZHH0e')[0]->text());
        $member_count = str_replace(",", "", $member_count_array[0]);

        return (int)$member_count;
    }

    private function scrapeName(simple_html_dom $html)
    {
        return $html->find('.Dm8wYc')[0]->text();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getToken(Request $request)
    {
        return null; // there is no access tokens for google plus posting
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return null; // users are created manually
    }
}