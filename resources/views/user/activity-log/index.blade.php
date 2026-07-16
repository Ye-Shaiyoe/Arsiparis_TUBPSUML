@extends('layouts.user')
@section('title', 'Activity Log')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2" style="border-radius:14px;border-left:4px solid #10b981 !important;" role="alert">
    <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="d-flex align-items-center gap-2 mb-4">
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);">
            <i class="bi bi-clock-history me-2"></i> Riwayat Aktivitas
        </h5>
        <small class="text-muted">Pantau semua aksi dan aktivitas Anda di sistem</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('user.activity-log.export', request()->query()) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:13px;">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
        @if($logs->total() > 0 || request()->hasAny(['search','action','model_type','date_from','date_to']))
        <button type="button" id="btn-hapus-semua" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:13px;">
            <i class="bi bi-trash3 me-1"></i> Hapus Semua Log
        </button>
        @endif
    </div>
</div>

{{-- Hidden form for DELETE method --}}
<form id="form-hapus-semua" method="POST" action="{{ route('user.activity-log.destroyAll') }}" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<div class="card card-custom mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('user.activity-log.index') }}" class="row g-3" data-turbo="false">
            {{-- Search --}}
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold" style="font-size:13px;color:var(--text-primary);">Pencarian</label>
                <input type="text" name="search" class="form-control" placeholder="Cari aksi atau deskripsi..." 
                       value="{{ request('search') }}" style="font-size:13px;background:var(--bg-secondary);border-color:var(--border-color);color:var(--text-primary);">
            </div>

            {{-- Filter Action --}}
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold" style="font-size:13px;color:var(--text-primary);">Jenis Aksi</label>
                <select name="action" class="form-select" style="font-size:13px;background:var(--bg-secondary);border-color:var(--border-color);color:var(--text-primary);">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Model Type --}}
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold" style="font-size:13px;color:var(--text-primary);">Tipe Model</label>
                <select name="model_type" class="form-select" style="font-size:13px;background:var(--bg-secondary);border-color:var(--border-color);color:var(--text-primary);">
                    <option value="">Semua Model</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type }}" {{ request('model_type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold" style="font-size:13px;color:var(--text-primary);">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" 
                       value="{{ request('date_from') }}" style="font-size:13px;background:var(--bg-secondary);border-color:var(--border-color);color:var(--text-primary);">
            </div>

            {{-- Date To --}}
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold" style="font-size:13px;color:var(--text-primary);">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" 
                       value="{{ request('date_to') }}" style="font-size:13px;background:var(--bg-secondary);border-color:var(--border-color);color:var(--text-primary);">
            </div>

            {{-- Filter Buttons --}}
            <div class="col-12 d-flex gap-2 pt-2">
                <button type="submit" class="btn btn-primary" style="font-size:13px;border-radius:8px;">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('user.activity-log.index') }}" class="btn btn-secondary" style="font-size:13px;border-radius:8px;">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

@if($logs->count() > 0)
    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:13px;">
                    <thead style="background:var(--bg-tertiary);border-bottom:2px solid var(--border-color);">
                        <tr>
                            <th style="color:var(--text-primary);font-weight:700;padding:1rem;">Tanggal & Waktu</th>
                            <th style="color:var(--text-primary);font-weight:700;padding:1rem;">Aksi</th>
                            <th style="color:var(--text-primary);font-weight:700;padding:1rem;">Model</th>
                            <th style="color:var(--text-primary);font-weight:700;padding:1rem;">Deskripsi</th>
                            <th style="color:var(--text-primary);font-weight:700;padding:1rem;">IP Address</th>
                            <th style="color:var(--text-primary);font-weight:700;padding:1rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr style="border-bottom:1px solid var(--border-color);">
                                <td style="padding:1rem;color:var(--text-primary);">
                                    {{ $log->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                </td>
                                <td style="padding:1rem;">
                                    <span class="badge rounded-pill" style="background:{{ $log->action_color }};color:white;font-size:11px;">
                                        <i class="bi {{ $log->action_icon }} me-1"></i>{{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td style="padding:1rem;color:var(--text-primary);">
                                    {{ $log->model_type ?? '-' }}<br>
                                    @if($log->model_id)
                                        <small class="text-muted">#{{ $log->model_id }}</small>
                                    @endif
                                </td>
                                <td style="padding:1rem;color:var(--text-primary);">
                                    {{ $log->description ?? '-' }}
                                </td>
                                <td style="padding:1rem;color:var(--text-primary);">
                                    <small>{{ $log->ip_address ?? '-' }}</small>
                                </td>
                                <td style="padding:1rem;">
                                    <a href="{{ route('user.activity-log.show', $log) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:6px;">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $logs->links() }}
    </div>
@else
    <div class="card card-custom">
        <div class="card-body text-center py-5">
            <div style="font-size:48px;opacity:0.3;margin-bottom:1rem;">
                <i class="bi bi-inbox"></i>
            </div>
            <p style="color:var(--text-primary);font-weight:500;">Tidak ada riwayat aktivitas</p>
            <small class="text-muted">Aktivitas Anda akan muncul di sini</small>
        </div>
    </div>
@endif

@push('scripts')
<script>
function initDeleteAllLogs() {
    const btn = document.getElementById('btn-hapus-semua');
    if (!btn) return;

    btn.addEventListener('click', function () {
        Swal.fire({
            title: 'Hapus Semua Log?',
            html: 'Seluruh riwayat aktivitas Anda akan <strong>dihapus permanen</strong> dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Ya, Hapus Semua',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-semua').submit();
            }
        });
    });
}

document.addEventListener('turbo:load', initDeleteAllLogs);
if (document.readyState !== 'loading') initDeleteAllLogs();
else document.addEventListener('DOMContentLoaded', initDeleteAllLogs);
</script>
@endpush

@endsection
