<?php

namespace App\Jobs;

use App\Mail\ReportStats;
use App\Models\Account;
use App\Models\Post;
use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostToGooglePlus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Queue
     */
    private $_queue;

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
     * @param Queue $queue
     * @param $message
     * @param $url
     * @param $group
     * @param $categoryId
     */
    public function __construct(Queue $queue, $message, $url, $group, $categoryId)
    {
        $this->_queue = $queue;
        $this->message = $message;
        $this->url = $url;
        $this->group = $group;
        $this->categoryId = $categoryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->_queue = Queue::find($this->_queue->id);

        $jobs = $this->_queue->stats['jobs'] - 1;
        $this->_queue->update(['stats->jobs' => $jobs]);

        if ($jobs == 0) {
            \Mail::to($this->_queue->client->email)->send(new ReportStats($this->_queue));
        }

        $accountId = $this->group['account_id'];

        $account = Account::find($accountId);
        $success = $account->socialMediaService->impl()->login($account->username, $account->password);

        if (!$success) {
            return;
        }

        $result = $account->socialMediaService->impl()->postGP(
            $this->message, $this->url, '', $this->group['id'], $this->categoryId
        );

        if (!is_array($result) || !isset($result['isPosted']) || $result['isPosted'] != '1') {
            \Log::error('GooglePlus - ' . $result);
            return;
        }

        $this->_queue->update(['stats->posts' => $this->_queue->stats['posts'] + 1]);

        $cacheKey = "Queue.{$this->_queue->id}.LAST_CHECKED_GROUP";

        if (cache($cacheKey) != $this->group['id']) {
            cache([$cacheKey => $this->group['id']], 60);
            $this->_queue->update(['stats->backlinks' => $this->_queue->stats['backlinks'] + $this->group['members']]);
        }

        Post::create([
            'queue_id' => $this->_queue->id,
            'data' => [
                'url' => $result['postURL'],
                'message' => $this->message,
                'groupName' => $this->group['name']
            ]
        ]);
    }
}
