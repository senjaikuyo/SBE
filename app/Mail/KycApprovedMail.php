<?php

namespace App\Mail;

use App\Models\KycSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class KycApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public KycSubmission $submission,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'GreenGrid APAC: company verification approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.kyc-approved',
            with: [
                'companyName' => $this->submission->company->name,
                'role' => $this->submission->requested_role,
            ],
        );
    }
}
