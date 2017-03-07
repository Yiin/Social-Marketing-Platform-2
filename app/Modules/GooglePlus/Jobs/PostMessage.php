<?php

namespace App\Modules\GooglePlus\Jobs;

use App\Modules\Errors\Models\ErrorLog;
use App\Modules\GooglePlus\Mail\ReportStats;
use App\Modules\GooglePlus\Models\GoogleAccount;
use App\Modules\GooglePlus\Models\GooglePost;
use App\Modules\GooglePlus\Models\GoogleQueue;
use App\Modules\GooglePlus\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var GoogleQueue
     */
    private $googleQueue;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $group;

    /**
     * @var string
     */
    private $categoryId;

    /**
     * Create a new job instance.
     *
     * @param GoogleQueue $queue
     * @param $message
     * @param $url
     * @param $group
     * @param $categoryId
     */
    public function __construct(GoogleQueue $queue, $message, $url, $group, $categoryId)
    {
        $this->googleQueue = $queue;
        $this->message = $message;
        $this->url = $url;
        $this->group = $group;
        $this->categoryId = $categoryId;
    }

    /**
     * Execute the job.
     *
     * @param ApiService $apiService
     * @return void
     */
    public function handle(ApiService $apiService)
    {
        $queue = GoogleQueue::find($this->googleQueue->id);

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

        /*
         * Try to login to saved account
         */
        $accountId = $this->group['account_id'];
        $account = GoogleAccount::find($accountId);
        $success = $apiService->login($account);

        if (!$success) {
            // We couldn't, so let's just stop right here.
            ErrorLog::report('Google+ error: Login failed for account ' . $account->username);
            return;
        }

        /*
         * Try to post
         */
        $result = $apiService->postGP(
            $this->message, $this->url, '', $this->group['id'], $this->categoryId
        );


        /*
         * If we failed, log and stop.
         */
        if (!is_array($result) || !isset($result['isPosted']) || $result['isPosted'] != '1') {
            ErrorLog::report('Google+ error: ' . $result);
            return;
        }

        $queue->increment('post_count');

        $cacheKey = "GoogleQueue.{$queue->id}.LAST_CHECKED_GROUP";

        if (cache($cacheKey) != $this->group['id']) {
            cache([$cacheKey => $this->group['id']], 60);
            $queue->increment('backlinks', $this->group['members']);
        }

        GooglePost::create([
            'google_queue_id' => $queue->id,
            'url' => $result['postURL'],
            'message' => $this->message,
            'community_name' => $this->group['name']
        ]);
    }
}