@extends('layouts.itsupport')

@section('content')
<div class="animate-in" style="animation: slideIn 0.5s ease-out;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="margin: 0; font-weight: 700; color: #111827;">System Overview</h2>
            <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 14px;">Selamat datang kembali, IT Support. Berikut adalah status sistem saat ini.</p>
        </div>
        <div style="background: white; padding: 8px 15px; border-radius: 10px; border: 1px solid #e5e7eb; display: flex; align-items: center; gap: 10px;">
            <div style="width: 10px; height: 10px; background: #10b981; border-radius: 50%; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);"></div>
            <span style="font-size: 13px; font-weight: 600; color: #374151;">System Status: Optimal</span>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-check-circle-fill" style="font-size: 18px;"></i>
            <span style="font-size: 14px; font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;"><i class="bi bi-cpu"></i></div>
            <div class="stat-info">
                <span class="stat-label">CPU Usage</span>
                <span class="stat-value">12.5%</span>
            </div>
            <div class="stat-progress"><div style="width: 12.5%; background: #3b82f6;"></div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f0fdf4; color: #10b981;"><i class="bi bi-memory"></i></div>
            <div class="stat-info">
                <span class="stat-label">RAM Usage</span>
                <span class="stat-value">45.2%</span>
            </div>
            <div class="stat-progress"><div style="width: 45.2%; background: #10b981;"></div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fff7ed; color: #f59e0b;"><i class="bi bi-hdd"></i></div>
            <div class="stat-info">
                <span class="stat-label">Storage</span>
                <span class="stat-value">68.1%</span>
            </div>
            <div class="stat-progress"><div style="width: 68.1%; background: #f59e0b;"></div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef2f2; color: #ef4444;"><i class="bi bi-activity"></i></div>
            <div class="stat-info">
                <span class="stat-label">Active Users</span>
                <span class="stat-value">24</span>
            </div>
            <div style="font-size: 11px; color: #9ca3af; margin-top: 8px;">Real-time sessions</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
        {{-- KOTAK ASPIRASI --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="margin: 0; font-weight: 700; color: #111827;">
                        <i class="bi bi-chat-right-heart-fill me-2 text-primary"></i> Kotak Aspirasi
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill" style="font-size: 11px;">{{ $aspirasis->count() }} Menunggu</span>
                </div>

            @forelse($aspirasis as $asp)
                <div class="aspirasi-item-it p-3 mb-3" style="border: 1px solid #e5e7eb; border-radius: 16px; background: #ffffff; transition: all 0.3s ease;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ 
                            match($asp->kategori) {
                                'bug', 'keluhan' => 'bg-danger',
                                'error' => 'bg-warning text-dark',
                                'fitur' => 'bg-success',
                                'saran' => 'bg-primary',
                                default => 'bg-info'
                            } 
                        }} rounded-pill" style="font-size: 10px; padding: 5px 10px;">
                            {{ strtoupper($asp->kategori) }}
                        </span>
                        <small class="text-muted" style="font-size: 11px;"><i class="bi bi-clock me-1"></i>{{ $asp->created_at->diffForHumans() }}</small>
                    </div>
                    <h6 class="fw-bold mb-1" style="font-size: 14px; color: #1f2937;">{{ $asp->judul }}</h6>
                    <p class="text-muted mb-3" style="font-size: 13px; line-height: 1.5;">{{ Str::limit($asp->isi, 100) }}</p>
                    
                    @if($asp->balasan)
                        <div class="p-2 mt-2 bg-white rounded border-start border-success border-4">
                            <div class="small fw-bold text-success mb-1">Sudah Dibalas:</div>
                            <div class="small text-muted">{{ Str::limit($asp->balasan, 60) }}</div>
                        </div>
                    @else
                        <button class="btn btn-sm btn-outline-info w-100 mt-2" onclick="openReplyModal('{{ $asp->uuid }}', '{{ addslashes($asp->judul) }}')" style="font-size: 11px; border-radius: 8px;">
                            <i class="bi bi-reply me-1"></i> Balas Aspirasi
                        </button>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-chat-dots text-muted" style="font-size: 40px; opacity: 0.2;"></i>
                    <p class="text-muted small mt-2">Tidak ada aspirasi untuk IT Support.</p>
                </div>
            @endforelse
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="margin: 0; font-weight: 700; color: #111827;">
                        <i class="bi bi-bell-fill me-2 text-warning"></i> Notifikasi
                    </h5>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($notifications as $notif)
                    <div class="notif-item-it p-3" style="border: 1px solid #f3f4f6; border-radius: 12px; background: {{ $notif->read_at ? 'white' : '#fffbeb' }}; transition: all 0.2s;">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0" style="width: 32px; height: 32px; border-radius: 8px; background: {{ match($notif->data['type'] ?? '') { 'success' => '#ecfdf5', 'danger' => '#fef2f2', 'warning' => '#fffbeb', default => '#eff6ff' } }}; display: flex; align-items: center; justify-content: center;">
                                <i class="bi {{ match($notif->data['type'] ?? '') { 'success' => 'bi-check-lg text-success', 'danger' => 'bi-x-lg text-danger', 'warning' => 'bi-exclamation-triangle text-warning', default => 'bi-info-lg text-primary' } }}" style="font-size: 14px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1" style="font-size: 13px;">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $notif->data['message'] ?? '' }}</div>
                                <div class="text-muted mt-2" style="font-size: 10px; opacity: 0.7;">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 40px; opacity: 0.2;"></i>
                        <p class="text-muted small mt-2">Belum ada notifikasi.</p>
                    </div>
                @endforelse
            </div>
            @if($notifications->count() > 0)
                <button class="btn btn-light mt-3 w-100 fw-bold text-primary" style="font-size: 12px; border-radius: 12px;">Lihat Semua Notifikasi</button>
            @endif
            </div>
        </div>
    </div>

    {{-- Modal Balas Aspirasi --}}
    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="font-size: 20px; color: #111827;">Balas Aspirasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="replyForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Judul Aspirasi</label>
                            <input type="text" id="modal_asp_judul" class="form-control" readonly style="background: #f9fafb; font-size: 14px;">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Isi Balasan (IT Support)</label>
                            <textarea name="balasan" class="form-control" rows="4" placeholder="Tuliskan solusi atau jawaban teknis di sini..." required style="font-size: 14px; border-radius: 12px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 14px;">Batal</button>
                        <button type="submit" class="btn btn-info text-white px-4" style="border-radius: 10px; font-size: 14px;">Kirim Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 15px;
    }
    .stat-info {
        display: flex;
        flex-direction: column;
    }
    .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-top: 4px;
    }
    .stat-progress {
        height: 4px;
        background: #f3f4f6;
        border-radius: 2px;
        margin-top: 15px;
        overflow: hidden;
    }
    .stat-progress div {
        height: 100%;
        border-radius: 2px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: scale(1.02);
    }
    .action-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #4b5563;
    }
    .action-text {
        display: flex;
        flex-direction: column;
    }
    .action-text strong {
        font-size: 14px;
        color: #111827;
    }
    .action-text small {
        font-size: 12px;
        color: #6b7280;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 8px;
        border-bottom: 1px dashed #e5e7eb;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 13px;
        color: #6b7280;
    }
    .info-val {
        font-size: 13px;
        color: #111827;
        font-weight: 500;
    }

    .card {
        border-radius: 20px !important;
    }

    .aspirasi-item-it:hover {
        border-color: #3b82f6 !important;
        background: white !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .notif-item-it:hover {
        transform: translateX(5px);
        border-color: #f59e0b !important;
    }
</style>

<script>
    function openReplyModal(uuid, judul) {
        document.getElementById('modal_asp_judul').value = judul;
        document.getElementById('replyForm').action = `/IT-Support/Aspirasi/${uuid}`;
        var myModal = new bootstrap.Modal(document.getElementById('replyModal'));
        myModal.show();
    }
</script>
@endsection
