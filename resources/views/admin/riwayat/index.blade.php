@extends('layouts.admin')
@section('title', 'Riwayat Pemrosesan Surat')

@push('styles')
<style>
    /* ─── Color tokens ─────────────────────────────────────────────── */
    :root {
        --navy:        #1e3a5f;
        --navy-light:  #2d5282;
        --blue:        #3b82f6;
        --blue-soft:   rgba(59, 130, 246, 0.15);
        --green:       #22c55e;
        --green-soft:  rgba(34, 197, 94, 0.15);
        --green-dark:  #4ade80;
        --amber:       #f59e0b;
        --amber-soft:  rgba(245, 158, 11, 0.15);
        --amber-dark:  #fbbf24;
        --red:         #ef4444;
        --red-soft:    rgba(239, 68, 68, 0.15);
        --red-dark:    #f87171;
    }

    /* ─── Dark Mode Overrides ─────────────────────────────────────── */
    html.dark-mode .admin-chip {
        color: #93c5fd !important;
        background: rgba(30, 64, 175, 0.4) !important;
    }
    
    html.dark-mode .admin-chip:hover {
        background: rgba(30, 64, 175, 0.6) !important;
    }

    html.dark-mode .badge-selesai { color: #4ade80 !important; background: rgba(21, 128, 61, 0.25) !important; }
    html.dark-mode .badge-ditolak { color: #f87171 !important; background: rgba(185, 28, 28, 0.25) !important; }
    html.dark-mode .badge-proses  { color: #fbbf24 !important; background: rgba(180, 83, 9, 0.25) !important; }
    html.dark-mode .badge-revisi  { color: #facc15 !important; background: rgba(133, 77, 14, 0.25) !important; }
    html.dark-mode .badge-revisi-admin { color: #c084fc !important; background: rgba(88, 28, 135, 0.25) !important; }

    /* Force Table Transparency for Dark Mode */
    html.dark-mode .table-card .table,
    html.dark-mode .table-card .table > :not(caption) > * > * {
        background-color: transparent !important;
        color: #f3f4f6 !important;
        border-color: #374151 !important;
    }

    html.dark-mode .table-card tbody tr:hover td { 
        background: rgba(255, 255, 255, 0.05) !important; 
    }

    html.dark-mode .surat-meta { color: #9ca3af !important; }
    html.dark-mode .surat-title { color: #f3f4f6 !important; }
    html.dark-mode .user-nip { color: #9ca3af !important; }
    html.dark-mode .user-name { color: #f3f4f6 !important; }
    html.dark-mode .user-avatar { background: rgba(59, 130, 246, 0.2) !important; color: #93c5fd !important; }
    
    html.dark-mode .empty-state { color: #6b7280 !important; }

    html.dark-mode .btn-detail {
        border-color: #60a5fa !important;
        color: #60a5fa !important;
    }

    html.dark-mode .btn-detail:hover {
        background: #60a5fa !important;
        color: #ffffff !important;
    }

    html.dark-mode .btn-back { border-color: #4b5563 !important; color: #d1d5db !important; background: #1f2937 !important; }
    html.dark-mode .btn-back:hover { border-color: #60a5fa !important; color: #60a5fa !important; background: #374151 !important; }

    html.dark-mode .table-card {
        background-color: var(--bg-secondary) !important;
        border-color: #374151 !important;
    }

    /* ─── Page header ──────────────────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
    }
    .page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-primary, var(--navy));
        margin: 0 0 .2rem;
        line-height: 1.3;
        transition: color 0.3s;
    }
    .page-subtitle {
        font-size: .8rem;
        color: var(--text-secondary, var(--gray-500));
        margin: 0;
        transition: color 0.3s;
    }

    /* ─── Stat cards ───────────────────────────────────────────────── */
    .stat-card {
        background: var(--bg-secondary);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow .2s, transform .2s, background 0.3s, border-color 0.3s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
        transform: translateY(-1px);
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .stat-label {
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--text-secondary, var(--gray-500));
        margin: 0 0 .2rem;
        transition: color 0.3s;
    }
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1;
        margin: 0;
        color: var(--text-primary);
        transition: color 0.3s;
    }

    /* ─── Filter card ──────────────────────────────────────────────── */
    .filter-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        margin-bottom: 1.5rem;
        transition: background 0.3s, border-color 0.3s;
    }
    .filter-card .form-label {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .03em;
        text-transform: uppercase;
        color: var(--text-secondary, var(--gray-500));
        margin-bottom: .3rem;
        transition: color 0.3s;
    }
    .filter-card .form-select,
    .filter-card .form-control {
        font-size: .82rem;
        border-color: var(--border-color, var(--gray-200));
        border-radius: 8px;
        height: 34px;
        padding-top: 0;
        padding-bottom: 0;
        background: var(--bg-secondary, #fff);
        color: var(--text-primary);
        transition: all 0.3s;
    }
    .filter-card .form-select:focus,
    .filter-card .form-control:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }
    .btn-filter {
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 8px;
        height: 34px;
        padding: 0 1rem;
        font-size: .8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: background .15s;
    }
    .btn-filter:hover { background: var(--navy-light); color: #fff; }

    /* ─── Table card ───────────────────────────────────────────────── */
    .table-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        transition: background 0.3s, border-color 0.3s;
    }
    .table-card .table {
        margin: 0;
        font-size: .83rem;
        background-color: transparent;
        color: var(--text-primary);
    }
    .table-card thead th {
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-color);
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-secondary, var(--gray-500));
        padding: .75rem 1rem;
        white-space: nowrap;
        transition: all 0.3s;
    }
    .table-card tbody td {
        padding: .85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color, var(--gray-100));
        color: var(--text-secondary, var(--gray-700));
        transition: all 0.3s;
    }
    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover td { background: rgba(59, 130, 246, 0.05); }

    /* ─── Table: judul cell ────────────────────────────────────────── */
    .surat-title {
        font-weight: 600;
        color: var(--text-primary, var(--gray-900));
        max-width: 240px;
        line-height: 1.35;
        margin-bottom: .25rem;
        transition: color 0.3s;
    }
    .surat-meta {
        font-size: .7rem;
        color: var(--text-secondary, var(--gray-400));
        display: flex;
        align-items: center;
        gap: .6rem;
        flex-wrap: wrap;
        margin-top: .2rem;
        transition: color 0.3s;
    }
    .surat-meta i { font-size: .65rem; }

    /* ─── Avatar ───────────────────────────────────────────────────── */
    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--blue-soft);
        color: var(--blue);
        font-weight: 700;
        font-size: .75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .user-name  { font-size: .83rem; font-weight: 500; color: var(--text-primary, var(--gray-900)); transition: color 0.3s; }
    .user-nip   { font-size: .7rem;  color: var(--text-secondary, var(--gray-400)); word-break: break-all; transition: color 0.3s; }

    /* ─── Status badges ────────────────────────────────────────────── */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .28rem .65rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-selesai { background: var(--green-soft);  color: var(--green-dark); }
    .badge-ditolak { background: var(--red-soft);    color: var(--red-dark);   }
    .badge-proses  { background: var(--amber-soft);  color: var(--amber-dark); }
    .badge-revisi  { background: rgba(234, 179, 8, 0.15); color: #ca8a04; }
    .badge-revisi-admin { background: rgba(168, 85, 247, 0.15); color: #9333ea; }

    /* ─── Admin pengolah chips ─────────────────────────────────────── */
    .admin-chips {
        display: flex;
        flex-wrap: nowrap;
        gap: .3rem;
        overflow-x: auto;
        max-width: 280px;
        padding-bottom: 5px;
    }
    .admin-chips::-webkit-scrollbar { height: 5px; }
    .admin-chips::-webkit-scrollbar-track { background: transparent; }
    .admin-chips::-webkit-scrollbar-thumb { background: var(--border-color, var(--gray-200)); border-radius: 10px; transition: background 0.3s; }
    .admin-chips::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }
    .admin-chip {
        background: var(--blue-soft);
        color: #1d4ed8;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 600;
        padding: .22rem .6rem;
        cursor: help;
        white-space: nowrap;
        transition: background .15s;
    }
    .admin-chip:hover { background: #bfdbfe; }

    /* ─── Action button ────────────────────────────────────────────── */
    .btn-detail {
        font-size: .72rem;
        font-weight: 600;
        padding: .3rem .75rem;
        border-radius: 8px;
        border: 1.5px solid var(--blue);
        color: var(--blue);
        background: transparent;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }
    .btn-detail:hover { background: var(--blue); color: #fff; }

    /* ─── Empty state ──────────────────────────────────────────────── */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3.5rem 1rem;
        color: var(--text-secondary, var(--gray-400));
        transition: color 0.3s;
    }
    .empty-state i { font-size: 3rem; margin-bottom: .75rem; }
    .empty-state p { font-size: .85rem; margin: 0; }

    /* ─── Pagination wrapper ───────────────────────────────────────── */
    .pagination-wrap {
        padding: .85rem 1rem;
        border-top: 1px solid var(--border-color, var(--gray-100));
        background: var(--bg-secondary, transparent);
        transition: all 0.3s;
    }
    .pagination-wrap .pagination { margin: 0; }
    .pagination-wrap .page-link {
        font-size: .78rem;
        border-radius: 6px !important;
        margin: 0 2px;
        border-color: var(--border-color, var(--gray-200));
        color: var(--text-secondary, var(--gray-700));
        background: var(--bg-secondary, transparent);
        transition: all 0.3s;
    }
    .pagination-wrap .page-item.active .page-link {
        background: var(--navy);
        border-color: var(--navy);
    }

    /* ─── Back button ──────────────────────────────────────────────── */
    .btn-back {
        font-size: .78rem;
        font-weight: 600;
        padding: .4rem .9rem;
        border-radius: 8px;
        border: 1.5px solid var(--border-color, var(--gray-200));
        color: var(--text-secondary, var(--gray-700));
        background: var(--bg-secondary, #fff);
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: border-color .15s, background .15s, color 0.3s;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-back:hover { border-color: var(--navy); color: var(--navy); background: var(--bg-tertiary, var(--gray-50)); }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3">

    {{-- ── Page Header ──────────────────────────────────────────────── --}}
    <div class="page-header">
        <div>
            <h4 class="page-title">
                <i class="bi bi-clock-history me-2" style="color:var(--blue);"></i>Riwayat Pemrosesan Surat
            </h4>
            <p class="page-subtitle">
                Siapa saja yang telah memproses tiap surat pada
                <strong>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- ── Stat Cards ───────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        {{-- Total --}}
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--blue-soft);">
                    <i class="bi bi-envelope-paper" style="color:var(--blue);"></i>
                </div>
                <div>
                    <p class="stat-label">Total Surat</p>
                    <p class="stat-value">{{ $totalSurat }}</p>
                </div>
            </div>
        </div>
        {{-- Selesai --}}
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--green-soft);">
                    <i class="bi bi-check-circle" style="color:var(--green);"></i>
                </div>
                <div>
                    <p class="stat-label">Selesai</p>
                    <p class="stat-value" style="color:var(--green);">{{ $totalSelesai }}</p>
                </div>
            </div>
        </div>
        {{-- Dalam Proses --}}
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--amber-soft);">
                    <i class="bi bi-hourglass-split" style="color:var(--amber);"></i>
                </div>
                <div>
                    <p class="stat-label">Dalam Proses</p>
                    <p class="stat-value" style="color:var(--amber);">{{ $totalProses }}</p>
                </div>
            </div>
        </div>
        {{-- Ditolak --}}
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--red-soft);">
                    <i class="bi bi-x-circle" style="color:var(--red);"></i>
                </div>
                <div>
                    <p class="stat-label">Ditolak</p>
                    <p class="stat-value" style="color:var(--red);">{{ $totalDitolak }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filter ────────────────────────────────────────────────────── --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.riwayat.index') }}">
            <div class="row g-2 align-items-end">

                <div class="col-6 col-md-2">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="proses"  {{ request('status') === 'proses'   ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ request('status') === 'selesai'  ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                        <option value="revisi"  {{ request('status') === 'revisi'   ? 'selected' : '' }}>Revisi</option>
                        <option value="revisi_admin" {{ request('status') === 'revisi_admin' ? 'selected' : '' }}>Revisi Admin</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label">Jenis Surat</label>
                    <select name="jenis" class="form-select form-select-sm">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisSurat as $key => $label)
                            <option value="{{ $key }}" {{ request('jenis') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label">Cari Judul</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Ketik judul surat…" value="{{ request('search') }}">
                </div>

                <div class="col-12 col-md-1">
                    <button type="submit" class="btn-filter w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- ── Data Table ────────────────────────────────────────────────── --}}
    <div class="table-card">
        @if($riwayat->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Tidak ada data surat untuk periode yang dipilih.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-card mb-0">
                    <thead>
                        <tr>
                            <th>Judul Surat</th>
                            <th>Pengusul</th>
                            <th>Status</th>
                            <th>Admin Pengolah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $surat)
                        <tr>
                            {{-- Judul --}}
                            <td>
                                <div class="surat-title">
                                    {{ \Illuminate\Support\Str::limit($surat->judul, 50) }}
                                </div>
                                <div class="surat-meta">
                                    <span>
                                        <i class="bi bi-calendar3"></i>
                                        {{ $surat->created_at?->format('d M Y, H:i') ?? '—' }}
                                    </span>
                                    <span>
                                        <i class="bi bi-file-earmark"></i>
                                        {{ $surat->jenis_label }}
                                    </span>
                                </div>
                            </td>

                            {{-- Pengusul --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        @if($surat->user?->profile_photo)
                                            <img src="{{ Storage::url($surat->user->profile_photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            {{ strtoupper(substr($surat->user?->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $surat->user?->name ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($surat->status === 'selesai')
                                    <span class="badge-status badge-selesai">
                                        <i class="bi bi-check-circle-fill"></i> Selesai
                                    </span>
                                @elseif($surat->status === 'ditolak')
                                    <span class="badge-status badge-ditolak">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    </span>
                                @elseif($surat->status === 'revisi')
                                    <span class="badge-status badge-revisi">
                                        <i class="bi bi-arrow-counterclockwise"></i> Revisi
                                    </span>
                                @elseif($surat->status === 'revisi_admin')
                                    <span class="badge-status badge-revisi-admin">
                                        <i class="bi bi-arrow-repeat"></i> Revisi Admin
                                    </span>
                                @else
                                    <span class="badge-status badge-proses">
                                        <i class="bi bi-hourglass-split"></i> Proses
                                    </span>
                                @endif
                            </td>

                            {{-- Admin Pengolah --}}
                            <td>
                                <div class="admin-chips">
                                    @forelse($surat->tahapans as $tahapan)
                                        <span class="admin-chip"
                                              title="Tahap {{ $tahapan->tahap }}: {{ $tahapan->nama_tahap }}">
                                            {{ $tahapan->diprosesByUser?->getRoleLabel() ?? '—' }}
                                        </span>
                                    @empty
                                        <span style="font-size:.72rem; color:var(--gray-400);">
                                            <i class="bi bi-dash-circle me-1"></i>Belum diproses
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <a href="{{ route('admin.surat.show', $surat) }}" class="btn-detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($riwayat->hasPages())
            <div class="pagination-wrap">
                {{ $riwayat->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @endif
    </div>

</div>
@endsection