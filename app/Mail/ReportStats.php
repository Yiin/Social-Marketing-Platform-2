<?php

namespace App\Mail;

use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportStats extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Queue
     */
    public $q;

    /**
     * Create a new message instance.
     * @param Queue $q
     */
    public function __construct(Queue $q)
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
        return $this->view('emails.stats');
    }
}