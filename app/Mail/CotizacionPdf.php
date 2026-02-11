<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CotizacionPdf extends Mailable
{
    use Queueable, SerializesModels;

    public $cotizacion;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion, string $pdfContent)
    {
        $this->cotizacion = $cotizacion;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $folio = str_pad($this->cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: "Cotización #{$folio} - {$this->cotizacion->titulo_cotizacion} | Cliché Marketing Digital",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizaciones.pdf-enviado',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $folio = str_pad($this->cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $this->pdfContent,
                "Cotizacion-{$folio}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
