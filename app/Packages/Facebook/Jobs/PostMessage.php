<?php

namespace App\Packages\Facebook\Jobs;

use App\Packages\Facebook\Mail\ReportStats;
use App\Packages\Facebook\Models\FacebookAccount;
use App\Packages\Facebook\Models\FacebookGroup;
use App\Packages\Facebook\Models\FacebookPost;
use App\Packages\Facebook\Models\FacebookQueue;
use App\Packages\Facebook\Services\ApiService;
use App\Vendor\Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var FacebookQueue
     */
    private $facebookQueue;

    /**
     * @var string
     */
    private $post;

    /**
     * @var array
     */
    private $group;

    /**
     * Create a new job instance.
     *
     * @param FacebookQueue $queue
     * @param $postData
     * @param $group
     */
    public function __construct(FacebookQueue $queue, $postData, $group)
    {
        $this->facebookQueue = $queue;
        $this->post = $postData;
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @param ApiService $apiService
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        $queue = FacebookQueue::find($this->facebookQueue->id);

        /*
         * Check if it's the last job in queue
         */
        $queue->decrement('jobs');

        if ($queue->jobs <= 0) {
            /*
             * If yes, send an email to client we posted to.
             */
            \Mail::to($queue->client->email)->send(new ReportStats($queue));
        }

        $account = FacebookAccount::find($this->group['account_id']);

        $facebook = new Facebook($config = [
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET')
        ]);
        $facebook->setDefaultAccessToken($account->access_token);
//        \Log::debug(print_r($this->post, true));

        try {
            $r = $facebook->post("/{$this->group['groupId']}/feed", $this->post, $account->access_token);//->getBody();
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            \Log::error('Facebook - ' . $e->getMessage());
            return;
        }
        list($groupid, $postid) = explode('_', json_decode($r->getBody())->id);

        $group = FacebookGroup::where('groupId', $this->group['groupId'])->first();

        $queue->increment('post_count');
        $queue->increment('backlinks', $group->members);

        FacebookPost::create([
            'facebook_queue_id' => $queue->id,
            'url' => "https://www.facebook.com/groups/$groupid/permalink/$postid/",
            'message' => $this->post['message'],
            'group_name' => $group->name
        ]);
    }
}