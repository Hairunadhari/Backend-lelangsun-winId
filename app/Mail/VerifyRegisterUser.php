<?php

namespace App\Mail;

use App\Models\User;
use App\Models\PesertaNpl;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PembayaranEvent;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyRegisterUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Menambahkan properti $user
    public $url; // Menambahkan properti $user

    public function __construct(User $user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('win@gmail.com','win'),
            subject: 'Verifikasi Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify_register',
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
