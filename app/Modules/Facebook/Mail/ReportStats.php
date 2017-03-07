<?php

namespace App\Modules\Facebook\Mail;

use App\Modules\Facebook\Models\FacebookQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportStats extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var FacebookQueue
     */
    public $q;

    /**
     * Create a new message instance.
     * @param FacebookQueue $q
     */
    public function __construct(FacebookQueue $q)
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
        return $this->markdown('facebook.email-stats');
    }
}