<?php

namespace App\Mail;

use App\Models\Evenement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvenementModifie extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Evenement $evenement
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Événement modifié : ' . $this->evenement->nom,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.evenement-modifie',
        );
    }
}
