@extends('layouts.user')

@section('title', 'Pusat Notifikasi')

@section('content')
<div class="container-fluid animate-in">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: var(--text-primary);">
                                <i class="bi bi-bell-fill text-primary me-2"></i> Pusat Notifikasi
                            </h4>
                            <p class="text-muted small mb-0">Kelola dan lihat riwayat pemberitahuan Anda</p>
                        </div>
                        <div class="d-flex gap-2">
                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <button type="button" onclick="markAllAsRead()" class="btn btn-sm btn-outline-primary" style="border-radius: 10px;" id="btnMarkAll">
                                    <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
                                </button>
                            @endif
                            @if(!$notifications->isEmpty())
                                <button type="button" onclick="deleteAllNotif()" class="btn btn-sm btn-outline-danger" style="border-radius: 10px;">
                                    <i class="bi bi-trash me-1"></i> Hapus Semua
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-bell-slash text-muted" style="font-size: 64px;"></i>
                            </div>
                            <h5 class="text-muted">Belum ada notifikasi</h5>
                            <p class="text-muted small">Semua pemberitahuan aktivitas Anda akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="notif-list">
                            @foreach($notifications as $notif)
                                @php $isUnread = is_null($notif->read_at); @endphp
                                <div class="notif-card p-3 mb-3 {{ $isUnread ? 'unread' : '' }}" 
                                     id="notif-{{ $notif->id }}"
                                     style="border-radius: 16px; border: 1px solid var(--border-color); background: {{ $notif->read_at ? 'var(--bg-tertiary)' : 'rgba(37, 99, 235, 0.05)' }}; transition: all 0.3s ease; position: relative;">
                                    <div class="d-flex gap-3">
                                        <div class="notif-icon-circle flex-shrink-0" 
                                             style="width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                            @switch($notif->data['type'] ?? 'info')
                                                @case('success') <i class="bi bi-check-circle-fill text-success" style="font-size: 20px;"></i> @break
                                                @case('warning') <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 20px;"></i> @break
                                                @case('danger')  <i class="bi bi-x-circle-fill text-danger" style="font-size: 20px;"></i> @break
                                                @default         <i class="bi bi-info-circle-fill text-primary" style="font-size: 20px;"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1 fw-bold" style="color: var(--text-primary); font-size: 15px;">
                                                    {{ $notif->data['title'] ?? 'Notifikasi' }}
                                                </h6>
                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="text-muted" style="font-size: 11px;">
                                                        {{ $notif->created_at->diffForHumans() }}
                                                    </small>
                                                    <button onclick="deleteNotif('{{ $notif->id }}')" class="btn btn-link text-danger p-0 border-0" title="Hapus">
                                                        <i class="bi bi-x-lg" style="font-size: 12px;"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="mb-2 text-secondary small" style="line-height: 1.5;">
                                                {{ $notif->data['message'] ?? '' }}
                                            </p>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('notif.read', $notif->id) }}" class="btn btn-sm btn-primary py-1 px-3" style="font-size: 11px; border-radius: 8px;">
                                                    Lihat Detail
                                                </a>
                                                @if($isUnread)
                                                    <button onclick="markAsRead('{{ $notif->id }}')" class="btn btn-sm btn-light py-1 px-3" id="btnRead-{{ $notif->id }}" style="font-size: 11px; border-radius: 8px; background: #fff; border: 1px solid #e2e8f0;">
                                                        Tandai Dibaca
                                                    </button>
                                                    <span class="badge bg-primary rounded-pill d-flex align-items-center px-2 badge-baru" style="font-size: 10px;">Baru</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notif-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: rgba(37, 99, 235, 0.2) !important;
    }
    .notif-card.unread::before {
        content: '';
        position: absolute;
        left: -1px;
        top: 20%;
        bottom: 20%;
        width: 4px;
        background: #3b82f6;
        border-radius: 0 4px 4px 0;
    }
    @keyframes fadeOut {
        to { opacity: 0; transform: translateX(20px); }
    }
</style>

<script>
    function markAsRead(id) {
        fetch(`/notif/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById(`notif-${id}`);
                card.classList.remove('unread');
                card.style.background = 'var(--bg-tertiary)';
                
                const btnRead = document.getElementById(`btnRead-${id}`);
                if (btnRead) btnRead.remove();
                
                const badge = card.querySelector('.badge-baru');
                if (badge) badge.remove();
                
                if (data.unreadCount === 0) {
                    const btnAll = document.getElementById('btnMarkAll');
                    if (btnAll) btnAll.remove();
                }
            }
        });
    }

    function markAllAsRead() {
        if (!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) return;
        
        fetch(`{{ route('notif.readAll') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function deleteNotif(id) {
        if (!confirm('Hapus notifikasi ini?')) return;
        fetch(`/notif/delete/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                const card = document.getElementById(`notif-${id}`);
                card.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll('.notif-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            } else {
                alert('Gagal menghapus notifikasi: ' + (data.message || 'Error tidak diketahui'));
            }
        });
    }

    function deleteAllNotif() {
        if (!confirm('Hapus SEMUA notifikasi Anda? Tindakan ini tidak bisa dibatalkan.')) return;
        
        fetch(`{{ route('notif.deleteAll') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                location.reload();
            }
        });
    }
</script>
@endsection
