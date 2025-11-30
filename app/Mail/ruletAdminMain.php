<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ruletAdminMain extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $nombre;
    public $cedula_cliente;
    public $premio;
    public $telefono;

    /**
     * Create a new message instance.
     */
    public function __construct(object $correoContent)
    {
        $this->nombre = $correoContent->nombre;
        $this->cedula_cliente = $correoContent->cedula;
        $this->premio = $correoContent->premio;
        $this->telefono = $correoContent->telefono;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ganador de premio en ruleta ruedayganaa.com (correo del administrador)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ruletMailAdmin',
            with: [
                'nombre' => $this->nombre,
                'cedula_cliente' => $this->cedula_cliente,
                'premio' => $this->premio,
                'contacto' => $this->telefono,
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
