<?php

namespace App\Services;


use App\Interfaces\SocialMediaServiceInterface;
use App\Models\Account;
use App\Models\Queue;
use App\Models\SocialMediaService;
use App\Models\Template;
use App\Vendor\Facebook\Facebook;
use ChillDev\Spintax\Parser;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use Redirect;
use URL;

class FacebookService implements SocialMediaServiceInterface
{
    /**
     * @var Facebook
     */
    private $facebook;
    /**
     * @var CurlService
     */
    private $curl;

    /**
     * FacebookService constructor.
     * @param CurlService $curl
     */
    public function __construct(CurlService $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @param Account $account
     * @return boolean
     */
    public function login(Account $account)
    {
        $this->facebook = new Facebook($config = [
            'app_id' => $account->app_id,
            'app_secret' => $account->app_secret
        ]);

        return !!$account->access_token;
    }

    /**
     * @param Account $account
     * @param SocialMediaService $socialMediaService
     * @return bool|Redirect
     */
    public function check(Account $account, SocialMediaService $socialMediaService)
    {
        $this->facebook = new Facebook($config = [
            'app_id' => $account->app_id,
            'app_secret' => $account->app_secret
        ]);

        session()->put('fb-config', $config);
        session()->put('account', $account->toArray());

        $helper = $this->facebook->getRedirectLoginHelper();

        $loginUrl = $helper->getLoginUrl(URL::to(route('account-login-callback', ['socialMediaService' => $socialMediaService->id])), [
            'publish_actions'
        ]);

        return redirect($loginUrl);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws FacebookSDKException
     */
    public function getToken(Request $request)
    {
        $this->facebook = new Facebook(session('fb-config'));

        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookSDKException $e) {
            throw $e;
        }
        return $accessToken;
    }

    /**
     * @return array
     */
    public function groups()
    {
        return []; // TODO: think of a method to retrieve groups automatically
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

        $queue = Queue::create([
            'social_media_service_id' => $socialMediaService->id,
            'client_id' => $client_id,
            'template_id' => $template_id,
            'stats' => [
                'posts' => 0,
                'backlinks' => 0,
                'jobs' => 0
            ]
        ]);

        $template = Template::find($template_id);

        foreach ($postData['queue'] as $group) {
            $url = $template->url;

            if ($template->image_url) {
                $url = [
                    'img' => $template->image_url
                ];
            }

            $spintax = Parser::parse($template->message);

            $message = $spintax->generate();

//            dispatch((new PostToFacebook(
//                $queue, $data, $group, $categoryId
//            ))->delay(Carbon::now()->addSeconds($delay)));
//
//            $jobs++;
        }

        try {
            $r = $this->facebook->post("/1669443953071578/feed", $postData);
        } catch (FacebookSDKException $e) {

            exit;
        }

        return $queue;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        try {
            $response = $this->facebook->get('/me?fields=name');
        } catch (FacebookSDKException $e) {
            \Log::error('Facebook error: ' . $e->getMessage());
            return null;
        }
        return json_decode($response->getBody());
    }
}