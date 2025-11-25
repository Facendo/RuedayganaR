<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ruletWinnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $premio;

    public function __construct($correoContent)
    {
        $this->nombre = $ruleta->nombre;
        $this->premio = $ruleta->premio;
    }


    /**
     * Get the message content definition.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ganador de premio en ruleta ruedayganaa.com',
        );
    }

     public function content(): Content
    {
        return new Content(
            view: 'emails.ruletMailClient',
            with: [
                'nombre' => $this->nombre,
                'premio' => $this->premio,
                
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
}
