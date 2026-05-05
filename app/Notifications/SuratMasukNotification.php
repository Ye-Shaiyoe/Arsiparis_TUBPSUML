<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratMasukNotification extends Notification
{
    use Queueable;

    public function __construct(public Surat $surat) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'surat_id'  => $this->surat->id,
            'type'      => 'info',
            'title'     => 'Surat baru masuk',
            'message'   => "Pengajuan baru: \"{$this->surat->judul}\" dari {$this->surat->user->name}.",
            'url'       => route('admin.surat.show', $this->surat),
            'jenis'     => $this->surat->jenis_label,
            'sifat'     => $this->surat->sifat,
        ];
    }
}