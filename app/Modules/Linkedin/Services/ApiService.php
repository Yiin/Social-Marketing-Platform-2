<?php

namespace App\Modules\Linkedin\Services;

use App\Models\Template;
use App\Modules\Linkedin\Jobs\PostMessage;
use App\Modules\Linkedin\Models\LinkedinAccount;
use App\Modules\Linkedin\Models\LinkedinQueue;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use GuzzleHttp\Client;

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
     * @return mixed
     */
    public function check(LinkedinAccount $account)
    {
        $cookiesFile = storage_path("app/phantomjs/cookies/{$account->email}.json");

        $output = shell_exec($cmd = implode(' ', [
            'node',
            '--harmony-async-await',
            app_path("Modules/Linkedin/resources/js/phantomjs/check-auth.js"),
            "--email", escapeshellarg($account->email),
            "--password", escapeshellarg($account->password),
            "--loadCookies 0",
            "--cookiesFile", escapeshellarg($cookiesFile)
        ]));

        return json_decode($output);
    }

    /**
     * Get list of user groups
     *
     * @return array
     */
    public function groups(LinkedinAccount $account, $useLastSession = false)
    {
        $cookiesFile = storage_path("app/phantomjs/cookies/{$account->email}.json");

        $output = shell_exec($cmd = implode(' ', [
            'node',
            '--harmony-async-await',
            app_path("Modules/Linkedin/resources/js/phantomjs/groups.js"),
            "--email", escapeshellarg($account->email),
            "--password", escapeshellarg($account->password),
            "--loadCookies " . $useLastSession ? 1 : 0,
            "--cookiesFile", escapeshellarg($cookiesFile)
        ]));

        return json_decode($output);
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
        $cookiesFile = storage_path("app/phantomjs/cookies/{$account->email}.json");

        $output = shell_exec($cmd = escapeshellcmd(implode(' ', [
            'node',
            '--harmony-async-await',
            app_path("Modules/Linkedin/resources/js/phantomjs/post.js"),
            "--email", escapeshellarg($account->email),
            "--password", escapeshellarg($account->password),
            "--loadCookies 1",
            "--cookiesFile", escapeshellarg($cookiesFile),
            "--groupid", escapeshellarg($groupId),
            "--caption", escapeshellarg($caption),
            "--message", escapeshellarg($message)
        ])));

        \Log::debug($cmd);
        \Log::debug($output);

        return json_decode($output);
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