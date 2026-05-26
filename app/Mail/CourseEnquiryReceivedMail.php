<?php

namespace App\Mail;

use App\Models\CourseEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseEnquiryReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CourseEnquiry $enquiry
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your course enquiry'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.course-enquiry-received'
        );
    }
}
