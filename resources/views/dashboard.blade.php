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
    
    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(255,255,255,0) 50%);
        animation: rotateBg 15s linear infinite;
        z-index: 0;
    }
    
    @keyframes rotateBg {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: var(--accent-color-light);
    }
    
    .stat-card-modern:hover::before {
        transform: scaleX(1);
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stat-card-modern:hover .stat-icon-wrapper {
        transform: scale(1.1) rotate(10deg);
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-color-light) 100%);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .stat-card-modern:hover .stat-icon-wrapper i {
        color: white !important;
        transition: color 0.3s ease;
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
    
    .card-modern {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(241, 245, 249, 0.8);
        overflow: hidden;
        transition: all 0.4s ease;
    }
    
    .card-modern:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        padding-left: 32px;
        padding-right: 16px;
    }
    
    .surat-item:hover::before, .notification-item:hover::before {
        transform: scaleY(1);
        opacity: 1;
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.4);
        color: white;
    }
    
    .btn-primary-modern:hover::after {
        left: 150%;
    }
    
    .chart-container {
        position: relative;
        height: 280px;
        transition: transform 0.4s ease;
    }
    
    .card-modern:hover .chart-container {
        transform: scale(1.02);
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
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .animate-in {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .help-center-glass:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(37, 99, 235, 0.15), inset 0 0 0 1px rgba(255,255,255,0.8);
    }

    .help-center-glass:hover .bi-headset {
        animation: wiggle 1s ease-in-out infinite;
        display: inline-block;
    }

    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-15deg); }
        50% { transform: rotate(15deg); }
        75% { transform: rotate(-15deg); }
    }

    .help-center-glass::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, rgba(255,255,255,0) 60%);
        z-index: -1;
        animation: pulseGradient 8s linear infinite;
    }

    .help-center-glass::after {
        content: '';
        position: absolute;
        bottom: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: -1;
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        transform: translateX(6px);
        background: #eff6ff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    }

    .template-card-link:hover i {
        animation: bounceDown 1s ease infinite;
    }

    /* Pulse Dot for Live Indicator */
    .pulse-dot {
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
        display: inline-block;
        animation: pulseLive 1.5s infinite;
    }

    @keyframes pulseLive {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
        70% { transform: scale(1.2); box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }

    @keyframes bounceDown {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(3px); }
    }

    .sla-item:hover {
        background: #f8fafc;
        border-color: #f1f5f9;
        transform: translateX(5px);
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
        transform: translateY(-10px);
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
            padding: 20px;
            text-align: center;
        }
        .dashboard-header .d-flex {
            flex-direction: column;
            align-items: stretch !important;
            justify-content: center;
        }
        .dashboard-header h2 {
            font-size: 22px;
        }
        .btn-primary-modern {
            justify-content: center;
            width: 100%;
        }
        .stat-card-modern {
            padding: 16px;
        }
        .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 18px;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .stat-value-modern {
            font-size: 22px;
        }
        .stat-label-modern {
            font-size: 11px;
        }
        .card-header-modern {
            padding: 16px;
        }
        .card-body-modern {
            padding: 16px;
        }
        .surat-item {
            padding: 16px;
        }
        .notification-item {
            padding: 12px 16px;
        }
        .chart-container {
            height: 240px;
        }
        .d-flex.align-items-start.gap-3 {
            flex-direction: column;
            align-items: stretch !important;
        }
        .surat-item .d-flex.align-items-start.gap-3 > .flex-shrink-0 {
            align-self: flex-start;
            margin-top: 5px;
        }
        .surat-item .status-dot {
            display: none;
        }
    }
</style>

{{-- HEADER --}}
<div class="dashboard-header animate-in">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            @if(Auth::user()->profile_photo)
                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile Photo" class="rounded-circle border border-white border-2" style="width: 60px; height: 60px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            @endif
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h2 class="fw-bold mb-0">
                        <i class="bi bi-hand-thumbs-up-fill me-2"></i>Halo, {{ Str::words(Auth::user()->name, 1, '') }}!
                    </h2>
                    <div id="live-indicator" class="badge rounded-pill bg-success d-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; opacity: 0; transition: opacity 0.5s;">
                        <span class="pulse-dot"></span> LIVE
                    </div>
                </div>
                <p class="mb-0" style="font-size:14px; opacity:0.9;">
                    {{ now()->translatedFormat('l, d F Y') }} · Selamat datang di Monitoring Surat BP SUML
                </p>
            </div>
        </div>
        <a href="{{ $isLibur ? 'javascript:void(0)' : route('user.surat.create') }}" 
           class="btn btn-primary-modern d-flex align-items-center gap-2 {{ $isLibur ? 'disabled' : '' }}"
           @if($isLibur) onclick="Swal.fire({icon: 'info', title: 'Layanan Tutup', text: 'Pengajuan surat baru hanya tersedia pada hari kerja. Senin–Kamis pukul 07.30–16.00 WIB, Jumat pukul 07.30–16.30 WIB.', confirmButtonColor: '#1e3a5f'})" @endif>
            <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
        </a>
    </div>
