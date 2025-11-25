<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class mailCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $numero;
    public $nombre;
    public $token;
    public $sorteo;


    public function __construct($ticket)
    {


        $this->numero = json_decode($ticket->numeros_seleccionados);
        $this->nombre = $ticket->nombre_cliente;
        $this->token = $ticket->ticket_token;
        $this->sorteo = $ticket->nombre_sorteo;


    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu número del sorteo ha sido asignado!',
        );
    }

    
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendMail',
            with: [
                'nombre' => $this->nombre,
                'numeros' => $this->numero,
                'token' => $this->token,
                'sorteo' => $this->sorteo,
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
