<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteShipped extends Mailable
{
    use Queueable, SerializesModels;

    public Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cotización ' . $this->quote->folio . ' - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quotes.shipped',
        );
    }

    public function attachments(): array
    {
        // Genera el PDF en memoria y lo adjunta al correo
        $this->quote->load('items', 'client');
        $pdf = Pdf::loadView('quotes.pdf', ['quote' => $this->quote]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Cotizacion_' . $this->quote->folio . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}