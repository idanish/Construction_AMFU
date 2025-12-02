<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $modelType;
    public $modelId;
    public $approvalStep;
    public $modelDetails;
    public $actionUrl;

    public function __construct($modelType, $modelId, $approvalStep, $modelDetails = [], $actionUrl = null)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->approvalStep = $approvalStep;
        $this->modelDetails = $modelDetails;
        $this->actionUrl = $actionUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS', 'dev@amfu.net'),
            subject: "Approval Pending - {$this->modelType} #{$this->modelId} - {$this->approvalStep} Step"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-notification',
            with: [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'approvalStep' => $this->approvalStep,
                'modelDetails' => $this->modelDetails,
                'actionUrl' => $this->actionUrl,
            ],
        );
    }
}
