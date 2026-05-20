<?php

namespace App\Mail;

use App\Models\CollegePartnershipDiscussion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollegePartnershipDiscussionReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CollegePartnershipDiscussion $discussion
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your partnership discussion request'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.college-partnership-discussion-received'
        );
    }
}
