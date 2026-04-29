@extends('layouts.admin')
@section('title', 'Notifikasi')

@section('content')

<style>
    .notif-container { max-width: 900px; }
    .notif-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .notif-header h5 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        transition: color 0.3s;
    }
    .notif-actions { display: flex; gap: 6px; }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s, background 0.3s, border-color 0.3s;
        color: var(--text-secondary);
        font-size: 14px;
        padding: 0;
        text-decoration: none;
    }
    .action-btn:hover {
        background: var(--bg-tertiary);
        border-color: var(--border-color);
        transform: translateY(-1px);
    }
    .action-btn.read-all:hover { background: #eff6ff; border-color: #93c5fd; color: #2563eb; }
    .action-btn.delete-all:hover { background: #fef2f2; border-color: #fca5a5; color: #dc2626; }

    .notif-filters {
        display: flex;
        gap: 6px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        border: 1.5px solid var(--border-color);
        color: var(--text-secondary);
        transition: all 0.2s, background 0.3s, border-color 0.3s;
        background: var(--bg-secondary);
    }
    .filter-pill:hover { border-color: var(--border-color); color: var(--text-primary); text-decoration: none; transform: translateY(-1px); }
    .filter-pill.active { background: #1e3a5f; color: #fff; border-color: #1e3a5f; }
    .filter-pill.unread { background: #fffbeb; color: #92400e; border-color: #fbbf24; }
    .filter-pill.unread.active { background: #f59e0b; color: #fff; border-color: #f59e0b; }
    .filter-pill.read { background: #f0fdf4; color: #166534; border-color: #86efac; }
    .filter-pill.read.active { background: #22c55e; color: #fff; border-color: #22c55e; }

    .notif-list { display: flex; flex-direction: column; gap: 8px; }
    .notif-item {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 12px 16px;
        transition: all 0.2s, background 0.3s, border-color 0.3s;
        position: relative;
        overflow: hidden;
    }
    .notif-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--border-color);
        transition: all 0.2s;
    }
    .notif-item:hover {
        border-color: var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transform: translateX(2px);
    }
    .notif-item.unread::before { background: #3b82f6; }
    .notif-item.unread { background: rgba(59, 130, 246, 0.05); }
    .notif-item.type-success::before { background: #22c55e; }
    .notif-item.type-danger::before { background: #ef4444; }
    .notif-item.type-warning::before { background: #f59e0b; }
    .notif-item.type-info::before { background: #3b82f6; }

    .notif-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .notif-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .notif-icon.success { background: linear-gradient(135deg, #dcfce7, #bbf7d0); }
    .notif-icon.danger { background: linear-gradient(135deg, #fee2e2, #fecaca); }
    .notif-icon.warning { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .notif-icon.info { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }

    .notif-content { flex: 1; min-width: 0; }
    .notif-message {
        font-size: 12px;
        color: var(--text-primary);
        line-height: 1.4;
        margin: 0;
        transition: color 0.3s;
    }
    .notif-time {
        font-size: 10px;
        color: var(--text-secondary);
        margin-top: 2px;
        transition: color 0.3s;
    }
    .notif-time i { margin-right: 2px; }

    .notif-buttons {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
        align-items: center;
    }
    .notif-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1.5px solid var(--border-color);
        background: var(--bg-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s, background 0.3s, border-color 0.3s;
        color: var(--text-secondary);
        font-size: 13px;
        padding: 0;
        text-decoration: none;
    }
    .notif-btn:hover { transform: translateY(-2px); box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .notif-btn.read { color: #3b82f6; border-color: #93c5fd; }
    .notif-btn.read:hover { background: #eff6ff; border-color: #3b82f6; }
    .notif-btn.view { color: var(--text-secondary); }
    .notif-btn.view:hover { background: var(--bg-tertiary); border-color: var(--text-secondary); }
    .notif-btn.delete { color: #ef4444; border-color: #fca5a5; }
    .notif-btn.delete:hover { background: #fef2f2; border-color: #ef4444; }

    .unread-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 8px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 6px;
        letter-spacing: 0.5px;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--text-secondary);
        transition: color 0.3s;
    }
    .empty-state i { font-size: 40px; opacity: 0.4; margin-bottom: 12px; display: block; }
</style>

<div class="notif-container">
    {{-- Header --}}
    <div class="notif-header">
        <h5>🔔 Notifikasi</h5>
        <div class="notif-actions">
            @if($unreadCount > 0)
            <button type="button" class="action-btn read-all" title="Tandai Semua Dibaca" data-action="mark-all-read">
                <i class="bi bi-check-all"></i>
            </button>
            @endif
            <button type="button" class="action-btn delete-all" title="Hapus Semua" data-action="delete-all">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="notif-filters">
        <a href="{{ route('admin.notifikasi.index', ['filter' => 'all']) }}" 
           class="filter-pill {{ $filter === 'all' ? 'active' : '' }}">
            Semua
        </a>
        <a href="{{ route('admin.notifikasi.index', ['filter' => 'unread']) }}" 
           class="filter-pill {{ $filter === 'unread' ? 'unread active' : 'unread' }}">
            <i class="bi bi-circle-fill" style="font-size:6px;"></i>
            Belum Dibaca
            @if($unreadCount > 0)
            <span class="badge bg-danger" style="font-size:9px;padding:1px 5px;">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.notifikasi.index', ['filter' => 'read']) }}" 
           class="filter-pill {{ $filter === 'read' ? 'read active' : 'read' }}">
            Sudah Dibaca
        </a>
    </div>

    {{-- List --}}
    @if($notifications->isEmpty())
    <div class="empty-state">
        <i class="bi bi-bell-slash"></i>
        <div style="font-size:13px;">Tidak ada notifikasi</div>
    </div>
    @else
    <div class="notif-list">
        @foreach($notifications as $notif)
        @php
            $isUnread = is_null($notif->read_at);
            $type = $notif->data['type'] ?? 'info';
            $icons = ['success' => '✅', 'danger' => '❌', 'warning' => '⚠️', 'info' => 'ℹ️'];
            $icon = $icons[$type] ?? 'ℹ️';
        @endphp

        <div class="notif-item {{ $isUnread ? 'unread' : '' }} type-{{ $type }}">
            <div class="notif-row">
                {{-- Icon --}}
                <div class="notif-icon {{ $type }}">{{ $icon }}</div>

                {{-- Content --}}
                <div class="notif-content">
                    <p class="notif-message">
                        {{ $notif->data['message'] ?? '' }}
                        @if($isUnread)
                        <span class="unread-badge">BARU</span>
                        @endif
                    </p>
                    <div class="notif-time">
                        <i class="bi bi-clock"></i> {{ $notif->created_at->diffForHumans() }}
                    </div>
                </div>

                {{-- Buttons - Horizontal --}}
                <div class="notif-buttons">
                    @if($isUnread)
                    <button type="button" class="notif-btn read" title="Tandai Dibaca" 
                            data-action="mark-read" data-notif-id="{{ $notif->id }}">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    @endif
                    <a href="{{ $notif->data['url'] ?? route('admin.dashboard') }}" class="notif-btn view" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                    <button type="button" class="notif-btn delete" title="Hapus" 
                            data-action="delete" data-notif-id="{{ $notif->id }}">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Mark as Read
    document.querySelectorAll('[data-action="mark-read"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notifId = this.getAttribute('data-notif-id');
            markAsRead(notifId);
        });
    });

    // Handle Delete
    document.querySelectorAll('[data-action="delete"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notifId = this.getAttribute('data-notif-id');
            deleteNotif(notifId);
        });
    });

    // Handle Mark All as Read
    const markAllBtn = document.querySelector('[data-action="mark-all-read"]');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
                markAllAsRead();
            }
        });
    }

    // Handle Delete All
    const deleteAllBtn = document.querySelector('[data-action="delete-all"]');
    if (deleteAllBtn) {
        deleteAllBtn.addEventListener('click', function() {
            if (confirm('Hapus semua notifikasi?')) {
                deleteAll();
            }
        });
    }
});

function markAsRead(notifId) {
    const url = `/Admin/Notifikasi/read/${notifId}`;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const notifItem = document.querySelector(`[data-notif-id="${notifId}"]`).closest('.notif-item');
            notifItem.classList.remove('unread');
            
            // Remove "BARU" badge
            const badge = notifItem.querySelector('.unread-badge');
            if (badge) badge.remove();
            
            // Remove mark-read button
            const markBtn = notifItem.querySelector('[data-action="mark-read"]');
            if (markBtn) markBtn.remove();
            
            updateUnreadCount(data.unreadCount);
            showToast('Notifikasi ditandai dibaca', 'success');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('Gagal menandai notifikasi', 'error');
    });
}

function deleteNotif(notifId) {
    const url = `/Admin/Notifikasi/${notifId}`;
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const notifItem = document.querySelector(`[data-notif-id="${notifId}"]`).closest('.notif-item');
            notifItem.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => notifItem.remove(), 300);
            
            updateUnreadCount(data.unreadCount);
            showToast('Notifikasi dihapus', 'danger');
            
            // Reload jika list kosong
            setTimeout(() => {
                if (document.querySelectorAll('.notif-item').length === 0) {
                    location.reload();
                }
            }, 500);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('Gagal menghapus notifikasi', 'error');
    });
}

function markAllAsRead() {
    const url = "{{ route('admin.notifikasi.readAll') }}";
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('Gagal menandai semua notifikasi', 'error');
    });
}

function deleteAll() {
    const url = "{{ route('admin.notifikasi.deleteAll') }}";
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
            filter: '{{ $filter }}'
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('Gagal menghapus semua notifikasi', 'error');
    });
}

function updateUnreadCount(count) {
    const badge = document.querySelector('.filter-pill .badge');
    const markAllBtn = document.querySelector('[data-action="mark-all-read"]');
    
    if (count === 0) {
        if (badge) badge.remove();
        if (markAllBtn) markAllBtn.style.display = 'none';
    } else if (badge) {
        badge.textContent = count;
    }
}

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        z-index: 9999;
        animation: slideInUp 0.3s ease;
        background: ${type === 'success' ? '#22c55e' : '#ef4444'};
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutDown 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

// Add fade animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        to { opacity: 0; transform: translateX(-10px); }
    }
    @keyframes slideInUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideOutDown {
        to { transform: translateY(100px); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>

