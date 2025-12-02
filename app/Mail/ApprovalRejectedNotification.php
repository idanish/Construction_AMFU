<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalRejectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $modelType;
    public $modelId;
    public $approvalStep;
    public $rejectionReason;
    public $rejectedBy;
    public $modelDetails;
    public $resubmitUrl;

    public function __construct($modelType, $modelId, $approvalStep, $rejectionReason, $rejectedBy, $modelDetails = [], $resubmitUrl = null)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->approvalStep = $approvalStep;
        $this->rejectionReason = $rejectionReason;
        $this->rejectedBy = $rejectedBy;
        $this->modelDetails = $modelDetails;
        $this->resubmitUrl = $resubmitUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS', 'dev@amfu.net'),
            subject: "Rejected - {$this->modelType} #{$this->modelId} - Action Required"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-rejected',
            with: [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'approvalStep' => $this->approvalStep,
                'rejectionReason' => $this->rejectionReason,
                'rejectedBy' => $this->rejectedBy,
                'modelDetails' => $this->modelDetails,
                'resubmitUrl' => $this->resubmitUrl,
            ],
        );
    }
}
