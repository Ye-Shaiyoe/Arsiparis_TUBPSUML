<?php

namespace App\Notifications;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratDiprosesNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Surat  $surat,
        public User   $diprosesByUser,
        public string $aksi, // 'disetujui' | 'ditolak'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $type = $this->aksi === 'ditolak' ? 'danger' : 'success';

        return [
            'surat_id' => $this->surat->id,
            'type'     => $type,
            'title'    => "Surat {$this->aksi} oleh {$this->diprosesByUser->name}",
            'message'  => "\"{$this->surat->judul}\" telah {$this->aksi} — sekarang di tahap {$this->surat->tahap_sekarang}/10.",
            'url'      => route('admin.surat.show', $this->surat),
        ];
    }
}