<?php

namespace App\Notifications;

use App\Models\Aspirasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AspirasiDibalasNotification extends Notification
{
    use Queueable;

    protected $aspirasi;

    public function __construct(Aspirasi $aspirasi)
    {
        $this->aspirasi = $aspirasi;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'success',
            'title' => 'Aspirasi Dibalas',
            'message' => 'Aspirasi Anda "' . $this->aspirasi->judul . '" telah dibalas oleh Admin.',
            'aspirasi_id' => $this->aspirasi->id,
            'link' => route('user.aspirasi.index'),
        ];
    }
}
