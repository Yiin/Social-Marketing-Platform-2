<?php

namespace App\Modules\Linkedin\Jobs;

use App\Modules\Errors\Models\ErrorLog;
use App\Modules\Linkedin\Mail\ReportStats;
use App\Modules\Linkedin\Models\LinkedinAccount;
use App\Modules\Linkedin\Models\LinkedinPost;
use App\Modules\Linkedin\Models\LinkedinQueue;
use App\Modules\Linkedin\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var LinkedinQueue
     */
    private $linkedinQueue;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $group;

    /**
     * Create a new job instance.
     *
     * @param LinkedinQueue $queue
     * @param $postData
     * @param $group
     */
    public function __construct(LinkedinQueue $queue, $caption, $message, $group)
    {
        $this->linkedinQueue = $queue;
        $this->caption = $caption;
        $this->message = $message;
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
        $queue = LinkedinQueue::find($this->linkedinQueue->id);

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

        $account = LinkedinAccount::find($this->group['account_id']);

        $post = $apiService->actuallyPost($account, $this->group['id'], $this->caption, $this->message);

        $group = $account->groups()->where('groupId', $this->group['id'])->first();

        switch ($post->status) {
            case 'ok':
                $queue->increment('post_count');
                $queue->increment('backlinks', $group->members);

                LinkedinPost::create([
                    'linkedin_queue_id' => $queue->id,
                    'url' => $post->data->link,
                    'message' => $this->message,
                    'group_name' => $group->name,
                ]);
                break;
            case 'unauthorized':
                ErrorLog::report("[LinkedIn] Couldn't authenticate {$account->email} account.");
                break;
            default:
                ErrorLog::report("[LinkedIn] Could't post to group {$group->name} with {$account->email} account.");
                break;
        }
    }
}
