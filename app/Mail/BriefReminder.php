<?php

namespace App\Mail;

use App\Models\Brief;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BriefReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $brief;
    public $cliente;

    /**
     * Create a new message instance.
     */
    public function __construct(Brief $brief, $cliente)
    {
        $this->brief = $brief;
        $this->cliente = $cliente;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Recordatorio: Brief Pendiente - Cliché Marketing',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.briefs.reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