</div>

@if($isLibur)
<div class="alert alert-warning border-0 shadow-sm animate-in mb-4" style="border-radius:16px; background:#fffbeb; color:#b45309; animation-delay: 0.05s; border-left: 5px solid #f59e0b !important;">
    <div class="d-flex align-items-center gap-3 p-2">
        <div style="font-size:32px;">⏰</div>
        <div>
            <h6 class="fw-bold mb-1">Layanan Sedang Tutup</h6>
            <p class="mb-0" style="font-size:13px; opacity:0.9;">
                Saat ini pukul <strong>{{ now()->format('H:i') }} WIB</strong>. Pengajuan surat baru hanya tersedia pada hari kerja:<br>
                <strong>Senin–Kamis</strong> pukul <strong>07.30–16.00 WIB</strong> &nbsp;|&nbsp; <strong>Jumat</strong> pukul <strong>07.30–16.30 WIB</strong>.
            </p>
        </div>
    </div>
</div>
@endif

{{-- STAT CARDS --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 mb-4">
    <div class="col animate-in" style="animation-delay: 0.1s;">
        <div class="stat-card-modern" style="--accent-color: #1e3a5f; --accent-color-light: #2563eb; --icon-bg: #eff6ff;">
            <div class="stat-icon-wrapper">
                <i class="bi bi-envelope-paper-fill" style="color: #1e3a5f; font-size: 20px;"></i>
            </div>
            <div class="stat-value-modern" id="stat-totalSurat" style="font-size: 24px;">{{ $totalSurat }}</div>
            <div class="stat-label-modern" style="font-size: 11px;">Total Surat</div>
        </div>
    </div>
    <div class="col animate-in" style="animation-delay: 0.15s;">
        <div class="stat-card-modern" style="--accent-color: #15803d; --accent-color-light: #22c55e; --icon-bg: #f0fdf4;">
            <div class="stat-icon-wrapper">
                <i class="bi bi-check-circle-fill" style="color: #15803d; font-size: 20px;"></i>
            </div>
            <div class="stat-value-modern" id="stat-suratSelesai" style="font-size: 24px;">{{ $suratSelesai }}</div>
            <div class="stat-label-modern" style="font-size: 11px;">Selesai</div>
        </div>
    </div>
    <div class="col animate-in" style="animation-delay: 0.2s;">
        <div class="stat-card-modern" style="--accent-color: #2563eb; --accent-color-light: #60a5fa; --icon-bg: #eff6ff;">
            <div class="stat-icon-wrapper">
                <i class="bi bi-hourglass-split" style="color: #2563eb; font-size: 20px;"></i>
            </div>
            <div class="stat-value-modern" id="stat-suratProses" style="font-size: 24px;">{{ $suratProses }}</div>
            <div class="stat-label-modern" style="font-size: 11px;">Proses</div>
        </div>
    </div>
    <div class="col animate-in" style="animation-delay: 0.25s;">
        <div class="stat-card-modern" style="--accent-color: #b45309; --accent-color-light: #f59e0b; --icon-bg: #fffbeb;">
            <div class="stat-icon-wrapper">
                <i class="bi bi-pencil-square" style="color: #b45309; font-size: 20px;"></i>
            </div>
            <div class="stat-value-modern" id="stat-suratRevisi" style="font-size: 24px;">{{ $suratRevisi }}</div>
            <div class="stat-label-modern" style="font-size: 11px;">Revisi</div>
        </div>
    </div>
    <div class="col animate-in" style="animation-delay: 0.3s;">
        <div class="stat-card-modern" style="--accent-color: #b91c1c; --accent-color-light: #ef4444; --icon-bg: #fef2f2;">
            <div class="stat-icon-wrapper">
                <i class="bi bi-x-octagon-fill" style="color: #b91c1c; font-size: 20px;"></i>
            </div>
            <div class="stat-value-modern" id="stat-suratDitolak" style="font-size: 24px;">{{ $suratDitolak }}</div>
            <div class="stat-label-modern" style="font-size: 11px;">Ditolak</div>
        </div>
    </div>
    <div class="col animate-in" style="animation-delay: 0.35s;">
        <div class="stat-card-modern" style="--accent-color: #64748b; --accent-color-light: #94a3b8; --icon-bg: #f8fafc;">
            <div class="stat-icon-wrapper">
                <i class="bi bi-file-earmark-text-fill" style="color: #64748b; font-size: 20px;"></i>
            </div>
            <div class="stat-value-modern" id="stat-suratDraft" style="font-size: 24px;">{{ $suratDraft }}</div>
            <div class="stat-label-modern" style="font-size: 11px;">Draf</div>
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
                            <div class="status-dot" style="background:{{ $surat->status === 'selesai' ? '#22c55e' : ($surat->status === 'ditolak' ? '#ef4444' : (in_array($surat->status, ['revisi', 'revisi_admin']) ? '#f59e0b' : '#3b82f6')) }}; margin-top:6px;"></div>
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
                                @elseif(in_array($surat->status, ['revisi', 'revisi_admin']))
                                    <span class="badge rounded-pill" style="background:#fef3c7; color:#b45309; font-size:11px; padding:6px 12px;">📝 Revisi</span>
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
                            @if($surat->sla_status === 'terlambat')
                                <span class="badge" style="font-size:11px; background:#fee2e2; color:#b91c1c; padding:4px 10px;">⚠ Terlambat</span>
                            @else
                                <span style="font-size:12px; color:#64748b;">{{ $surat->sisa_jam }}</span>
                            @endif
                        </div>
                        <div class="progress" style="height:8px; background:#e2e8f0; border-radius:99px;">
                            @php
                                $pct = $surat->deadline_sla
                                    ? min(100, now()->diffInMinutes($surat->created_at) /
                                        max(1, $surat->deadline_sla->diffInMinutes($surat->created_at)) * 100)
                                    : 50;
                                $color = $pct >= 90 ? '#ef4444' : ($pct >= 60 ? '#f59e0b' : '#22c55e');
                            @endphp
                            <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $color }}; border-radius:99px;"></div>
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
            type: 'polarArea',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, data.length),
                    borderWidth: 1,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    r: {
                        ticks: { display: false }
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
                    backgroundColor: 'rgba(30, 58, 95, 0.8)',
                    borderColor: '#1e3a5f',
                    borderWidth: 2,
                    borderRadius: 8,
                    barPercentage: 0.6
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
                        ticks: { stepSize: 1 },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
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

    // Auto-refresh at 08:00 and 16:00
    function scheduleRefresh() {
        const now = new Date();
        const currentHour = now.getHours();
        
        let targetHour = 0;
        let targetDate = new Date(now);
        
        if (currentHour < 8) {
            targetHour = 8;
        } else if (currentHour >= 8 && currentHour < 16) {
            targetHour = 16;
        } else {
            // Next day at 8 AM
            targetHour = 8;
            targetDate.setDate(targetDate.getDate() + 1);
        }
        
        // Set exact target time (e.g. 08:00:00 or 16:00:00)
        targetDate.setHours(targetHour, 0, 0, 0);
        
        // Calculate difference in milliseconds
        const timeToWait = targetDate.getTime() - now.getTime();
        
        if (timeToWait > 0) {
            // Refresh with 2 seconds buffer so server definitely sees the new hour
            setTimeout(() => {
                window.location.reload();
            }, timeToWait + 2000);
        }
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

        fetch('{{ route("dashboard.liveData") }}', {
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
                            <div class="notification-item-wrapper position-relative">
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
                                <button class="btn-delete-notif" data-id="${n.id}" title="Hapus notifikasi">
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
                        slaHtml += `
                            <div class="mb-3 sla-item p-2 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold" style="font-size:13px; color:#1e293b;">${s.judul_short}</span>
                                    ${s.sla_status === 'terlambat' 
                                        ? '<span class="badge" style="font-size:11px; background:#fee2e2; color:#b91c1c; padding:4px 10px;">⚠ Terlambat</span>'
                                        : `<span style="font-size:12px; color:#64748b;">${s.sisa_jam}</span>`}
                                </div>
                                <div class="progress" style="height:8px; background:#e2e8f0; border-radius:99px;">
                                    <div class="progress-bar" style="width:${s.pct}%; background:${s.color}; border-radius:99px;"></div>
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
            // 5. Update Surat Terbaru (Tracking)
            const terbaruList = document.getElementById('surat-terbaru-list');
            if (terbaruList && data.suratTerbaru.length > 0) {
                // Sederhananya kita update jika ada perubahan status atau tahap pada surat pertama
                const firstSuratStatus = terbaruList.querySelector('.flex-shrink-0 .badge')?.innerText;
                const firstSuratTahap = terbaruList.querySelector('.text-muted')?.innerText;
                
                if (firstSuratStatus !== data.suratTerbaru[0].status || firstSuratTahap !== `Tahap ${data.suratTerbaru[0].tahap_sekarang}/10`) {
                    let terbaruHtml = '';
                    data.suratTerbaru.forEach(s => {
                        const statusColor = s.status === 'selesai' ? '#22c55e' : (s.status === 'ditolak' ? '#ef4444' : (['revisi', 'revisi_admin'].includes(s.status) ? '#f59e0b' : '#3b82f6'));
                        
                        let statusBadge = '';
                        if (s.status === 'selesai') statusBadge = '<span class="badge rounded-pill" style="background:#dcfce7; color:#15803d; font-size:11px; padding:6px 12px;">✓ Selesai</span>';
                        else if (s.status === 'ditolak') statusBadge = '<span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-size:11px; padding:6px 12px;">✗ Ditolak</span>';
                        else if (['revisi', 'revisi_admin'].includes(s.status)) statusBadge = '<span class="badge rounded-pill" style="background:#fef3c7; color:#b45309; font-size:11px; padding:6px 12px;">📝 Revisi</span>';
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
});
</script>


@endsection
