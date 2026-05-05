<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuratPurgedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Surat $surat;
    public string $purgedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Surat $surat, string $purgedBy)
    {
        $this->surat = $surat;
        $this->purgedBy = $purgedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Hanya notifikasi database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'info',
            'title'   => 'File Surat Dihapus (Pembersihan)',
            'message' => "File fisik surat {$this->surat->judul} telah dibersihkan oleh {$this->purgedBy}.",
            'url'     => route('admin.surat.show', $this->surat),
            'surat_id'=> $this->surat->id,
        ];
    }
}