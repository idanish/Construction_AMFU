<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $loginUrl;
    public $temporaryPassword;

    public function __construct($userName, $userEmail, $loginUrl = null, $temporaryPassword = null)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->loginUrl = $loginUrl;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS', 'dev@amfu.net'),
            subject: "Welcome to AMFU - Your Account Created"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'userName' => $this->userName,
                'userEmail' => $this->userEmail,
                'loginUrl' => $this->loginUrl,
                'temporaryPassword' => $this->temporaryPassword,
            ],
        );
    }
}
