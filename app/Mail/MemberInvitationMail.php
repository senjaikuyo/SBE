<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MemberInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Invitation $invitation,
        public string $token,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are invited to join '.$this->invitation->company->name.' on GreenGrid APAC',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.member-invitation',
            with: [
                'companyName' => $this->invitation->company->name,
                'role' => $this->invitation->role,
                'inviterName' => $this->invitation->inviter->name,
                'acceptUrl' => route('invitations.accept', $this->token),
                'expiresAt' => $this->invitation->expires_at,
            ],
        );
    }
}
