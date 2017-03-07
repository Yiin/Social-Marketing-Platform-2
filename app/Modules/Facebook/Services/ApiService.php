<?php

namespace App\Modules\Facebook\Services;

use App\Models\Template;
use App\Modules\Facebook\Jobs\PostMessage;
use App\Modules\Facebook\Models\FacebookQueue;
use App\Vendor\Facebook\Facebook;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Redirect;
use URL;

class ApiService
{
    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * FacebookService constructor.
     */
    public function __construct()
    {

        $this->facebook = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET')
        ]);
    }

    /**
     * @return bool|Redirect
     */
    public function authenticate()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        $loginUrl = $helper->getLoginUrl(URL::to(route('facebook.token')), [
            'publish_actions,publish_pages,manage_pages'
        ]);

        return $loginUrl;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getToken()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookSDKException $e) {
            throw $e;
        }
        $this->facebook->setDefaultAccessToken($accessToken);

        return $accessToken;
    }

    /**
     * @param array $postData
     * @return FacebookQueue
     */
    public function post(array $postData)
    {
        $client_id = $postData['client_id'];
        $template_id = $postData['template_id'];
        $delay = $postData['delay'];

        $queue = FacebookQueue::create([
            'client_id' => $client_id,
            'template_id' => $template_id,
            'posts' => 0,
            'backlinks' => 0,
            'jobs' => 0
        ]);
        $jobs = 0;

        $template = Template::find($template_id);

        foreach ($postData['queue'] as $group) {
            $data = [
                'link' => $template->url,
                'name' => Parser::parse($template->name)->generate(),
                'picture' => $template->image_url,
                'description' => Parser::parse($template->description)->generate(),
                'message' => Parser::parse($template->message)->generate(),
                'caption' => Parser::parse($template->caption)->generate(),
            ];

            dispatch((new PostMessage(
                $queue, $data, $group
            ))->delay(Carbon::now()->addSeconds($delay)));

            $jobs++;
        }

        $queue->update(['jobs' => $jobs]);

        return $queue;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        try {
            $response = $this->facebook->get('/me?fields=id,name');
        } catch (FacebookSDKException $e) {
            \Log::error('Facebook error: ' . $e->getMessage());
            return null;
        }
        return json_decode($response->getBody());
    }
}