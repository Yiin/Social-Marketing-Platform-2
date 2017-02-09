<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Post;
use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostToGooglePlus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * @param Queue|Model $queue
     * @param $message
     * @param $url
     * @param $group
     * @param $categoryId
     */
    public function __construct(Queue $queue, $message, $url, $group, $categoryId)
    {
        $this->message = $message;
        $this->url = $url;
        $this->group = $group;
        $this->categoryId = $categoryId;
        $this->queue = $queue;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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

        Post::create([
            'queue_id' => $this->queue->id,
            'data' => [
                'url' => $result['postURL'],
                'message' => $this->message,
                'groupName' => $this->group['name']
            ]
        ]);
    }
}
