<?php

namespace App\Mail;

use App\Models\KycSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class KycSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public KycSubmission $submission,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'GreenGrid APAC: your company verification was received',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.kyc-submitted',
            with: [
                'companyName' => $this->submission->company->name,
                'role' => $this->submission->requested_role,
                'country' => $this->submission->country,
                'decisionEstimateHours' => 48,
            ],
        );
    }
}
