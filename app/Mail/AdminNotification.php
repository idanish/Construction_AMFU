<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $modelType;
    public $modelId;
    public $action;
    public $details;
    public $dashboardUrl;

    public function __construct($modelType, $modelId, $action, $details = [], $dashboardUrl = null)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->action = $action;
        $this->details = $details;
        $this->dashboardUrl = $dashboardUrl;
    }

    public function envelope(): Envelope
    {
        $actionText = ucfirst($this->action);
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS', 'dev@amfu.net'),
            subject: "[ADMIN] {$actionText} - {$this->modelType} #{$this->modelId}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-notification',
            with: [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'action' => $this->action,
                'details' => $this->details,
                'dashboardUrl' => $this->dashboardUrl,
            ],
        );
    }
}
