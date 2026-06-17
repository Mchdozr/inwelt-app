<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        $subject = $this->contactMessage->subject
            ?: 'INWELT İletişim Formu';

        return new Envelope(
            subject: $subject,
            replyTo: [$this->contactMessage->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-message-received',
        );
    }
}
