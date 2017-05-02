<?php

namespace App\Modules\Linkedin\Services;

use App\Models\Template;
use App\Modules\Linkedin\Jobs\PostMessage;
use App\Modules\Linkedin\Models\LinkedinAccount;
use App\Modules\Linkedin\Models\LinkedinQueue;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class LinkedinService
 * @package App\Services
 */
class ApiService
{
    const CHECK_AUTH = 1;
    const REQUEST_GROUPS = 2;
    const START_POSTING = 3;

    /**
     * @var \App\Services\CurlService
     */
    private $http;

    /**
     * LinkedinService constructor.
     *
     * @param Client $http
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }


    /**
     * @param LinkedinAccount $account
     *
     * @return object
     */
    public function check(LinkedinAccount $account)
    {
        try {
            $response = $this->http->post('localhost:3000/authenticate', [
                'form_params' => [
                    'email' => $account->email,
                    'password' => $account->password
                ]
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        switch ($response->getStatusCode()) {
            case \Symfony\Component\HttpFoundation\Response::HTTP_OK:
                return (object)['status' => 'ok'];

            case \Symfony\Component\HttpFoundation\Response::HTTP_LOCKED:
                return (object)['status' => 'locked'];

            case \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED:
                return (object)['status' => 'unauthorized'];
        }
        return (object)['status' => 'unknown'];
    }


    /**
     * @param LinkedinAccount $account
     * @param string $code
     *
     * @return object
     */
    public function unlock(LinkedinAccount $account, $code)
    {
        try {
            $response = $this->http->post('localhost:3000/unlock', [
                'form_params' => [
                    'email' => $account->email,
                    'password' => $account->password,
                    'code' => $code
                ]
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        switch ($response->getStatusCode()) {
            case \Symfony\Component\HttpFoundation\Response::HTTP_OK:
                return (object)['status' => 'ok'];

            case \Symfony\Component\HttpFoundation\Response::HTTP_EXPECTATION_FAILED:
                return (object)['status' => 'locked'];
        }
        return (object)['status' => 'unknown'];
    }

    /**
     * Get list of user groups
     *
     * @return object
     */
    public function groups(LinkedinAccount $account)
    {
        try {
            $response = $this->http->post('localhost:3000/groups', [
                'form_params' => [
                    'email' => $account->email,
                    'password' => $account->password
                ]
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        switch ($response->getStatusCode()) {
            case \Symfony\Component\HttpFoundation\Response::HTTP_OK:
                return (object)['status' => 'ok', 'groups' => json_decode((string)$response->getBody())];

            case \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED:
                return (object)['status' => 'unauthorized'];
        }
        return (object)['status' => 'unknown'];
    }

    /**
     * Post a message to group
     *
     * @param $account
     * @param $post
     * @return mixed
     */
    public function actuallyPost($account, $groupId, $caption, $message)
    {
        try {
            $response = $this->http->post('localhost:3000/post', [
                'form_params' => [
                    'email' => $account->email,
                    'password' => $account->password,
                    'groupid' => $groupId,
                    'caption' => $caption,
                    'message' => $message
                ]
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        if ($response === null) {
            // no clue why response may fail, need to debug later on
            return (object)['status' => 'unauthorized'];
        }

        switch ($response->getStatusCode()) {
            case \Symfony\Component\HttpFoundation\Response::HTTP_OK:
                return (object)['status' => 'ok', 'data' => json_decode((string)$response->getBody())];

            case \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED:
                return (object)['status' => 'unauthorized'];
        }
        return (object)['status' => 'unknown'];
    }

    /**
     * @param array $postData
     * @return LinkedinQueue
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

        $queue = LinkedinQueue::create([
            'client_id' => $client_id,
            'template_id' => $template_id,
            'post_count' => 0,
            'backlinks' => 0,
            'jobs' => 0
        ]);
        $jobs = 0;

        $template = Template::find($template_id);

        $time = Carbon::now();

        $captionSpintax = Parser::parse($template->caption);
        $messageSpintax = Parser::parse($template->message);

        foreach ($groups as $group) {
            $caption = $captionSpintax->generate();
            $message = $messageSpintax->generate();

            dispatch((new PostMessage(
                $queue, $caption, $message, $group
            ))->delay($time->addSeconds($delay)));

            $jobs++;
        }

        $queue->update(['jobs' => $jobs]);

        return $queue;
    }

    /*
    |--------------------------------------------------------------------------
    | Private helper functions
    |--------------------------------------------------------------------------
    */

    private function local($type, $data = [])
    {
        $this->getRequestData($type, $method, $uri);

        $client = new Client();

        $res = $client->request($method, config('app.local-server') . $uri, [
            'json' => $data
        ]);
        return json_decode((string)$res->getBody());
    }

    private function getRequestData($type, &$method, &$uri)
    {
        switch ($type) {
            case self::CHECK_AUTH:
                $method = 'POST';
                $uri = '/check-auth';
                break;
        }
    }
}