@extends('layouts.user')
@section('title', 'Dashboard')

@section('content')

{{-- GREETING --}}
<link rel="icon" href="{{ asset('images/metrologi.png') }}">

{{-- Custom Styles --}}
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        border-radius: 20px;
        padding: 36px;
        color: white;
        margin-bottom: 32px;
        box-shadow: 0 15px 40px rgba(15, 23, 42, 0.2);
        position: relative;
        overflow: hidden;
    }
    


    .dashboard-header > div {
        position: relative;
        z-index: 1;
    }
    
    .stat-card-modern {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s, border-color 0.4s;
        border: 1px solid rgba(255, 255, 255, 0.8);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-modern::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color), var(--accent-color-light));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }
    
    .stat-card-modern:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: var(--accent-color-light);
    }
    
    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        background: var(--icon-bg);
    }
    
    .stat-value-modern {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 6px;
        transition: color 0.3s ease;
    }
    
    .stat-card-modern:hover .stat-value-modern {
        color: var(--accent-color);
    }
    
    .stat-label-modern {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    /* Premium Stat Cards */
    .stat-card-new {
        position: relative;
        padding: 40px 24px;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
    }
    .stat-card-new:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2); }
    .stat-card-new.blue { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white; }
    .stat-card-new.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .stat-card-new.amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .stat-card-new.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    
    .stat-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    .stat-value-new { font-size: 36px; font-weight: 900; line-height: 1; letter-spacing: -1.5px; }
    .stat-label-new { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; opacity: 0.85; margin-bottom: 6px; }
    .stat-sub-new { font-size: 11px; font-weight: 600; opacity: 0.7; }

    .wave-bg {
        position: absolute;
        right: 0;
        bottom: -5px;
        opacity: 0.5;
        width: 100%;
        height: 80px;
        pointer-events: none;
        transition: transform 0.4s ease;
    }

    /* Fixed Grid Layout to prevent FOUC */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 640px) {
        .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .stat-card-new:hover .wave-bg {
        transform: scaleY(1.2) translateY(-5px);
    }
    
    .card-modern {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(241, 245, 249, 0.8);
        overflow: hidden;
        transition: background-color 0.4s, box-shadow 0.4s, transform 0.4s;
    }
    
    .card-modern:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }
    
    .card-header-modern {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        background: rgba(250, 251, 252, 0.8);
        backdrop-filter: blur(8px);
    }
    
    .card-body-modern {
        padding: 24px;
    }
    
    .surat-item, .notification-item {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        outline: none !important;
        cursor: pointer;
        position: relative;
        background: transparent;
    }
    
    .surat-item::before, .notification-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #3b82f6;
        transform: scaleY(0);
        transition: transform 0.3s ease;
        opacity: 0;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    
    .surat-item:hover, .notification-item:hover {
        background: #f8fafc;
    }
    
    .surat-item:last-child, .notification-item:last-child {
        border-bottom: none;
    }
    
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 2px white, 0 0 0 4px currentColor;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border: none;
        color: white;
        padding: 14px 28px;
        border-radius: 14px;
        font-weight: 600;
        transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .btn-primary-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: skewX(-20deg);
        transition: all 0.5s ease;
        z-index: -1;
    }
    
    .btn-primary-modern:hover {
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.4);
        color: white;
    }
    

    
    .notification-item.removing {
        opacity: 0;
        transform: translateX(20px);
    }
    
    .btn-delete-notif {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: none;
        background: transparent;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.2s;
        z-index: 10;
        cursor: pointer;
        line-height: 1;
    }
    
    .btn-delete-notif:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: scale(1.1) rotate(90deg);
    }
    


    .help-center-glass {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.6) 0%, rgba(255, 255, 255, 0.95) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 32px rgba(37, 99, 235, 0.08), inset 0 0 0 1px rgba(255,255,255,0.5);
        position: relative;
        overflow: hidden;
        z-index: 1;
        transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
    }

    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-15deg); }
        50% { transform: rotate(15deg); }
        75% { transform: rotate(-15deg); }
    }

    @keyframes pulseGradient {
        0% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); opacity: 0.5; }
    }

    .btn-glass-primary {
        background: rgba(37, 99, 235, 0.85);
        color: white !important;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-glass-primary:hover {
        background: rgba(30, 58, 95, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .template-card-link {
        transition: all 0.3s ease;
    }

    .template-card-link:hover {
        background: #eff6ff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    }



    /* Pulse Dot for Live Indicator */
    .pulse-dot {
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
        display: inline-block;
    }

    @keyframes bounceDown {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(3px); }
    }

    .sla-item:hover {
        background: #f8fafc;
        border-color: #f1f5f9;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    /* DOCUMENT PREVIEW CARD */
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .doc-preview-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #eef2f6;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .doc-preview-card:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: #dbeafe;
    }

    .doc-preview-top {
        height: 160px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .doc-preview-top img {
        width: 90%;
        height: 90%;
        object-fit: cover;
        object-position: top;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.5s ease;
    }

    .doc-preview-card:hover .doc-preview-top img {
        transform: scale(1.05);
    }

    .doc-info-section {
        padding: 15px;
        background: #0f172a; /* Dark theme as requested */
        color: white;
        flex-grow: 1;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .doc-icon-box {
        width: 38px;
        height: 38px;
        background: #ef4444; /* PDF Red or Word Blue */
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .doc-icon-box.docx {
        background: #2563eb;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .doc-name {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #f8fafc;
    }

    .doc-footer {
        padding: 10px 15px;
        background: #0f172a;
        border-top: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #94a3b8;
    }

    .doc-preview-card:hover .doc-info-section {
        background: #1e293b;
    }

    .doc-preview-card:hover .doc-footer {
        background: #1e293b;
    }

    /* MOBILE RESPONSIVE TWEAKS */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 24px 20px;
            text-align: center;
            border-radius: 24px;
            margin-bottom: 24px;
        }
        .dashboard-header .d-flex {
            flex-direction: column;
            align-items: center !important;
            justify-content: center;
        }
        .dashboard-header h2 {
            font-size: 20px;
            margin-top: 8px;
        }
        .dashboard-header p {
            font-size: 12px !important;
        }
        .btn-primary-modern {
            justify-content: center;
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            margin-top: 10px;
        }
        .stat-card-modern {
            padding: 14px;
            border-radius: 16px;
        }
        .stat-icon-wrapper {
            width: 36px;
            height: 36px;
            font-size: 16px;
            margin-bottom: 8px;
            border-radius: 10px;
        }
        .stat-value-modern {
            font-size: 20px;
        }
        .stat-label-modern {
            font-size: 10px;
        }
        .card-header-modern {
            padding: 14px 16px;
        }
        .card-body-modern {
            padding: 14px 16px;
        }
        .surat-item {
            padding: 14px 16px;
        }
        .notification-item {
            padding: 10px 14px;
        }
        .chart-container {
            height: 220px;
        }
        .d-flex.align-items-start.gap-3 {
            flex-direction: row !important; /* Keep horizontal on mobile for better layout */
            align-items: flex-start !important;
        }
        .surat-item .d-flex.align-items-start.gap-3 > .flex-shrink-0 {
            align-self: flex-start;
        }
        .surat-item .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-top: 6px;
        }
        
        #banner-revisi .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            text-align: left !important;
        }
        #banner-revisi .btn-danger {
            width: 100%;
            margin-top: 12px;
            text-align: center;
        }
    }

    .placeholder-white::placeholder {
        color: rgba(255,255,255,0.7) !important;
    }
</style>

{{-- HEADER --}}
<div class="dashboard-header animate-in">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            @if(Auth::user()->profile_photo)
                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile Photo" class="rounded-circle border border-white border-2 shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
            @else
                <div class="rounded-circle border border-white border-2 shadow-sm d-flex align-items-center justify-content-center bg-white text-primary fw-bold" style="width: 65px; height: 65px; font-size: 24px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                    <h2 class="fw-bold mb-0">
                        <i class="bi bi-hand-thumbs-up-fill me-2"></i>Halo, {{ Str::words(Auth::user()->name, 1, '') }}!
                    </h2>
                    <div id="live-indicator" class="badge rounded-pill bg-success d-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; opacity: 0; transition: opacity 0.5s;">
                        <span class="pulse-dot"></span> LIVE
                    </div>
                </div>
                <p class="mb-0" style="font-size:14px; opacity:0.85; font-weight: 500;">
                    {{ now()->translatedFormat('l, d F Y') }} · Selamat datang di Monitoring Surat BP SUML
                </p>
            </div>
        </div>
        <a href="{{ $isLibur ? 'javascript:void(0)' : route('user.surat.create') }}" 
           class="btn btn-primary-modern d-flex align-items-center gap-2 {{ $isLibur ? 'disabled' : '' }}"
           @if($isLibur) onclick="Swal.fire({icon: 'info', title: 'Layanan Tutup', text: 'Pengajuan surat baru hanya tersedia pada hari kerja. Senin–Kamis: 07.30–16.00 WIB, Jumat: 07.30–16.30 WIB. Sabtu & Minggu: Libur.', confirmButtonColor: '#1e3a5f'})" @endif>
            <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
        </a>
    </div>
</div>

@if($isLibur)
<div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius:16px; background:#fffbeb; color:#b45309; border-left: 5px solid #f59e0b !important;">
    <div class="d-flex align-items-center gap-3 p-2">
        <div style="font-size:32px;">⏰</div>
        <div>
            <h6 class="fw-bold mb-1">Layanan Sedang Tutup</h6>
            <p class="mb-0" style="font-size:13px; opacity:0.9;">
                Saat ini: <strong>{{ now()->translatedFormat('l, H:i') }} WIB</strong>. Pengajuan & draf surat baru hanya tersedia pada hari kerja:<br>
                <strong>Senin–Kamis:</strong> 07.30–16.00 WIB | <strong>Jumat:</strong> 07.30–16.30 WIB | <strong>Sabtu–Minggu:</strong> <span class="badge bg-danger p-1" style="font-size: 10px;">LIBUR</span>
                <br><small class="mt-1 d-block text-muted"><em>* Catatan: Upload file <strong>perbaikan/revisi</strong> surat tetap bisa dilakukan kapan saja.</em></small>
            </p>
        </div>
    </div>
</div>
@endif
</div>

{{-- BANNER BUTUH TINDAKAN (REVISI & DITOLAK) --}}
@php
    $suratRevisiCount = \App\Models\Surat::where('user_id', auth()->id())->whereIn('status', ['revisi', 'revisi_admin'])->count();
    $suratDitolakCount = \App\Models\Surat::where('user_id', auth()->id())->where('status', 'ditolak')->count();
@endphp

@if($suratActionUrgent > 0)
<div class="alert alert-danger border-0 shadow-sm mb-4 animate-in" id="banner-revisi" style="border-radius:20px; background:#fff; color:#b91c1c; border-left: 6px solid #ef4444 !important; box-shadow: 0 10px 25px rgba(239, 68, 68, 0.1) !important;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-2">
        <div class="d-flex align-items-center gap-3">
            <div class="flex-shrink-0" style="font-size:36px; background: #fee2e2; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 14px;">⚠️</div>
            <div>
                <h6 class="fw-bold mb-1" style="font-size: 16px;">Butuh Tindakan Segera!</h6>
                <p class="mb-0" style="font-size:13px; color: #475569;">
                    Anda memiliki <span class="fw-bold text-danger">{{ $suratActionUrgent }} surat</span> yang butuh perhatian (revisi atau ditolak). Mohon segera ditindaklanjuti.
                </p>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($suratRevisiCount > 0)
            <a href="{{ route('user.surat.index', ['status' => 'revisi']) }}" class="btn btn-warning px-3 py-2 fw-bold d-flex align-items-center gap-2" style="border-radius:12px; font-size:12px; color: #92400e; background: #fef3c7; border: none;">
                <i class="bi bi-pencil-square"></i> Lihat Revisi ({{ $suratRevisiCount }})
            </a>
            @endif
            
            @if($suratDitolakCount > 0)
            <a href="{{ route('user.surat.index', ['status' => 'ditolak']) }}" class="btn btn-danger px-3 py-2 fw-bold d-flex align-items-center gap-2" style="border-radius:12px; font-size:12px; background: #ef4444; border: none;">
                <i class="bi bi-x-circle"></i> Lihat Ditolak ({{ $suratDitolakCount }})
            </a>
            @endif
        </div>
    </div>
</div>
@endif

<div class="container-fluid px-0">

{{-- STAT CARDS --}}
<div class="stats-grid">
    {{-- TOTAL SURAT --}}
    <div class="stat-card-new blue animate-in" style="animation-delay: 0.1s;">
        <svg class="wave-bg" viewBox="0 0 400 150" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 120 C 50 120, 80 20, 130 80 C 180 140, 210 20, 260 80 C 310 140, 340 120, 400 120" fill="none" stroke="white" stroke-width="8" stroke-linecap="round" opacity="0.4" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-envelope-paper-fill"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Total Surat</div>
            <div class="stat-value-new" id="stat-totalSurat">{{ $totalSurat }}</div>
            <div class="stat-sub-new">Termasuk draf pengajuan</div>
        </div>
    </div>

    {{-- SELESAI --}}
    <div class="stat-card-new green animate-in" style="animation-delay: 0.15s;">
        <svg class="wave-bg" viewBox="0 0 400 150" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 140 L 100 100 L 200 120 L 300 40 L 400 60" fill="none" stroke="white" stroke-width="8" stroke-linecap="round" opacity="0.4" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Selesai</div>
            <div class="stat-value-new" id="stat-suratSelesai">{{ $suratSelesai }}</div>
            <div class="stat-sub-new">Terarsip & diverifikasi</div>
        </div>
    </div>

    {{-- PROSES --}}
    <div class="stat-card-new amber animate-in" style="animation-delay: 0.2s;">
        <svg class="wave-bg" viewBox="0 0 400 150" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="20" y="80" width="40" height="70" fill="white" opacity="0.3" rx="4"/>
            <rect x="80" y="40" width="40" height="110" fill="white" opacity="0.3" rx="4"/>
            <rect x="140" y="60" width="40" height="90" fill="white" opacity="0.3" rx="4"/>
            <rect x="200" y="20" width="40" height="130" fill="white" opacity="0.3" rx="4"/>
            <rect x="260" y="70" width="40" height="80" fill="white" opacity="0.3" rx="4"/>
            <rect x="320" y="50" width="40" height="100" fill="white" opacity="0.3" rx="4"/>
        </svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Sedang Proses</div>
            <div class="stat-value-new" id="stat-suratProses">{{ $suratProses }}</div>
            <div class="stat-sub-new">Menunggu aksi petugas</div>
        </div>
    </div>

    {{-- DITOLAK / REVISI --}}
    <div class="stat-card-new red animate-in" style="animation-delay: 0.25s;">
        <svg class="wave-bg" viewBox="0 0 400 150" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 80 L 50 140 L 100 20 L 150 100 L 200 40 L 250 130 L 300 20 L 350 110 L 400 60" fill="none" stroke="white" stroke-width="8" stroke-linecap="round" opacity="0.4" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Ditolak / Revisi</div>
            <div class="stat-value-new" id="stat-suratDitolak">{{ $suratDitolak }}</div>
            <div class="stat-sub-new">Perlu perbaikan segera</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- SURAT TERBARU --}}
    <div class="col-12 col-lg-7">
        <div class="card-modern h-100">
            <div class="card-header-modern d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-1" style="color:#1e293b;">
                        <i class="bi bi-envelope-paper me-2"></i>Surat Terbaru
                    </h6>
                    <small class="text-muted">Klik untuk lihat tracking lengkap</small>
                </div>
                <a href="{{ route('user.surat.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:12px; border-radius:8px;">
                    Lihat Semua →
                </a>
            </div>

            @if($suratTerbaru->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:48px; display:block; margin-bottom:12px;"></i>
                    <p class="mb-0">Belum ada surat yang diajukan.</p>
                    <a href="{{ route('user.surat.create') }}" class="text-primary text-decoration-none fw-semibold">
                        Ajukan sekarang →
                    </a>
                </div>
            @else
                <div id="surat-terbaru-list">
                @foreach($suratTerbaru as $surat)
                    <div class="surat-item">
                        <div class="d-flex align-items-start gap-3">
                            <div class="status-dot" style="background:{{ $surat->status === 'selesai' ? '#22c55e' : ($surat->status === 'ditolak' ? '#ef4444' : ($surat->status === 'revisi' ? '#f59e0b' : ($surat->status === 'revisi_admin' ? '#8b5cf6' : '#3b82f6'))) }}; margin-top:6px;"></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1" style="color:#1e293b; font-size:14px;">
                                    {{ $surat->judul }}
                                </div>
                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                    <span class="badge rounded-pill" style="font-size:11px; background:#ede9fe; color:#6d28d9; padding:4px 10px;">
                                        {{ $surat->jenis_label }}
                                    </span>
                                    <span class="badge rounded-pill badge-{{ $surat->sifat }}" style="font-size:11px; padding:4px 10px;">
                                        {{ ucfirst($surat->sifat) }}
                                    </span>
                                    <span class="text-muted" style="font-size:12px;">
                                        Tahap {{ $surat->tahap_sekarang }}/10
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if($surat->status === 'selesai')
                                    <span class="badge rounded-pill" style="background:#dcfce7; color:#15803d; font-size:11px; padding:6px 12px;">✓ Selesai</span>
                                @elseif($surat->status === 'ditolak')
                                    <span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:6px 12px;">✗ Ditolak</span>
                                @elseif($surat->status === 'revisi')
                                    <span class="badge rounded-pill" style="background:#fef3c7; color:#b45309; font-size:11px; padding:6px 12px;">📝 Revisi</span>
                                @elseif($surat->status === 'revisi_admin')
                                    <span class="badge rounded-pill" style="background:#f3e8ff; color:#6b21a8; font-size:11px; padding:6px 12px;">⚙️ Admin Revisi</span>
                                @elseif($surat->sla_status === 'terlambat')
                                    <span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:6px 12px;">⚠ SLA!</span>
                                @else
                                    <span class="badge rounded-pill" style="background:#dbeafe; color:#1d4ed8; font-size:11px; padding:6px 12px;">⏱ Proses</span>
                                @endif
                            </div>
                        </div>

                        {{-- Tracking Panel --}}
                        <div class="mt-3 p-3" style="background:#f8fafc; border-radius:10px;">

                            {{-- Progress bar --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="progress flex-grow-1" style="height:8px; border-radius:99px; background:#e2e8f0;">
                                    <div class="progress-bar" style="width:{{ $surat->proses_persen }}%; background:linear-gradient(90deg, #1e3a5f, #2563eb); border-radius:99px;"></div>
                                </div>
                                <span class="fw-bold" style="font-size:13px; color:#1e3a5f;">{{ $surat->proses_persen }}%</span>
                            </div>

                            {{-- Tracking steps compact --}}
                            <div class="d-flex gap-2 overflow-auto pb-2">
                                @foreach($surat->tahapans as $tahapan)
                                    @if($tahapan->status === 'selesai' || $tahapan->tahap === $surat->tahap_sekarang || $tahapan->status === 'proses')
                                    <div class="flex-shrink-0 text-center" style="min-width:80px;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                             style="width:32px; height:32px; background:{{ $tahapan->status === 'selesai' ? '#dcfce7' : ($tahapan->status === 'proses' ? '#dbeafe' : ($tahapan->status === 'ditolak' ? '#fee2e2' : '#f3f4f6')) }};">
                                            @if($tahapan->status === 'selesai')
                                                <i class="bi bi-check-lg" style="color:#15803d; font-size:16px;"></i>
                                            @elseif($tahapan->status === 'proses')
                                                <i class="bi bi-hourglass-split" style="color:#1d4ed8; font-size:14px;"></i>
                                            @elseif($tahapan->status === 'ditolak')
                                                <i class="bi bi-x-lg" style="color:#b91c1c; font-size:14px;"></i>
                                            @else
                                                <i class="bi bi-hourglass-split" style="color:#9ca3af; font-size:14px;"></i>
                                            @endif
                                        </div>
                                        <div style="font-size:10px; color:#64748b;">{{ $tahapan->nama_tahap }}</div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="text-end mt-2">
                                <a href="{{ route('user.surat.show', $surat) }}" class="btn btn-sm" style="font-size:12px; color:#1e3a5f; border:1px solid #e2e8f0; border-radius:8px;">
                                    Detail lengkap →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- PANEL KANAN --}}
    <div class="col-12 col-lg-5">
        {{-- LACAK CEPAT UUID --}}
        <div class="card-modern mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; border: none;">
            <div class="card-body-modern p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-search"></i> Lacak Cepat via UUID
                </h6>
                <div class="input-group mb-2" style="background: rgba(255,255,255,0.2); border-radius: 12px; padding: 4px; border: 1px solid rgba(255,255,255,0.3);">
                    <input type="text" id="quick-uuid-input" class="form-control border-0 bg-transparent text-white placeholder-white" placeholder="Masukkan UUID Surat..." style="box-shadow: none; font-size: 13px;">
                    <button class="btn btn-white text-primary fw-bold px-3" type="button" id="btn-quick-track" style="border-radius: 8px; font-size: 12px; background: white;">
                        Lacak
                    </button>
                </div>
                <p class="mb-0" style="font-size: 10px; opacity: 0.8;">Contoh: 123e4567-e89b-12d3-a456-426614174000</p>
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        <div class="card-modern mb-4">
            <div class="card-header-modern d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">
                    <i class="bi bi-bell-fill me-2"></i>Notifikasi Terbaru
                </h6>
                <div class="d-flex align-items-center gap-2" id="notif-badge-container">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button onclick="markAllAsRead()" class="btn btn-sm p-0 text-primary fw-semibold" style="font-size:11px; background:none; border:none;">
                            Tandai semua dibaca
                        </button>
                        <span class="badge rounded-pill bg-danger" id="notif-unread-count" style="font-size:11px; padding:6px 10px;">
                            {{ auth()->user()->unreadNotifications->count() }} baru
                        </span>
                    @endif
                </div>
            </div>
            <div id="dashboard-notif-list" style="max-height:400px; overflow-y:auto;">
                @php
                    // Pastikan kita ambil yang terbaru dan prioritaskan yang belum dibaca
                    $allNotifications = auth()->user()->notifications()->latest()->limit(20)->get();
                    $unread = $allNotifications->whereNull('read_at');
                    $read = $allNotifications->whereNotNull('read_at');
                    $displayNotifs = $unread->merge($read)->take(10);
                @endphp
                @forelse($displayNotifs as $notif)
                    <div class="notification-item-wrapper position-relative">
                        <a href="{{ route('notif.read', $notif->id) }}" class="notification-item d-block text-decoration-none">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:36px; height:36px; background:{{ match($notif->data['type'] ?? 'info') {
                                         'success' => '#dcfce7',
                                         'warning' => '#fef3c7',
                                         'danger' => '#fee2e2',
                                         default => '#dbeafe'
                                     } }};">
                                    @switch($notif->data['type'] ?? 'info')
                                        @case('success') <i class="bi bi-check-circle-fill" style="color:#15803d;"></i> @break
                                        @case('warning') <i class="bi bi-exclamation-triangle-fill" style="color:#b45309;"></i> @break
                                        @case('danger')  <i class="bi bi-x-circle-fill" style="color:#b91c1c;"></i> @break
                                        @default         <i class="bi bi-info-circle-fill" style="color:#1d4ed8;"></i>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold mb-1" style="color:#1e293b; font-size:13px;">
                                        {{ $notif->data['title'] ?? 'Notifikasi' }}
                                    </div>
                                    <div class="text-muted" style="font-size:12px;">
                                        {{ Str::limit($notif->data['message'] ?? '', 60) }}
                                    </div>
                                    <div style="font-size:11px; color:#94a3b8; margin-top:4px;">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                @if(!$notif->read_at)
                                    <div class="flex-shrink-0 pe-4">
                                        <span class="badge rounded-circle" style="width:8px; height:8px; background:#3b82f6; padding:0;"></span>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <button class="btn-delete-notif" data-id="{{ $notif->id }}" title="Hapus notifikasi">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash-fill" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                        <p class="mb-0" style="font-size:13px;">Belum ada notifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SLA AKTIF --}}
        @if($suratProses > 0)
        <div class="card-modern mb-4">
            <div class="card-header-modern">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">
                    <i class="bi bi-speedometer2 me-2"></i>Status SLA Surat Aktif
                </h6>
            </div>
            <div class="card-body-modern" id="sla-surat-list">
                @foreach($suratAktif as $surat)
                    <div class="mb-3 sla-item p-2 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold" style="font-size:13px; color:#1e293b;">
                                {{ Str::limit($surat->judul, 30) }}
                            </span>
                            @php
                                $isTerlambat = $surat->sla_status === 'terlambat';
                                $sisaJamNum  = $surat->deadline_sla ? now()->diffInHours($surat->deadline_sla, false) : 99;
                                if ($isTerlambat) {
                                    $slaBadgeBg   = '#fee2e2';
                                    $slaBadgeColor = '#b91c1c';
                                    $slaBarColor  = '#ef4444';
                                    $slaIcon      = '🔴';
                                } elseif ($sisaJamNum <= 12) {
                                    $slaBadgeBg   = '#fef3c7';
                                    $slaBadgeColor = '#92400e';
                                    $slaBarColor  = '#f59e0b';
                                    $slaIcon      = '🟡';
                                } else {
                                    $slaBadgeBg   = '#dcfce7';
                                    $slaBadgeColor = '#15803d';
                                    $slaBarColor  = '#22c55e';
                                    $slaIcon      = '🟢';
                                }
                                // Paksa 100% kalau sudah terlambat, supaya progress bar merah penuh
                                if ($isTerlambat) {
                                    $pct = 100;
                                } else {
                                    $pct = $surat->deadline_sla
                                        ? min(100, now()->diffInMinutes($surat->created_at) /
                                            max(1, $surat->deadline_sla->diffInMinutes($surat->created_at)) * 100)
                                        : 50;
                                    $pct = max(2, $pct); // minimal 2% supaya bar selalu kelihatan
                                }
                            @endphp
                            <span class="badge" style="font-size:11px; background:{{ $slaBadgeBg }}; color:{{ $slaBadgeColor }}; padding:4px 10px; border-radius:8px;">
                                {{ $slaIcon }}
                                @if($isTerlambat)
                                    ⚠ Terlambat
                                @elseif($sisaJamNum <= 12)
                                    ⚡ {{ $surat->sisa_jam }}
                                @else
                                    ✔ {{ $surat->sisa_jam }}
                                @endif
                            </span>
                        </div>
                        <div class="progress" style="height:8px; background:#e2e8f0; border-radius:99px;">
                            <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $slaBarColor }}; border-radius:99px; transition: width 0.6s ease;"></div>
                        </div>
                        <div style="font-size:11px; color:#94a3b8; margin-top:4px;">
                            Tahap {{ $surat->tahap_sekarang }}/10 · {{ $surat->nama_tahap }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TEMPLATE --}}
        <div class="card-modern">
            <div class="card-header-modern d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">
                    <i class="bi bi-file-earmark-richtext-fill me-2"></i>Template Surat
                </h6>
                <a href="{{ route('user.template.index') }}" class="btn btn-sm text-primary p-0 fw-semibold" style="font-size:12px;">Lihat Semua</a>
            </div>
            <div class="card-body-modern">
                <div class="template-grid">
                    @forelse($templates->take(4) as $tpl)
                        <div class="doc-preview-card" onclick="window.open('{{ $tpl['url'] }}', '_blank')">
                            <div class="doc-preview-top">
                                <img src="{{ asset('images/template_previewss.png') }}" alt="Preview">
                                <div style="position:absolute; top:10px; right:10px;">
                                    <span class="badge bg-white text-dark shadow-sm" style="font-size:9px; border-radius:6px; opacity:0.9;">
                                        {{ strtoupper($tpl['ext']) }}
                                    </span>
                                </div>
                            </div>
                            <div class="doc-info-section">
                                <div class="doc-icon-box {{ $tpl['ext'] }}">
                                    @if($tpl['ext'] == 'pdf')
                                        <i class="bi bi-file-pdf-fill text-white" style="font-size:20px;"></i>
                                    @else
                                        <i class="bi bi-file-earmark-word-fill text-white" style="font-size:20px;"></i>
                                    @endif
                                </div>
                                <div class="doc-name">{{ $tpl['nama'] }}</div>
                            </div>
                            <div class="doc-footer">
                                <span>{{ strtoupper($tpl['ext']) }} &bull; {{ $tpl['size'] }}</span>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-download"></i>
                                    <span>Unduh</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3 w-100">
                            <p class="mb-0" style="font-size:13px;">Belum ada template tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- PUSAT BANTUAN (GLASSMORPHISM) --}}
        <div class="help-center-glass mt-4 animate-in" style="animation-delay: 0.5s;">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%); border: 1px solid rgba(255,255,255,0.8); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);">
                    <i class="bi bi-headset" style="font-size: 24px; color: #2563eb;"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color:#1e293b;">Pusat Bantuan</h6>
                    <p class="mb-3 text-muted" style="font-size: 13px; line-height: 1.5;">
                        Butuh bantuan atau mengalami kendala? Tim dukungan kami siap membantu Anda.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://wa.me/{{ config('services.whatsapp.number') }}" target="_blank" class="btn-glass-primary">
                            <i class="bi bi-whatsapp"></i> Chat Admin
                        </a>
                        <a href="{{ route('user.faq.index') }}" class="btn-glass-primary" style="background: rgba(255, 255, 255, 0.7); color: #1e3a5f !important; border-color: rgba(30, 58, 95, 0.1); box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                            <i class="bi bi-book"></i> Panduan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
{{-- WEEKLY ACTIVITY CHART --}}
<div class="card-modern mb-4 animate-in" style="animation-delay: 0.4s;">
    <div class="card-header-modern d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0" style="color:#1e293b;">
            <i class="bi bi-activity me-2 text-primary"></i>Aktivitas Mingguan
        </h6>
        <div class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-1" style="font-size: 11px; font-weight: 700;">
            7 Hari Terakhir
        </div>
    </div>
    <div class="card-body-modern py-4">
        <div class="row align-items-center">
            {{-- Statistik Angka --}}
            <div class="col-12 col-md-4 text-center border-end mb-4 mb-md-0">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.1);">
                        <i class="bi bi-fire text-primary fs-4"></i>
                    </div>
                    <span class="fw-black text-primary mb-0" style="line-height: 1; font-size: 42px;">{{ $weeklyActivity['total'] }}</span>
                    <span class="text-muted text-uppercase fw-bold mt-1" style="font-size: 10px; letter-spacing: 1.5px;">Total Kontribusi</span>
                </div>
            </div>
            {{-- Grafik Garis --}}
            <div class="col-12 col-md-8">
                <div style="height: 140px; position: relative;">
                    <canvas id="weeklyActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- CHARTS SECTION --}}
