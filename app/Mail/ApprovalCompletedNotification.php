<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $modelType;
    public $modelId;
    public $modelDetails;
    public $viewUrl;

    public function __construct($modelType, $modelId, $modelDetails = [], $viewUrl = null)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->modelDetails = $modelDetails;
        $this->viewUrl = $viewUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS', 'dev@amfu.net'),
            subject: "Approved - {$this->modelType} #{$this->modelId} is Now Fully Approved"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-completed',
            with: [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'modelDetails' => $this->modelDetails,
                'viewUrl' => $this->viewUrl,
            ],
        );
    }
}
