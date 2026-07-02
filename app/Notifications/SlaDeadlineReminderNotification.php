<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SlaDeadlineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Surat  $surat,
        public string $tipe, // 'mendekati' | 'terlewat'
        public float  $sisaJam,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isTerlambat = $this->tipe === 'terlewat';

        if ($isTerlambat) {
            $title   = '🔴 SLA Terlewat!';
            $message = "Surat \"{$this->surat->judul}\" sudah melewati batas SLA "
                     . round($this->sisaJam, 1) . " jam yang lalu. Segera proses!";
        } else {
            $jam    = floor($this->sisaJam);
            $menit  = round(($this->sisaJam - $jam) * 60);
            $title  = '🟡 SLA Hampir Habis';
            $message = "Surat \"{$this->surat->judul}\" (Tahap {$this->surat->tahap_sekarang}: "
                     . "{$this->surat->nama_tahap}) harus selesai dalam {$jam}j {$menit}m.";
        }

        return [
            'surat_id' => $this->surat->id,
            'type'     => $isTerlambat ? 'danger' : 'warning',
            'title'    => $title,
            'message'  => $message,
            'url'      => route('admin.surat.show', $this->surat),
        ];
    }
}
