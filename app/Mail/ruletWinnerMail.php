<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ruletWinnerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $nombre;
    public $premio;

    public function __construct(object $correoContent)
    {
        $this->nombre = $correoContent->nombre;
        $this->premio = $correoContent->premio;
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
