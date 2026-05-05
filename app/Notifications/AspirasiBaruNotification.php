<?php

namespace App\Notifications;

use App\Models\Aspirasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AspirasiBaruNotification extends Notification
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
            'type' => 'info',
            'title' => 'Aspirasi Baru',
            'message' => 'Ada aspirasi baru dari ' . $this->aspirasi->user->name . ': ' . $this->aspirasi->judul,
            'aspirasi_id' => $this->aspirasi->id,
            'link' => route('admin.aspirasi.index'),
        ];
    }
}
