<?php

namespace App\Mail;

use App\Models\CounsellingLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CounsellingLeadReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CounsellingLead $lead
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your counselling request'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.counselling-lead-received'
        );
    }
}
