<?php

namespace App\Notifications;

use App\Models\Komentar;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $komentar;

    public function __construct(Komentar $komentar)
    {
        $this->komentar = $komentar;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_comment',
            'komentar_id' => $this->komentar->id,
            'surat_id' => $this->komentar->surat_id,
            'user_name' => $this->komentar->user->name,
            'user_id' => $this->komentar->user_id,
            'isi' => $this->komentar->isi,
            'judul_surat' => $this->komentar->surat->judul,
            'message' => $this->komentar->user->name . ' mengomentari surat "' . $this->komentar->surat->judul . '"',
            'severity' => 'info',
        ];
    }
}
