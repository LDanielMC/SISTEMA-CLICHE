<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class RecordatorioPublicaciones extends Mailable
{
    use Queueable, SerializesModels;

    public $publicacionesHoy;
    public $publicacionesManana;
    public $publicacionesAtrasadas;

    /**
     * Create a new message instance.
     */
    public function __construct($publicacionesHoy, $publicacionesManana, $publicacionesAtrasadas)
    {
        $this->publicacionesHoy = $publicacionesHoy;
        $this->publicacionesManana = $publicacionesManana;
        $this->publicacionesAtrasadas = $publicacionesAtrasadas;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $totalHoy = count($this->publicacionesHoy);
        $totalAtrasadas = count($this->publicacionesAtrasadas);
        
        $subject = '📅 Recordatorio de Publicaciones';
        
        if ($totalAtrasadas > 0) {
            $subject = "⚠️ {$totalAtrasadas} publicaciones atrasadas + {$totalHoy} para hoy";
        } elseif ($totalHoy > 0) {
            $subject = "📅 {$totalHoy} publicaciones pendientes para hoy";
        }
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-publicaciones',
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
