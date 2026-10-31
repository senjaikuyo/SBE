<?php

namespace App\Mail;

use App\Models\KycSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class KycRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public KycSubmission $submission,
        public string $reason,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'GreenGrid APAC: company verification rejected',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.kyc-rejected',
            with: [
                'companyName' => $this->submission->company->name,
                'reason' => $this->reason,
            ],
        );
    }
}
