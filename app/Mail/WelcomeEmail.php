<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $accountName;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($accountName, $password, $loginUrl)
    {
        $this->accountName = $accountName;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Digital Asset Portal')
            ->markdown('emails.welcome'); // Make sure this is correct

    }
}
