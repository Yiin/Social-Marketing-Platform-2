<?php

namespace App\Modules\GooglePlus\Services;

use App\Models\Template;
use App\Modules\GooglePlus\Jobs\PostMessage;
use App\Modules\GooglePlus\Models\GoogleAccount;
use App\Modules\GooglePlus\Models\GoogleQueue;
use App\Services\CurlService;
use Cache;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use Illuminate\Http\Request;
use nxs_class_SNAP_GP;
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
            return dd(['login', $error]);
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
        $groups = $this->getGroupsData();

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

            $messageSpintax = Parser::parse($template->message);

            if ($template->image_url) {
                $urlSpintax = Parser::parse($template->image_url);
            } else {
                $urlSpintax = Parser::parse($template->url);
            }

            foreach ($group['categories'] as $categoryId) {
                $message = $messageSpintax->generate();
                $url = $template->image_url ? ['img' => $urlSpintax->generate()] : $urlSpintax->generate();

                dispatch((new PostMessage(
                    $queue, $message, $url, $group, $categoryId
                ))->delay($time->addSeconds($delay)));

                $jobs++;
            }
        }

        $queue->update(['jobs' => $jobs]);

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

    private function getGroupsData()
    {
        $email = 'stanislovas.janonis@gmail.com';
        $pass = 'Gigabytes5284691367';
        $commID = '100609058582053363304';
        $commCategoryID = '64a9f3c6-57aa-4da9-ab02-a25aa523687f';
        $lnk = 'http://www.nextscripts.com/snap-api/';
        $msg = 'Post this to Google Plus!';

        $nt = new nxsAPI_GP();
        $nt->debug = true;

        $loginError = $nt->connect($email, $pass);

        if (!$loginError) {
            $result = $nt->postGP($msg, $lnk, '', $commID, $commCategoryID);
        } else {
            echo $loginError;
        }

        if (!empty($result) && is_array($result) && !empty($result['post_url']))
            echo '<a target="_blank" href="' . $result['post_url'] . '">New Post</a>';
        else
            echo "<pre>" . print_r($result, true) . "</pre>";

        exit;

        $groups = [];

        if ($err) {
            dd($err);
        }

//        dd($this->api->postGP("test lol", "", "", "100609058582053363304", "64a9f3c6-57aa-4da9-ab02-a25aa523687f"));

//        dd($this->api->check('GP', 'stanislovas.janonis'));

        $body = $this->api->get('communities/member');//['body'];
//        dd($body);
        echo $body;
        exit;

        $html = new simple_html_dom();

        $html->load($body);

        foreach ($html->find('.gZXxHe.UB0dDd.CuPm0') as $node) {
            $groups [] = [
                'url' => $node->href
            ];
        }
        dd($groups);

        return $groups;

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