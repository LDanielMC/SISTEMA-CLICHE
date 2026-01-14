<?php

namespace App\Mail;

use App\Models\Evento;
use App\Models\EventoRecordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioEvento extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $recordatorio;

    /**
     * Create a new message instance.
     */
    public function __construct(Evento $evento, EventoRecordatorio $recordatorio)
    {
        $this->evento = $evento;
        $this->recordatorio = $recordatorio;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Recordatorio: ' . $this->evento->titulo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-evento',
            with: [
                'evento' => $this->evento,
                'recordatorio' => $this->recordatorio,
                'tiempoAntes' => $this->formatearTiempo($this->recordatorio->minutos_antes),
            ],
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

    private function formatearTiempo($minutos)
    {
        if ($minutos < 60) {
            return "{$minutos} minutos";
        } elseif ($minutos < 1440) {
            $horas = floor($minutos / 60);
            return "{$horas} " . ($horas == 1 ? 'hora' : 'horas');
        } else {
            $dias = floor($minutos / 1440);
            return "{$dias} " . ($dias == 1 ? 'día' : 'días');
        }
    }
}
