<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioSuscripciones extends Mailable
{
    use Queueable, SerializesModels;

    public $suscripcionesPorVencer;
    public $suscripcionesVencidas;

    /**
     * Create a new message instance.
     */
    public function __construct($suscripcionesPorVencer, $suscripcionesVencidas)
    {
        $this->suscripcionesPorVencer = $suscripcionesPorVencer;
        $this->suscripcionesVencidas = $suscripcionesVencidas;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio de Suscripciones - Vencimientos Próximos',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio_suscripciones',
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
