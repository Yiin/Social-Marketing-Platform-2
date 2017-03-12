<?php

namespace App\Modules\Twitter\Mail;

use App\Modules\Twitter\Models\TwitterQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportStats extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var TwitterQueue
     */
    public $q;

    /**
     * Create a new message instance.
     * @param TwitterQueue $q
     */
    public function __construct(TwitterQueue $q)
    {
        $this->q = $q;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('twitter.email-stats');
    }
}