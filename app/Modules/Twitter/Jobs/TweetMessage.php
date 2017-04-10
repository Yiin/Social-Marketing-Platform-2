<?php

namespace App\Modules\Twitter\Jobs;

use App\Modules\Errors\Models\ErrorLog;
use App\Modules\Twitter\Mail\ReportStats;
use App\Modules\Twitter\Models\Tweet;
use App\Modules\Twitter\Models\TwitterAccount;
use App\Modules\Twitter\Models\TwitterGroup;
use App\Modules\Twitter\Models\TwitterPost;
use App\Modules\Twitter\Models\TwitterQueue;
use App\Modules\Twitter\Services\ApiService;
use App\Vendor\Twitter\Twitter;
use Log;
use Twitter\Exceptions\TwitterSDKException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class TweetMessage
 * @package App\Modules\Twitter\Jobs
 */
class TweetMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TwitterQueue
     */
    private $twitterQueue;

    /**
     * @var string
     */
    private $post;

    /**
     * @var array
     */
    private $twitterAccount;

    /**
     * Create a new job instance.
     *
     * @param TwitterQueue $queue
     * @param $tweet
     * @param $twitterAccount
     */
    public function __construct(TwitterQueue $queue, $tweet, $twitterAccount)
    {
        $this->twitterQueue = $queue;
        $this->post = $tweet;
        $this->twitterAccount = $twitterAccount;
    }

    /**
     * Execute the job.
     *
     * @param ApiService $apiService
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        /**
         * @var $queue TwitterQueue
         */
        $queue = TwitterQueue::find($this->twitterQueue->id);

        /*
         * Check if it's the last job in queue
         */
        $queue->decrement('jobs');

        if ($queue->jobs === 0) {
            /*
             * If yes, send an email to client we posted to.
             */
            \Mail::to($queue->client->email)->send(new ReportStats($queue));
        }

        $account = TwitterAccount::find($this->twitterAccount['id']);

        $tweet = $apiService->postTweet($account, $this->post);

        if (!$tweet) {
            ErrorLog::report('Twitter error: Connection error.');
            return;
        }
        if (isset($tweet->errors)) {
            ErrorLog::report('Twitter error: ' . $tweet->errors[0]->message);
            return;
        }

        $queue->increment('tweet_count');

        Tweet::create([
            'twitter_queue_id' => $queue->id,
            'url' => "https://twitter.com/{$tweet->user->screen_name}/status/{$tweet->id}",
            'message' => $this->post
        ]);
    }
}