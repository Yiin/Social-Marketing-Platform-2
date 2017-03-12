<?php

namespace App\Modules\GooglePlus\Mail;

use App\Modules\GooglePlus\Models\GoogleQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportStats extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var GoogleQueue
     */
    public $q;

    /**
     * Create a new message instance.
     * @param GoogleQueue $q
     */
    public function __construct(GoogleQueue $q)
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
        return $this->markdown('google.email-stats');
    }
}