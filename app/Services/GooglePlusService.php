<?php

namespace App\Services;

use App\Interfaces\SocialMediaServiceInterface;
use App\Jobs\PostToGooglePlus;
use App\Models\Account;
use App\Models\Post;
use App\Models\Queue;
use App\Models\SocialMediaService;
use App\Models\Template;
use Cache;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use nxsAPI_GP;
use simple_html_dom;

/**
 * Class GooglePlusService
 * @package App\Services
 */
class GooglePlusService implements SocialMediaServiceInterface
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
     * @param $username
     * @param $password
     * @return boolean
     */
    public function login($username, $password)
    {
        $error = $this->api->connect($username, $password);

        if ($error) {
            return false;
        }
        return true;
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
     * @param SocialMediaService $socialMediaService
     * @return Queue
     */
    public function post(array $postData, SocialMediaService $socialMediaService)
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

        $queue = Queue::create([
            'social_media_service_id' => $socialMediaService->id,
            'client_id' => $client_id,
            'template_id' => $template_id,
            'stats' => [
                'posts' => 0,
                'backlinks' => 0
            ]
        ]);

        $template = Template::find($template_id);

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

                dispatch((new PostToGooglePlus(
                    $queue, $message, $url, $group, $categoryId
                ))->delay(Carbon::now()->addSeconds($delay)));
            }
        }

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

            // Cache for 1 hour
            Cache::put('GooglePlus.' . $groups[$index]['id'], $groups[$index], 60);
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
}