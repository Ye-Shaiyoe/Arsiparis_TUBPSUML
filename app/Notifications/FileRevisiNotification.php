<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FileRevisiNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Surat $surat,
        public int $tahapDitolak,
        public string $namaTahapDitolak
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $revisiKe = $this->surat->revisi_count;

        return [
            'surat_id'       => $this->surat->id,
            'type'           => 'warning',
            'title'          => "📝 File perbaikan diajukan (Revisi ke-{$revisiKe})",
            'message'        => "\"{$this->surat->judul}\" dari {$this->surat->user->name} mengupload file perbaikan. Ditolak pada tahap: {$this->namaTahapDitolak}.",
            'url'            => route('admin.surat.show', $this->surat->id),
            'jenis'          => $this->surat->jenis_label,
            'sifat'          => $this->surat->sifat,
            'tahap_ditolak'  => $this->tahapDitolak,
            'nama_tahap'     => $this->namaTahapDitolak,
            'revisi_count'   => $this->surat->revisi_count,
        ];
    }
}
