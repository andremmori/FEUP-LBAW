<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    // Info
    public $email;
    public $subject;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $reason)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact');
    }
}
