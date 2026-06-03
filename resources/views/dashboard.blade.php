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
        padding: 30px 24px;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .stat-card-new:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Elegant Clean Gradients */
    .stat-card-new.blue { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; }
    .stat-card-new.green { background: linear-gradient(135deg, #065f46 0%, #10b981 100%); color: white; }
    .stat-card-new.amber { background: linear-gradient(135deg, #9a3412 0%, #f59e0b 100%); color: white; }
    .stat-card-new.red { background: linear-gradient(135deg, #991b1b 0%, #ef4444 100%); color: white; }

    /* Modern Radial Glow Accents (Not Gaudy) */
    .stat-card-glow {
        position: absolute;
        top: -40%;
        right: -25%;
        width: 130px;
        height: 130px;
        background: rgba(255, 255, 255, 0.15);
        filter: blur(35px);
        border-radius: 50%;
        pointer-events: none;
        transition: all 0.4s ease;
    }
    .stat-card-new:hover .stat-card-glow {
        transform: scale(1.3);
        background: rgba(255, 255, 255, 0.25);
    }
    
    .stat-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: transform 0.4s ease;
    }
    .stat-card-new:hover .stat-icon-box {
        transform: rotate(6deg) scale(1.05);
    }
    
    .stat-value-new { 
        font-size: 32px; 
        font-weight: 850; 
        line-height: 1.1; 
        letter-spacing: -0.5px; 
        margin-bottom: 2px;
    }
    .stat-label-new { 
        font-size: 11px; 
        font-weight: 750; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        opacity: 0.9; 
        margin-bottom: 4px; 
    }
    .stat-sub-new { 
        font-size: 11px; 
        font-weight: 500; 
        opacity: 0.75; 
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
        .dashboard-header-glass {
            padding: 24px 20px;
            text-align: center;
            border-radius: 20px;
            margin-bottom: 24px;
        }
        .dashboard-header-glass > .d-flex {
            flex-direction: column;
            align-items: center !important;
            justify-content: center;
            text-align: center;
        }
        .dashboard-header-glass .d-flex.align-items-center {
            flex-direction: column;
            align-items: center !important;
        }
        .dashboard-header-glass h2 {
            font-size: 20px;
            margin-top: 8px;
            text-align: center;
        }
        .dashboard-header-glass p {
            font-size: 12px !important;
            text-align: center;
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
        
        #banner-revisi .btn-danger {
            width: 100%;
            margin-top: 12px;
            text-align: center;
        }
        
        .border-end {
            border-right: none !important;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 20px !important;
        }
    }
    
    @media (max-width: 576px) {
        .template-grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
        }
        .doc-preview-top {
            height: 110px;
        }
        .help-center-glass {
            padding: 16px;
        }
        .help-center-glass > .d-flex {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .help-center-glass .btn-glass-primary {
            width: 100%;
            justify-content: center;
        }
        #banner-revisi .d-flex.gap-2 {
            width: 100%;
            flex-direction: column;
        }
        #banner-revisi .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .stat-card-new {
            padding: 20px 16px;
            gap: 12px;
        }
        .stat-icon-box {
            width: 42px;
            height: 42px;
            font-size: 18px;
            border-radius: 10px;
        }
        .stat-value-new {
            font-size: 24px;
        }
        .stat-label-new {
            font-size: 9px;
            letter-spacing: 0.5px;
        }
        .stat-sub-new {
            font-size: 10px;
        }
    }

    .placeholder-white::placeholder {
        color: rgba(255,255,255,0.7) !important;
    }

    /* Header Glassmorphism */
    .dashboard-header-glass {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 24px;
        padding: 32px;
        color: var(--text-primary);
        margin-bottom: 32px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .dashboard-header-glass:hover {
        box-shadow: 0 15px 35px -8px rgba(0, 0, 0, 0.06);
        border-color: rgba(255, 255, 255, 0.85);
    }
    
    .dashboard-header-bg-glow {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 10% 20%, rgba(6, 182, 212, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(37, 99, 235, 0.12) 0%, transparent 45%);
        pointer-events: none;
        z-index: 0;
        animation: headerGlowShift 8s infinite alternate ease-in-out;
    }
    
    @keyframes headerGlowShift {
        0% { transform: scale(1) translate(0, 0); }
        100% { transform: scale(1.08) translate(1.5%, 1%); }
    }
    
    /* Pulses for active SLA count downs */
    @keyframes pulseGlowAmber {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.45); }
        50% { box-shadow: 0 0 8px 3px rgba(245, 158, 11, 0.7); }
    }
    @keyframes pulseGlowRed {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.45); }
        50% { box-shadow: 0 0 10px 4px rgba(239, 68, 68, 0.75); }
    }
    .glow-pulse-amber {
        animation: pulseGlowAmber 1.8s infinite;
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.45);
    }
    .glow-pulse-red {
        animation: pulseGlowRed 1.4s infinite;
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.45);
    }
    .bg-cyan-soft {
        background-color: rgba(6, 182, 212, 0.12) !important;
    }
    .text-gradient-primary {
        background: linear-gradient(135deg, #1e293b 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .transition-all-200 {
        transition: all 0.2s ease;
    }
</style>

{{-- HEADER --}}
<div class="dashboard-header-glass animate-in">
    <div class="dashboard-header-bg-glow"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index: 1;">
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
                    <h2 class="fw-bold mb-0 text-gradient-primary" id="welcome-greeting-text" style="font-size: 24px; font-weight: 800;">
                        <i class="bi bi-brightness-alt-high-fill text-primary me-2"></i>Halo, {{ Str::words(Auth::user()->name, 2, '') }}!
                    </h2>
                    <div id="live-indicator" class="badge rounded-pill bg-success d-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; opacity: 0; transition: opacity 0.5s;">
                        <span class="pulse-dot"></span> LIVE
                    </div>
                </div>
                <p class="mb-0 text-secondary" style="font-size:14px; font-weight: 600;">
                    <span id="live-header-date">{{ now()->translatedFormat('l, d F Y') }}</span> · <i class="bi bi-clock-fill text-cyan me-1"></i><span id="live-header-clock" class="fw-bold text-cyan" style="font-family: monospace;">--:--:--</span>
                </p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-cyan-soft text-cyan px-2.5 py-1.5 fw-bold d-none d-md-inline-block" style="font-size: 11px; border-radius: 8px;">
                <i class="bi bi-lightning-charge-fill me-1"></i>SLA Aman
            </span>
            <a href="{{ $isLibur ? 'javascript:void(0)' : route('user.surat.create') }}" 
               class="btn btn-primary-modern d-flex align-items-center gap-2 {{ $isLibur ? 'disabled' : '' }}"
               @if($isLibur) onclick="Swal.fire({icon: 'info', title: 'Layanan Tutup', text: 'Pengajuan surat baru hanya tersedia pada hari kerja. Senin–Kamis: 07.30–16.00 WIB, Jumat: 07.30–16.30 WIB. Sabtu & Minggu: Libur.', confirmButtonColor: '#1e3a5f'})" @endif>
                <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
            </a>
        </div>
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
        <div class="stat-card-glow"></div>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-envelope-paper-fill"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Total Surat</div>
            <div class="stat-value-new" id="stat-totalSurat">{{ $totalSurat }}</div>
            <div class="stat-sub-new">Termasuk draf pengajuan</div>
        </div>
    </div>

    {{-- SELESAI --}}
    <div class="stat-card-new green animate-in" style="animation-delay: 0.15s;">
        <div class="stat-card-glow"></div>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Selesai</div>
            <div class="stat-value-new" id="stat-suratSelesai">{{ $suratSelesai }}</div>
            <div class="stat-sub-new">Terarsip & diverifikasi</div>
        </div>
    </div>

    {{-- PROSES --}}
    <div class="stat-card-new amber animate-in" style="animation-delay: 0.2s;">
        <div class="stat-card-glow"></div>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Sedang Proses</div>
            <div class="stat-value-new" id="stat-suratProses">{{ $suratProses }}</div>
            <div class="stat-sub-new">Menunggu aksi petugas</div>
        </div>
    </div>

    {{-- DITOLAK / REVISI --}}
    <div class="stat-card-new red animate-in" style="animation-delay: 0.25s;">
        <div class="stat-card-glow"></div>
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
                    @php
                        $createdIso = $surat->created_at->toIso8601String();
                        $deadlineIso = $surat->deadline_sla ? $surat->deadline_sla->toIso8601String() : '';
                    @endphp
                    <div class="mb-3 sla-item p-2.5 rounded transition-all-200" 
                         data-sla-item="true"
                         data-created-at="{{ $createdIso }}"
                         data-deadline-at="{{ $deadlineIso }}"
                         style="border: 1px solid transparent;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="font-size:13px; color:#1e293b;" title="{{ $surat->judul }}">
                                {{ Str::limit($surat->judul, 28) }}
                            </span>
                            <span class="badge sla-countdown-badge" style="font-size:11px; padding:4.5px 10px; border-radius:8px; font-weight: 700; transition: all 0.3s ease;">
                                ⏱ Hitung mundur...
                            </span>
                        </div>
                        <div class="progress" style="height:8px; background:#e2e8f0; border-radius:99px; overflow:hidden;">
                            <div class="progress-bar sla-progress-bar" style="width:0%; border-radius:99px; transition: width 1s linear, background-color 0.5s ease;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1" style="font-size:11px; color:#94a3b8;">
                            <span>Tahap {{ $surat->tahap_sekarang }}/10 · {{ $surat->nama_tahap }}</span>
                            <span class="fw-semibold" style="font-size:10px;" id="sla-pct-text-{{ $surat->id }}">0%</span>
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
                        <a href="{{ route('user.surat.file_index') }}" class="btn-glass-primary" style="background: rgba(255, 255, 255, 0.7); color: #1e3a5f !important; border-color: rgba(30, 58, 95, 0.1); box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                            <i class="bi bi-file-earmark-x"></i> Hapus File Surat
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

</div>{{-- /.container-fluid --}}

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
(function() {
    // Function to run dashboard scripts
    const initDashboard = () => {
        // Mixed Chart: Distribusi Jenis Surat
    const jenisCtx = document.getElementById('jenisChart');
    if (jenisCtx) {
        const jenisData = @json($jenisSurat);
        const jenisLabels = @json(\App\Models\Surat::JENIS_LABEL);
        
        const labels = Object.keys(jenisData).map(key => jenisLabels[key] || key);
        const data = Object.values(jenisData);
        
        new Chart(jenisCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Jumlah',
                        data: data,
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(139, 92, 246, 0.8)'); // Purple
                            gradient.addColorStop(1, 'rgba(236, 72, 153, 0.8)'); // Pink
                            return gradient;
                        },
                        borderRadius: 8,
                        barPercentage: 0.5,
                        categoryPercentage: 0.7,
                        borderWidth: 0
                    },
                    {
                        type: 'line',
                        label: 'Garis Distribusi',
                        data: data,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#8b5cf6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: { size: 11, weight: '500' },
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } },
                        grid: { color: 'rgba(241, 245, 249, 0.5)', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#64748b', font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Premium Mixed Chart: Tren Pengajuan Surat (Bar + Line)
    const trenCtx = document.getElementById('trenChart');
    if (trenCtx) {
        const trenData = @json($trenBulanan);
        const labels = Object.keys(trenData);
        const data = Object.values(trenData);
        
        // Compute cumulative values for line dataset
        const cumulative = data.reduce((acc, val, idx) => {
            acc.push((acc[idx - 1] || 0) + val);
            return acc;
        }, []);

        new Chart(trenCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Jumlah Surat',
                        data: data,
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.8)'); // Emerald
                            gradient.addColorStop(1, 'rgba(52, 211, 153, 0.8)'); // Light Emerald
                            return gradient;
                        },
                        borderRadius: 8,
                        barPercentage: 0.5,
                        categoryPercentage: 0.7,
                        borderWidth: 0,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Kumulatif',
                        data: cumulative,
                        borderColor: '#10b981', // Emerald
                        backgroundColor: 'transparent',
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        yAxisID: 'y1',
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: { size: 11, weight: '500' },
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } },
                        grid: { color: 'rgba(241, 245, 249, 0.5)', drawBorder: false }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false },
                        ticks: { color: '#10b981', font: { size: 10 } }
                    },
                    x: {
                        ticks: { color: '#64748b', font: { size: 10 } },
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
                didOpen: () => {
                    const checkbox = document.getElementById('dontShowAgain');
                    if (checkbox) {
                        checkbox.addEventListener('change', (e) => {
                            if (e.target.checked) {
                                localStorage.setItem(faqPopupKey, 'true');
                            } else {
                                localStorage.removeItem(faqPopupKey);
                            }
                        });
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('user.faq.index') }}";
                }
            });
        }, 1000); // Muncul setelah 1 detik dashboard terbuka
    }

    scheduleRefresh();

    // 1. Live Header Dynamic Greeting & Real-time Clock
    const updateHeaderGreetingAndClock = () => {
        const now = new Date();
        
        // Format Clock: HH:MM:SS
        const pad = (num) => String(num).padStart(2, '0');
        const timeStr = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        const clockEl = document.getElementById('live-header-clock');
        if (clockEl) {
            clockEl.textContent = timeStr;
        }

        // Adaptive greeting based on client hour
        const hour = now.getHours();
        let greeting = 'Halo';
        let greetingIcon = 'bi-brightness-alt-high-fill';
        
        if (hour >= 5 && hour < 12) {
            greeting = 'Selamat Pagi';
            greetingIcon = 'bi-brightness-alt-high-fill';
        } else if (hour >= 12 && hour < 15) {
            greeting = 'Selamat Siang';
            greetingIcon = 'bi-brightness-high-fill';
        } else if (hour >= 15 && hour < 19) {
            greeting = 'Selamat Sore';
            greetingIcon = 'bi-cloud-sun-fill';
        } else {
            greeting = 'Selamat Malam';
            greetingIcon = 'bi-moon-stars-fill';
        }

        const greetingEl = document.getElementById('welcome-greeting-text');
        if (greetingEl) {
            const userName = "{{ Str::words(Auth::user()->name, 2, '') }}";
            greetingEl.innerHTML = `<i class="bi ${greetingIcon} text-primary me-2"></i>${greeting}, ${userName}!`;
        }
    };

    updateHeaderGreetingAndClock();
    const headerInterval = setInterval(updateHeaderGreetingAndClock, 1000);

    // 2. Live SLA Countdown Timer & Glow Indicator
    const updateSlaCountdowns = () => {
        const now = new Date();
        const slaItems = document.querySelectorAll('[data-sla-item="true"]');
        
        slaItems.forEach((item, index) => {
            const createdStr = item.getAttribute('data-created-at');
            const deadlineStr = item.getAttribute('data-deadline-at');
            
            if (!deadlineStr) {
                const badge = item.querySelector('.sla-countdown-badge');
                if (badge) {
                    badge.innerHTML = '🟢 SLA Ok';
                    badge.style.background = '#dcfce7';
                    badge.style.color = '#15803d';
                }
                return;
            }

            const createdAt = new Date(createdStr);
            const deadlineAt = new Date(deadlineStr);
            const diffMs = deadlineAt - now;

            const badge = item.querySelector('.sla-countdown-badge');
            const progressBar = item.querySelector('.sla-progress-bar');
            const pctText = item.querySelector('[id^="sla-pct-text-"]');

            if (diffMs <= 0) {
                // Expired SLA (Terlambat)
                if (badge) {
                    badge.innerHTML = '🔴 ⚠ Terlambat';
                    badge.style.background = '#fee2e2';
                    badge.style.color = '#b91c1c';
                    badge.className = 'badge sla-countdown-badge'; // reset glow classes
                }
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.backgroundColor = '#ef4444';
                }
                if (pctText) {
                    pctText.textContent = '100%';
                    pctText.className = 'fw-bold text-rose';
                }
                item.style.borderColor = 'rgba(239, 68, 68, 0.15)';
                item.style.background = 'rgba(239, 68, 68, 0.02)';
            } else {
                // Active SLA (Running countdown)
                const totalDuration = deadlineAt - createdAt;
                const elapsed = now - createdAt;
                let pct = Math.max(2, Math.min(100, (elapsed / totalDuration) * 100));
                
                // Format duration breakdown: H:m:s
                const totalSeconds = Math.floor(diffMs / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                const timeString = `${hours}j ${minutes}m ${seconds}s`;

                if (progressBar) {
                    progressBar.style.width = `${pct}%`;
                }
                if (pctText) {
                    pctText.textContent = `${Math.round(pct)}%`;
                }

                // Theme and Pulse Glow conditions
                if (hours < 3) {
                    // Critical (under 3h) - Pulse Red Glow
                    if (badge) {
                        badge.innerHTML = `⚡ ${timeString}`;
                        badge.style.background = '#fee2e2';
                        badge.style.color = '#b91c1c';
                        badge.className = 'badge sla-countdown-badge glow-pulse-red';
                    }
                    if (progressBar) progressBar.style.backgroundColor = '#ef4444';
                    item.style.borderColor = 'rgba(239, 68, 68, 0.2)';
                    item.style.background = 'rgba(239, 68, 68, 0.02)';
                } else if (hours < 12) {
                    // Warning (under 12h) - Pulse Amber Glow
                    if (badge) {
                        badge.innerHTML = `⚡ ${timeString}`;
                        badge.style.background = '#fef3c7';
                        badge.style.color = '#92400e';
                        badge.className = 'badge sla-countdown-badge glow-pulse-amber';
                    }
                    if (progressBar) progressBar.style.backgroundColor = '#f59e0b';
                    item.style.borderColor = 'rgba(245, 158, 11, 0.15)';
                    item.style.background = 'rgba(245, 158, 11, 0.01)';
                } else {
                    // Healthy (above 12h)
                    if (badge) {
                        badge.innerHTML = `✔ ${timeString}`;
                        badge.style.background = '#dcfce7';
                        badge.style.color = '#15803d';
                        badge.className = 'badge sla-countdown-badge';
                    }
                    if (progressBar) progressBar.style.backgroundColor = '#22c55e';
                    item.style.borderColor = 'transparent';
                    item.style.background = 'transparent';
                }
            }
        });
    };

    updateSlaCountdowns();
    const slaInterval = setInterval(updateSlaCountdowns, 1000);

    // Clean up duplicate intervals on Hotwire Turbo load
    if (window.activeDashboardIntervals) {
        window.activeDashboardIntervals.forEach(clearInterval);
    }
    window.activeDashboardIntervals = [headerInterval, slaInterval];

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
};

// Execute immediately since Turbo already swapped the content
initDashboard();

})();
</script>


@endsection
