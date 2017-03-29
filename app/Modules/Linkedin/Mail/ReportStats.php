<?php

namespace App\Modules\Linkedin\Mail;

use App\Modules\Linkedin\Models\LinkedinQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportStats extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var LinkedinQueue
     */
    public $q;

    /**
     * Create a new message instance.
     * @param LinkedinQueue $q
     */
    public function __construct(LinkedinQueue $q)
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
        return $this->markdown('linkedin.email-stats');
    }
}