<div class="row g-4 mt-2">
    <div class="col-12 col-lg-6 animate-in" style="animation-delay: 0.5s;">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h6 class="fw-bold mb-1" style="color:#1e293b;">
                    <i class="bi bi-pie-chart-fill me-2"></i>Distribusi Jenis Surat
                </h6>
                <small class="text-muted">Persentase setiap jenis surat yang diajukan</small>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="jenisChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 animate-in" style="animation-delay: 0.6s;">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h6 class="fw-bold mb-1" style="color:#1e293b;">
                    <i class="bi bi-graph-up-arrow me-2"></i>Tren Pengajuan Surat
                </h6>
                <small class="text-muted">6 bulan terakhir</small>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart: Distribusi Jenis Surat
    const jenisCtx = document.getElementById('jenisChart');
    if (jenisCtx) {
        const jenisData = @json($jenisSurat);
        const jenisLabels = @json(\App\Models\Surat::JENIS_LABEL);
        
        const labels = Object.keys(jenisData).map(key => jenisLabels[key] || key);
        const data = Object.values(jenisData);
        const colors = [
            'rgba(30, 58, 95, 0.8)', 
            'rgba(37, 99, 235, 0.8)', 
            'rgba(34, 197, 94, 0.8)', 
            'rgba(245, 158, 11, 0.8)', 
            'rgba(239, 68, 68, 0.8)', 
            'rgba(139, 92, 246, 0.8)', 
            'rgba(236, 72, 153, 0.8)'
        ];
        
        new Chart(jenisCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, data.length),
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 15,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12, weight: '600' }
                        }
                    }
                }
            }
        });
    }

    // Chart: Tren Bulanan
    const trenCtx = document.getElementById('trenChart');
    if (trenCtx) {
        const trenData = @json($trenBulanan);
        const labels = Object.keys(trenData);
        const data = Object.values(trenData);
        
        new Chart(trenCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Surat',
                    data: data,
                    backgroundColor: (context) => {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return null;
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, '#1e3a5f');
                        gradient.addColorStop(1, '#3b82f6');
                        return gradient;
                    },
                    borderRadius: 10,
                    barPercentage: 0.5,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8' },
                        grid: { color: 'rgba(241, 245, 249, 0.5)', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Handle Dashboard Notification Deletion
    const notifList = document.getElementById('dashboard-notif-list');
    if (notifList) {
        notifList.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-notif');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const id = btn.dataset.id;
            const wrapper = btn.closest('.notification-item-wrapper');
            const item = wrapper.querySelector('.notification-item');

            // Add removing class for animation
            item.classList.add('removing');

            // Call API
            fetch(`/notif/delete/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    setTimeout(() => {
                        wrapper.remove();
                        if (notifList.querySelectorAll('.notification-item-wrapper').length === 0) {
                            notifList.innerHTML = `
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-bell-slash-fill" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                                    <p class="mb-0" style="font-size:13px;">Belum ada notifikasi</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error deleting notification:', error);
                item.classList.remove('removing');
            });
        });
    }

    // Mark All as Read
    window.markAllAsRead = function() {
        fetch('{{ route("notif.readAll") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(err => console.error('Error marking all as read:', err));
    };

    // Auto-refresh sesuai jam operasional (07:30 & 16:00/16:30)
    function scheduleRefresh() {
        const now = new Date();
        const day = now.getDay(); // 0=Minggu, 1=Senin, ..., 5=Jumat, 6=Sabtu
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        
        const startMinutes = 7 * 60 + 30;    // 07:30
        const endMinutesMF = 16 * 60;       // 16:00 (Senin-Kamis)
        const endMinutesFri = 16 * 60 + 30;  // 16:30 (Jumat)
        
        let targetMinutes = 0;
        let targetDate = new Date(now);
        
        const isFriday = (day === 5);
        const endMinutesToday = isFriday ? endMinutesFri : endMinutesMF;
        
        if (currentMinutes < startMinutes) {
            // Belum buka, refresh pas jam 07:30
            targetMinutes = startMinutes;
        } else if (currentMinutes < endMinutesToday && day >= 1 && day <= 5) {
            // Sedang buka, refresh pas tutup (16:00 atau 16:30)
            targetMinutes = endMinutesToday;
        } else {
            // Sudah tutup atau weekend, refresh besok pagi jam 07:30
            targetMinutes = startMinutes;
            targetDate.setDate(targetDate.getDate() + 1);
        }
        
        targetDate.setHours(Math.floor(targetMinutes / 60), targetMinutes % 60, 0, 0);
        
        const timeToWait = targetDate.getTime() - now.getTime();
        
        if (timeToWait > 0) {
            // Refresh dengan buffer 2 detik agar status di server sudah pasti berubah
            setTimeout(() => {
                window.location.reload();
            }, timeToWait + 2000);
        }
    }
    
    // Chart: Aktivitas Mingguan
    const weeklyCtx = document.getElementById('weeklyActivityChart');
    if (weeklyCtx) {
        const weeklyData = @json($weeklyActivity);
        
        new Chart(weeklyCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: weeklyData.labels,
                datasets: [{
                    label: 'Aktivitas',
                    data: weeklyData.values,
                    fill: true,
                    backgroundColor: (context) => {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return null;
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(37, 99, 235, 0)');
                        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.1)');
                        return gradient;
                    },
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1e293b',
                        padding: 10,
                        titleFont: { size: 12 },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            color: '#94a3b8',
                            font: { size: 10 }
                        },
                        grid: { 
                            color: '#f1f5f9',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: { 
                            color: '#94a3b8',
                            font: { size: 10 }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // FAQ Welcome Popup - Gunakan ID user agar tidak bentrok antar akun di browser yang sama
    const faqPopupKey = 'hideFaqPopup_{{ Auth::id() }}';
    const hasSeenFaqPopup = localStorage.getItem(faqPopupKey);
    if (!hasSeenFaqPopup) {
        setTimeout(() => {
            Swal.fire({
                title: '<span style="font-weight: 800; color: #1e3a5f;">Butuh Bantuan?</span>',
                html: `
                    <div class="text-center p-2">
                        <div style="background: #eff6ff; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="bi bi-lightbulb-fill text-primary" style="font-size: 40px;"></i>
                        </div>
                        <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                            Selamat datang di Dashboard! Jika ini pertama kali Anda menggunakan aplikasi, kami menyarankan untuk membaca <strong>Panduan & FAQ</strong> terlebih dahulu.
                        </p>
                        <div class="form-check d-inline-block mt-3">
                            <input class="form-check-input" type="checkbox" id="dontShowAgain" style="cursor: pointer;">
                            <label class="form-check-label text-muted" for="dontShowAgain" style="font-size: 13px; cursor: pointer;">
                                Jangan tampilkan pesan ini lagi
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-book-half me-2"></i>Ke Halaman FAQ',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#1e3a5f',
                cancelButtonColor: '#94a3b8',
                padding: '2rem',
                background: '#fff',
                borderRadius: '24px',
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                },
                preConfirm: () => {
                    const checkbox = document.getElementById('dontShowAgain');
                    if (checkbox && checkbox.checked) {
                        localStorage.setItem(faqPopupKey, 'true');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('user.faq.index') }}";
                } else {
                    // Check checkbox state even if cancelled
                    const checkbox = document.getElementById('dontShowAgain');
                    if (checkbox && checkbox.checked) {
                        localStorage.setItem(faqPopupKey, 'true');
                    }
                }
            });
        }, 1000); // Muncul setelah 1 detik dashboard terbuka
    }

    scheduleRefresh();

    // ==========================================
    // REAL-TIME POLLING DASHBOARD
    // ==========================================
    let isFetching = false;
    const pollingInterval = 10000; // 10 detik

    function updateDashboard() {
        if (isFetching || document.hidden) return;
        isFetching = true;

        const liveIndicator = document.getElementById('live-indicator');
        if (liveIndicator) liveIndicator.style.opacity = '1';

        fetch('{{ route("dashboard.liveData") }}?bulan={{ $bulanSelected }}&tahun={{ $tahunSelected }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // 1. Update Stats
            for (const [key, value] of Object.entries(data.stats)) {
                const el = document.getElementById(`stat-${key}`);
                if (el) {
                    const currentVal = parseInt(el.innerText);
                    if (currentVal !== value) {
                        el.innerText = value;
                        el.classList.add('animate__animated', 'animate__headShake');
                        setTimeout(() => el.classList.remove('animate__animated', 'animate__headShake'), 1000);
                    }
                }
            }

            // 1.1 Update Banner Revisi/Action
            const bannerRevisi = document.getElementById('banner-revisi');
            const actionCount = data.stats.suratActionUrgent || 0;
            if (actionCount > 0) {
                if (bannerRevisi) {
                    bannerRevisi.style.display = 'block';
                    // Kita tidak update teksnya via JS secara mendalam agar tidak merusak tombol dinamis, 
                    // tapi setidaknya pastikan banner tetap muncul.
                }
            } else if (bannerRevisi) {
                bannerRevisi.style.display = 'none';
            }

            // 2. Update Unread Count Badge
            const badgeContainer = document.getElementById('notif-badge-container');
            if (badgeContainer) {
                if (data.unreadCount > 0) {
                    badgeContainer.innerHTML = `
                        <button onclick="markAllAsRead()" class="btn btn-sm p-0 text-primary fw-semibold" style="font-size:11px; background:none; border:none;">
                            Tandai semua dibaca
                        </button>
                        <span class="badge rounded-pill bg-danger" id="notif-unread-count" style="font-size:11px; padding:6px 10px;">
                            ${data.unreadCount} baru
                        </span>
                    `;
                } else {
                    badgeContainer.innerHTML = '';
                }
            }

            // 3. Update Notification List
            const notifList = document.getElementById('dashboard-notif-list');
            if (notifList && data.notifications.length > 0) {
                // Hanya update jika ada perubahan (sederhananya cek ID notif pertama)
                const firstNotifId = notifList.querySelector('.btn-delete-notif')?.dataset.id;
                if (firstNotifId !== data.notifications[0].id) {
                    let notifHtml = '';
                    data.notifications.forEach(n => {
                        const bgType = {
                            'success': '#dcfce7',
                            'warning': '#fef3c7',
                            'danger': '#fee2e2',
                            'info': '#dbeafe'
                        }[n.type] || '#dbeafe';

                        const iconClass = {
                            'success': 'bi-check-circle-fill',
                            'warning': 'bi-exclamation-triangle-fill',
                            'danger': 'bi-x-circle-fill',
                            'info': 'bi-info-circle-fill'
                        }[n.type] || 'bi-info-circle-fill';

                        const iconColor = {
                            'success': '#15803d',
                            'warning': '#b45309',
                            'danger': '#b91c1c',
                            'info': '#1d4ed8'
                        }[n.type] || '#1d4ed8';

                        notifHtml += `
                            <div class="notification-item-wrapper position-relative" data-id="${n.id}">
                                <a href="${n.url}" class="notification-item d-block text-decoration-none">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:36px; height:36px; background:${bgType};">
                                            <i class="bi ${iconClass}" style="color:${iconColor};"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold mb-1" style="color:#1e293b; font-size:13px;">${n.title}</div>
                                            <div class="text-muted" style="font-size:12px;">${n.message}</div>
                                            <div style="font-size:11px; color:#94a3b8; margin-top:4px;">${n.created_at_human}</div>
                                        </div>
                                        ${!n.read_at ? '<div class="flex-shrink-0 pe-4"><span class="badge rounded-circle" style="width:8px; height:8px; background:#3b82f6; padding:0;"></span></div>' : ''}
                                    </div>
                                </a>
                                <button class="btn-delete-notif" data-id="${n.id}" onclick="event.preventDefault(); event.stopPropagation(); deleteNotif('${n.id}')" title="Hapus notifikasi">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        `;
                    });
                    notifList.innerHTML = notifHtml;
                }
            }

            // 4. Update SLA Surat Aktif
            const slaList = document.getElementById('sla-surat-list');
            if (slaList) {
                if (data.suratAktif.length > 0) {
                    let slaHtml = '';
                    data.suratAktif.forEach(s => {
                        // Warna berdasarkan sisa jam (bukan persentase)
                        let barColor, badgeBg, badgeColor, slaIcon, badgeText;
                        if (s.sla_status === 'terlambat') {
                            barColor   = '#ef4444';
                            badgeBg    = '#fee2e2';
                            badgeColor = '#b91c1c';
                            slaIcon    = '🔴';
                            badgeText  = '⚠ Terlambat';
                        } else if (s.sisa_jam_angka !== undefined && s.sisa_jam_angka <= 12) {
                            barColor   = '#f59e0b';
                            badgeBg    = '#fef3c7';
                            badgeColor = '#92400e';
                            slaIcon    = '🟡';
                            badgeText  = `⚡ ${s.sisa_jam}`;
                        } else {
                            barColor   = '#22c55e';
                            badgeBg    = '#dcfce7';
                            badgeColor = '#15803d';
                            slaIcon    = '🟢';
                            badgeText  = `✔ ${s.sisa_jam}`;
                        }

                        slaHtml += `
                            <div class="mb-3 sla-item p-2 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold" style="font-size:13px; color:#1e293b;">${s.judul_short}</span>
                                    <span class="badge" style="font-size:11px; background:${badgeBg}; color:${badgeColor}; padding:4px 10px; border-radius:8px;">
                                        ${slaIcon} ${badgeText}
                                    </span>
                                </div>
                                <div class="progress" style="height:8px; background:#e2e8f0; border-radius:99px;">
                                    <div class="progress-bar" style="width:${s.sla_status === 'terlambat' ? 100 : Math.max(2, s.pct)}%; background:${barColor}; border-radius:99px; transition: width 0.6s ease;"></div>
                                </div>
                                <div style="font-size:11px; color:#94a3b8; margin-top:4px;">
                                    Tahap ${s.tahap}/10 · ${s.nama_tahap}
                                </div>
                            </div>
                        `;
                    });
                    slaList.innerHTML = slaHtml;
                } else {
                    slaList.parentElement.style.display = 'none';
                }
            }
            const terbaruList = document.getElementById('surat-terbaru-list');
            if (terbaruList && data.suratTerbaru.length > 0) {
                const firstSuratStatus = terbaruList.querySelector('.flex-shrink-0 .badge')?.innerText;
                const firstSuratTahap = terbaruList.querySelector('.text-muted')?.innerText;
                
                if (firstSuratStatus !== data.suratTerbaru[0].status || firstSuratTahap !== `Tahap ${data.suratTerbaru[0].tahap_sekarang}/10`) {
                    let terbaruHtml = '';
                    data.suratTerbaru.forEach(s => {
                        const statusColor = s.status === 'selesai' ? '#22c55e' : (s.status === 'ditolak' ? '#ef4444' : (s.status === 'revisi' ? '#f59e0b' : (s.status === 'revisi_admin' ? '#8b5cf6' : '#3b82f6')));
                        
                        let statusBadge = '';
                        if (s.status === 'selesai') statusBadge = '<span class="badge rounded-pill" style="background:#dcfce7; color:#15803d; font-size:11px; padding:6px 12px;">✓ Selesai</span>';
                        else if (s.status === 'ditolak') statusBadge = '<span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:6px 12px;">✗ Ditolak</span>';
                        else if (s.status === 'revisi') statusBadge = '<span class="badge rounded-pill" style="background:#fef3c7; color:#b45309; font-size:11px; padding:6px 12px;">📝 Revisi</span>';
                        else if (s.status === 'revisi_admin') statusBadge = '<span class="badge rounded-pill" style="background:#f3e8ff; color:#6b21a8; font-size:11px; padding:6px 12px;">⚙️ Admin Revisi</span>';
                        else if (s.sla_status === 'terlambat') statusBadge = '<span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:6px 12px;">⚠ SLA!</span>';
                        else statusBadge = '<span class="badge rounded-pill" style="background:#dbeafe; color:#1d4ed8; font-size:11px; padding:6px 12px;">⏱ Proses</span>';

                        let stepsHtml = '';
                        s.tahapans.forEach(t => {
                            if (t.status === 'selesai' || t.tahap === s.tahap_sekarang || t.status === 'proses') {
                                const stepBg = t.status === 'selesai' ? '#dcfce7' : (t.status === 'proses' ? '#dbeafe' : (t.status === 'ditolak' ? '#fee2e2' : '#f3f4f6'));
                                const stepIcon = t.status === 'selesai' ? '<i class="bi bi-check-lg" style="color:#15803d; font-size:16px;"></i>' 
                                               : (t.status === 'proses' ? '<i class="bi bi-hourglass-split" style="color:#1d4ed8; font-size:14px;"></i>' 
                                               : (t.status === 'ditolak' ? '<i class="bi bi-x-lg" style="color:#b91c1c; font-size:14px;"></i>' 
                                               : '<i class="bi bi-hourglass-split" style="color:#9ca3af; font-size:14px;"></i>'));
                                
                                stepsHtml += `
                                    <div class="flex-shrink-0 text-center" style="min-width:80px;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                             style="width:32px; height:32px; background:${stepBg};">
                                            ${stepIcon}
                                        </div>
                                        <div style="font-size:10px; color:#64748b;">${t.nama_tahap}</div>
                                    </div>
                                `;
                            }
                        });

                        terbaruHtml += `
                            <div class="surat-item">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="status-dot" style="background:${statusColor}; margin-top:6px;"></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-1" style="color:#1e293b; font-size:14px;">${s.judul}</div>
                                        <div class="d-flex gap-2 flex-wrap align-items-center">
                                            <span class="badge rounded-pill" style="font-size:11px; background:#ede9fe; color:#6d28d9; padding:4px 10px;">${s.jenis_label}</span>
                                            <span class="badge rounded-pill badge-${s.sifat}" style="font-size:11px; padding:4px 10px;">${s.sifat.charAt(0).toUpperCase() + s.sifat.slice(1)}</span>
                                            <span class="text-muted" style="font-size:12px;">Tahap ${s.tahap_sekarang}/10</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">${statusBadge}</div>
                                </div>
                                <div class="mt-3 p-3" style="background:#f8fafc; border-radius:10px;">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="progress flex-grow-1" style="height:8px; border-radius:99px; background:#e2e8f0;">
                                            <div class="progress-bar" style="width:${s.proses_persen}%; background:linear-gradient(90deg, #1e3a5f, #2563eb); border-radius:99px;"></div>
                                        </div>
                                        <span class="fw-bold" style="font-size:13px; color:#1e3a5f;">${s.proses_persen}%</span>
                                    </div>
                                    <div class="d-flex gap-2 overflow-auto pb-2">${stepsHtml}</div>
                                    <div class="text-end mt-2">
                                        <a href="${s.show_url}" class="btn btn-sm" style="font-size:12px; color:#1e3a5f; border:1px solid #e2e8f0; border-radius:8px;">Detail lengkap →</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    terbaruList.innerHTML = terbaruHtml;
                }
            }
        })
        .catch(err => console.error('Polling Error:', err))
        .finally(() => {
            isFetching = false;
        });
    }

    setInterval(updateDashboard, pollingInterval);
    // Jalankan sekali saat load
    setTimeout(updateDashboard, 2000);

    // Quick Track Logic
    const btnTrack = document.getElementById('btn-quick-track');
    const inputTrack = document.getElementById('quick-uuid-input');
    if (btnTrack && inputTrack) {
        const handleTrack = () => {
            const uuid = inputTrack.value.trim();
            if (!uuid) {
                Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Silakan masukkan UUID surat.' });
                return;
            }
            // Format check (simple regex for UUID)
            const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
            if (!uuidRegex.test(uuid)) {
                Swal.fire({ icon: 'error', title: 'Format Salah', text: 'UUID yang Anda masukkan tidak valid.' });
                return;
            }
            window.location.href = `/surat/${uuid}`;
        };

        btnTrack.addEventListener('click', handleTrack);
        inputTrack.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleTrack();
        });
    }
});
</script>


@endsection
