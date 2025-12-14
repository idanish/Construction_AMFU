<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $modelType;
    public $modelId;
    public $submittedBy;
    public $modelDetails;
    public $approvalUrl;

    public function __construct($modelType, $modelId, $submittedBy, $modelDetails = [], $approvalUrl = null)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->submittedBy = $submittedBy;
        $this->modelDetails = $modelDetails;
        $this->approvalUrl = $approvalUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS', 'dev@amfu.net'),
            subject: "New Submission - {$this->modelType} #{$this->modelId} Submitted for Approval"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-submission',
            with: [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'submittedBy' => $this->submittedBy,
                'modelDetails' => $this->modelDetails,
                'approvalUrl' => $this->approvalUrl,
            ],
        );
    }
}
