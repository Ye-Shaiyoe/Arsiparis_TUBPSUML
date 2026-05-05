<?php

namespace App\Notifications;

use App\Models\SuratDeleteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeleteRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SuratDeleteRequest $deleteRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $surat = $this->deleteRequest->surat;
        
        return [
            'delete_request_id' => $this->deleteRequest->id,
            'surat_id'          => $surat->id,
            'type'              => 'warning',
            'title'             => '🗑 Permintaan Hapus Surat',
            'message'           => "User {$this->deleteRequest->user->name} meminta penghapusan surat \"{$surat->judul}\". Alasan: {$this->deleteRequest->alasan}",
            'url'               => route('admin.surat.show', $surat),
            'alasan'            => $this->deleteRequest->alasan,
        ];
    }
}
