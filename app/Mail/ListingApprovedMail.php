<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ListingApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Listing $listing,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'GreenGrid APAC: listing approved and active',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.listing-approved',
            with: [
                'listing' => $this->listing,
                'location' => $this->listing->location,
            ],
        );
    }
}
