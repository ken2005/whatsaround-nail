<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvenementAnnule extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nomEvenement,
        public string $dateHeure
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Événement annulé : ' . $this->nomEvenement,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.evenement-annule',
        );
    }
}
