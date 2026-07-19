<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User   $user,
        public readonly string $plainPassword,
        public readonly string $createdByName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun Anda Telah Dibuat — Sistem Surat BPSUML',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-account',
            with: [
                'user'           => $this->user,
                'plainPassword'  => $this->plainPassword,
                'createdByName'  => $this->createdByName,
                'loginUrl'       => route('login'),
            ],
        );
    }
}
