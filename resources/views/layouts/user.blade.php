<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} — Surat Balai Pengelolaan SUML</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-primary: #f5f6fa;
            --bg-secondary: rgba(255, 255, 255, 0.45);
            --bg-tertiary: rgba(255, 255, 255, 0.3);
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --border-color: rgba(255, 255, 255, 0.6);
            --navbar-bg: rgba(255, 255, 255, 0.25);
            --navbar-border: rgba(255, 255, 255, 0.5);
            --navbar-text: #1e293b;
            
            --glass-blur: 16px;
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        body {
            /* Mesh gradient background for Glassmorphism */
            background-color: #f0f4f8;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,0.03) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.03) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.03) 0, transparent 50%),
                linear-gradient(135deg, #e0c3fc88 0%, #8ec5fc88 100%);
            background-attachment: fixed;
            background-size: cover;
            color: var(--text-primary);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .navbar-main {
            background: var(--navbar-bg);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            border-bottom: 1px solid var(--navbar-border);
            box-shadow: var(--glass-shadow);
            padding: 0 1.5rem;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 1050;
            transition: height 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
        }


        .navbar-brand-text {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a5f !important;
            letter-spacing: 0.01em;
        }
        .navbar-brand-text small {
            display: block;
            font-size: 10px;
            font-weight: 500;
            color: #475569;
            letter-spacing: 0;
        }
        .nav-link-item {
            color: #475569 !important;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px !important;
            border-radius: 12px;
            /* Avoid 'all' to prevent transition from browser defaults on load */
            transition: 
                background 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none !important;
            outline: none !important;
            border: none !important;
            box-shadow: none;
            -webkit-tap-highlight-color: transparent;
            /* Rendering optimization */
            backface-visibility: hidden;
            transform: translateZ(0);
            -webkit-font-smoothing: antialiased;
        }
        /* Ensure no bootstrap focus ring */
        .nav-link-item:focus, 
        .nav-link-item:active,
        .nav-link-item:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            background: rgba(255,255,255,0.6);
        }
        .nav-link-item:hover {
            color: #2563eb !important;
            background: rgba(255,255,255,0.7);
        }
        .nav-link-item.active {
            color: #2563eb !important;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 4px 12px rgba(31, 38, 135, 0.05);
        }
        .nav-link-item i { 
            font-size: 1.1rem;
            display: inline-block;
        }

        /* ===== NOTIF BELL ===== */
        .notif-btn {
            position: relative;
            background: rgba(255,255,255,0.4);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 10px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e3a5f;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .notif-btn:hover { background: rgba(255,255,255,0.7); }
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        /* ===== NOTIF DROPDOWN ===== */
        .notif-dropdown {
            width: 340px;
            max-height: 420px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            box-shadow: var(--glass-shadow);
            border-radius: 16px;
            padding: 0;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: background 0.3s;
        }
        .notif-header {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            gap: 10px;
            align-items: flex-start;
            text-decoration: none;
            transition: background 0.2s;
            background: transparent;
        }
        .notif-item:hover { background: rgba(255,255,255,0.5); }
        .notif-item.unread { 
            background: rgba(59, 130, 246, 0.08);
        }
        .notif-item.unread:hover { 
            background: rgba(59, 130, 246, 0.15);
        }
        .notif-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.8);
        }
        .notif-icon.success { color: #15803d; }
        .notif-icon.warning { color: #b45309; }
        .notif-icon.danger  { color: #b91c1c; }
        .notif-icon.info    { color: #1d4ed8; }
        .notif-title { font-size: 12px; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
        .notif-sub   { font-size: 11px; color: var(--text-secondary); margin-top: 2px; }
        .notif-time  { font-size: 10px; color: var(--text-secondary); margin-top: 3px; }
        .notif-empty { padding: 32px 16px; text-align: center; color: var(--text-secondary); font-size: 13px; }

        /* ===== AVATAR --===== */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.6);
            border: 1px solid rgba(255,255,255,0.8);
            color: #1e3a5f;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .user-avatar:hover { background: rgba(255,255,255,0.8); }

        /* General Dropdown */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color) !important;
            box-shadow: var(--glass-shadow) !important;
            border-radius: 12px !important;
            padding: 8px !important;
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
        }
        .dropdown-item.active {
            background: #2563eb !important;
            color: #fff !important;
        }
        .dropdown-item i {
            font-size: 1.1rem;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            padding: 24px;
            min-height: calc(100vh - 60px);
        }

        /* ===== CARDS ===== */
        .card-custom {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--glass-shadow);
            background: var(--bg-secondary);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            transition: all 0.4s ease;
        }
        .card-custom:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        .stat-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            background: var(--bg-secondary);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            box-shadow: var(--glass-shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        .stat-card .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
            display: inline-block;
        }
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 12px;
            opacity: 0.75;
            margin-top: 4px;
        }



        /* ===== TRACKING STEPS ===== */
        .tracking-steps { position: relative; }
        .step-item {
            display: flex;
            gap: 14px;
            position: relative;
        }
        .step-item:not(:last-child) .step-line {
            position: absolute;
            left: 15px;
            top: 32px;
            width: 2px;
            height: calc(100% - 8px);
            background: rgba(255,255,255,0.6);
            transition: background 0.3s;
        }
        .step-item:not(:last-child).done .step-line { background: #86efac; }
        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            z-index: 1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .step-circle.done    { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .step-circle.active  { background: #dbeafe; color: #1d4ed8; border: 2px solid #3b82f6; }
        .step-circle.waiting { background: rgba(255,255,255,0.6); color: var(--text-secondary); border: 1px solid var(--border-color); }
        .step-circle.rejected{ background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .step-content { padding-bottom: 20px; flex: 1; }
        .step-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .step-title.active  { color: #1d4ed8; }
        .step-title.waiting { color: #64748b; }
        .step-meta { font-size: 11px; color: var(--text-secondary); margin-top: 2px; }
        .step-note {
            font-size: 12px;
            background: rgba(255,255,255,0.5);
            border-left: 3px solid rgba(255,255,255,0.8);
            padding: 6px 10px;
            border-radius: 0 6px 6px 0;
            color: var(--text-primary);
            margin-top: 6px;
        }

        /* ===== BADGE SIFAT ===== */
        .badge-segera  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-rahasia { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-biasa   { background: rgba(255,255,255,0.6); color: var(--text-secondary); border: 1px solid var(--border-color); }

        /* ===== SLA BAR ===== */
        .sla-bar {
            height: 5px;
            background: rgba(255,255,255,0.5);
            border-radius: 99px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.8);
        }
        .sla-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.3s;
        }

        /* ===== FLASH ===== */
        .flash-container {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            width: 320px;
        }
        .alert {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.8) !important;
            box-shadow: var(--glass-shadow);
            color: var(--text-primary);
        }
        .alert-success { border-left: 4px solid #10b981 !important; }
        .alert-danger { border-left: 4px solid #ef4444 !important; }

        /* ===== UPLOAD AREA ===== */
        .upload-area {
            border: 2px dashed rgba(255,255,255,0.8);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background: rgba(255,255,255,0.5);
            color: #2563eb;
        }
        .upload-area input[type=file] {
            display: none;
        }

        /* ===== FORMS ===== */
        .form-control, .form-select {
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.8);
            color: var(--text-primary);
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.7);
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        /* ===== TABLE ===== */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: rgba(255,255,255,0.4);
        }
        .table thead th {
            background: rgba(255,255,255,0.4);
            border-bottom: 2px solid rgba(255,255,255,0.6);
            color: #1e293b;
            font-weight: 600;
        }
        .table tbody tr:hover {
            background: rgba(255,255,255,0.3) !important;
        }

        /* Scrollbar notif */
        .notif-dropdown::-webkit-scrollbar { width: 4px; }
        .notif-dropdown::-webkit-scrollbar-track { background: transparent; }
        .notif-dropdown::-webkit-scrollbar-thumb { 
            background: rgba(0,0,0,0.1);
            border-radius: 99px;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 991px) {
            .navbar-brand-text img { height: 35px !important; }
            .main-content {
                padding: 16px 12px;
                padding-bottom: 90px; /* space for bottom nav */
            }
            .flash-container {
                width: calc(100% - 32px);
                right: 16px;
                left: 16px;
                top: 70px;
            }
            .notif-dropdown {
                width: calc(100vw - 32px);
                max-width: 340px;
            }
            .stat-card {
                padding: 16px;
            }
            .stat-card .stat-icon { font-size: 24px; }
            .stat-card .stat-value { font-size: 24px; }
            
            /* Hide footer on mobile since we have bottom nav */
            footer {
                padding-bottom: 80px !important;
            }
            
            .table-responsive {
                border: 1px solid rgba(255,255,255,0.5);
                border-radius: 12px;
                background: rgba(255,255,255,0.3);
            }
        }

        /* Scrolled Navbar */
        .navbar-main.scrolled {
            height: 55px;
            background: rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        /* ===== MOBILE BOTTOM NAV ===== */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1040;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.8);
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
        }
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 10px;
            font-weight: 600;
            gap: 2px;
            flex: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .mobile-nav-item i {
            font-size: 20px;
            width: 40px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .mobile-nav-item.active {
            color: #2563eb;
        }
        .mobile-nav-item.active i {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            transform: translateY(-2px);
        }
        .mobile-nav-item:active {
            transform: scale(0.9);
        }
        .mobile-nav-item:hover { color: #3b82f6; }

        /* ===== OFFCANVAS NOTIF ===== */
        .offcanvas-notif {
            width: 380px !important;
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-left: 1px solid var(--border-color) !important;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05) !important;
        }
        .offcanvas-notif .offcanvas-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 20px 24px;
        }
        .offcanvas-notif .notif-item {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            display: flex;
            gap: 14px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }
        .offcanvas-notif .notif-item:hover {
            background: rgba(255, 255, 255, 0.5);
            padding-left: 28px;
        }
        .offcanvas-notif .notif-item.unread {
            background: rgba(59, 130, 246, 0.05);
            border-left: 4px solid #3b82f6;
        }
        .offcanvas-notif .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>


{{-- ===== NAVBAR ===== --}}
<nav class="navbar navbar-main d-flex align-items-center justify-content-between">
    {{-- Brand --}}
    <a class="navbar-brand-text text-decoration-none d-flex align-items-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/BP_SUML2.png') }}" alt="Logo BPR SUML" style="height: 45px; object-fit: contain;">
    </a>

    {{-- Nav Links --}}
    <div class="d-flex align-items-center gap-2 d-none d-lg-flex">
        <a href="{{ url('/?home=1') }}" class="nav-link-item">
            <i class="bi bi-globe"></i> Beranda
        </a>
        <a href="{{ route('dashboard') }}"
           class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house"></i> Dashboard
        </a>

        {{-- Dropdown Surat Saya --}}
        <div class="dropdown">
            <button class="nav-link-item border-0 bg-transparent dropdown-toggle {{ request()->routeIs('user.surat.*') && !request()->routeIs('user.surat.create') ? 'active' : '' }}" 
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-envelope"></i> Surat Saya
            </button>
            <ul class="dropdown-menu border-0 shadow-sm">
                <li>
                    <a class="dropdown-item {{ request()->routeIs('user.surat.index') && request('status') !== 'draft' ? 'active' : '' }}" href="{{ route('user.surat.index') }}">
                        <i class="bi bi-envelope-open me-2"></i> Surat Saya (Card)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request('status') === 'draft' ? 'active' : '' }}" href="{{ route('user.surat.index', ['status' => 'draft']) }}">
                        <i class="bi bi-pencil-square me-2"></i> Draft Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('user.surat.table') ? 'active' : '' }}" href="{{ route('user.surat.table') }}">
                        <i class="bi bi-table me-2"></i> Tabel Surat (Detail)
                    </a>
                </li>
            </ul>
        </div>
        <a href="{{ route('user.surat.create') }}"
           class="nav-link-item {{ request()->routeIs('user.surat.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Ajukan Surat
        </a>
        <a href="{{ route('user.statistik.index') }}"
           class="nav-link-item {{ request()->routeIs('user.statistik.index') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Statistik
        </a>
        <a href="{{ route('user.template.index') }}"
           class="nav-link-item {{ request()->routeIs('user.template.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-word"></i> Template
        </a>
        
        {{-- Lainnya Dropdown --}}
        <div class="dropdown">
            <button class="nav-link-item border-0 bg-transparent dropdown-toggle {{ request()->routeIs('user.faq.*', 'user.about.*', 'user.notifikasi.index', 'user.aspirasi.index') ? 'active' : '' }}" 
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-grid"></i> Lainnya
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0">

                <li>
                    <a class="dropdown-item {{ request()->routeIs('user.notifikasi.index') ? 'active' : '' }}" href="{{ route('user.notifikasi.index') }}">
                        <i class="bi bi-bell me-2"></i> Notifikasi
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('user.aspirasi.index') ? 'active' : '' }}" href="{{ route('user.aspirasi.index') }}">
                        <i class="bi bi-chat-right-heart me-2"></i> Kotak Aspirasi
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('user.faq.*') ? 'active' : '' }}" href="{{ route('user.faq.index') }}">
                        <i class="bi bi-question-circle me-2"></i> FAQ
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('user.about.*') ? 'active' : '' }}" href="{{ route('user.about.index') }}">
                        <i class="bi bi-info-circle me-2"></i> About
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Right: notif + avatar --}}
    <div class="d-flex align-items-center gap-2">

        {{-- Notifikasi (Sidebar Trigger) --}}
        <button type="button" class="notif-btn" 
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNotif"
                aria-controls="offcanvasNotif" id="notif-toggle">
            <i class="bi bi-bell"></i>
            @php $unreadNotif = auth()->user()->unreadNotifications->count(); @endphp
            @if($unreadNotif > 0)
                <span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>
            @endif
        </button>

        {{-- User dropdown --}}
        <div class="dropdown">
            <div class="user-avatar" data-bs-toggle="dropdown" style="padding: 0; overflow: hidden; display: flex; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                @if(Auth::user()->profile_photo)
                    <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @endif
            </div>
            <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px; border:none; box-shadow:0 8px 24px rgba(0,0,0,0.1); font-size:13px; min-width:180px;">
                <li><div class="px-3 py-2 border-bottom">
                    <div style="font-weight:600; color:var(--text-primary); font-size:13px;">{{ Auth::user()->name }}</div>
                    <div style="font-size:11px; color:var(--text-secondary);">{{ Auth::user()->email }}</div>
                </div></li>
                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i> Profil Saya
                </a></li>
                <li><a class="dropdown-item py-2" href="{{ route('user.surat.index') }}">
                    <i class="bi bi-envelope me-2"></i> Surat Saya
                </a></li>
                <li><a class="dropdown-item py-2" href="{{ route('user.surat.index', ['status' => 'draft']) }}">
                    <i class="bi bi-pencil-square me-2"></i> Draft Saya
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Flash toast --}}
<div class="flash-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius:10px; font-size:13px; border:none;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius:10px; font-size:13px; border:none;">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- Main --}}
<main class="main-content">
    @yield('content')
</main>

{{-- Modals stack: dirender di luar <main> agar tidak terjebak stacking context dari transform animation --}}
@stack('modals')

{{-- ===== MOBILE BOTTOM NAV ===== --}}
<div class="mobile-bottom-nav d-lg-none">
    {{-- Dropup Beranda & Dashboard --}}
    <div class="dropup" style="flex: 1;">
        <button class="mobile-nav-item border-0 bg-transparent w-100 {{ request()->routeIs('dashboard') || request()->is('/') ? 'active' : '' }}" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-house"></i>
            <span>Menu Utama</span>
        </button>
        <ul class="dropdown-menu border-0 shadow-lg" style="margin-bottom: 20px; border-radius: 15px; min-width: 160px;">
            <li>
                <a class="dropdown-item py-3 {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/?home=1') }}">
                    <i class="bi bi-globe me-2"></i> Beranda Publik
                </a>
            </li>
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard User
                </a>
            </li>
        </ul>
    </div>
    {{-- Dropup Surat Saya untuk Mobile --}}
    <div class="dropup" style="flex: 1;">
        <button class="mobile-nav-item border-0 bg-transparent w-100 {{ request()->routeIs('user.surat.*') && !request()->routeIs('user.surat.create') ? 'active' : '' }}" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-envelope"></i>
            <span>Surat Saya</span>
        </button>
        <ul class="dropdown-menu border-0 shadow-lg" style="margin-bottom: 20px; border-radius: 15px; min-width: 160px;">
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('user.surat.index') && request('status') !== 'draft' ? 'active' : '' }}" href="{{ route('user.surat.index') }}">
                    <i class="bi bi-envelope-open me-2"></i> Surat Saya (Card)
                </a>
            </li>
            <li>
                <a class="dropdown-item py-3 {{ request('status') === 'draft' ? 'active' : '' }}" href="{{ route('user.surat.index', ['status' => 'draft']) }}">
                    <i class="bi bi-pencil-square me-2"></i> Draft Saya
                </a>
            </li>
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('user.surat.table') ? 'active' : '' }}" href="{{ route('user.surat.table') }}">
                    <i class="bi bi-table me-2"></i> Tabel Surat (Detail)
                </a>
            </li>
        </ul>
    </div>
    <a href="{{ route('user.surat.create') }}" class="mobile-nav-item {{ request()->routeIs('user.surat.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i>
        <span>Ajukan</span>
    </a>

    <a href="{{ route('user.statistik.index') }}" class="mobile-nav-item {{ request()->routeIs('user.statistik.index') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i>
        <span>Statistik</span>
    </a>

    {{-- Dropup Lainnya untuk Mobile --}}
    <div class="dropup" style="flex: 1;">
        <button class="mobile-nav-item border-0 bg-transparent w-100 {{ request()->routeIs('user.faq.*') || request()->routeIs('user.about.*') || request()->routeIs('user.template.*') || request()->routeIs('user.notifikasi.index') || request()->routeIs('user.aspirasi.index') ? 'active' : '' }}" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-grid"></i>
            <span>Lainnya</span>
        </button>
        <ul class="dropdown-menu border-0 shadow-lg" style="margin-bottom: 20px; border-radius: 15px; min-width: 180px;">
            <li>
                <a class="dropdown-item py-3" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNotif">
                    <i class="bi bi-bell me-2"></i> Notifikasi
                </a>
            </li>
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('user.aspirasi.index') ? 'active' : '' }}" href="{{ route('user.aspirasi.index') }}">
                    <i class="bi bi-chat-right-heart me-2"></i> Kotak Aspirasi
                </a>
            </li>
            <li><hr class="dropdown-divider mx-3"></li>
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('user.template.*') ? 'active' : '' }}" href="{{ route('user.template.index') }}">
                    <i class="bi bi-file-earmark-word me-2"></i> Template
                </a>
            </li>
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('user.faq.*') ? 'active' : '' }}" href="{{ route('user.faq.index') }}">
                    <i class="bi bi-question-circle me-2"></i> FAQ
                </a>
            </li>
            <li>
                <a class="dropdown-item py-3 {{ request()->routeIs('user.about.*') ? 'active' : '' }}" href="{{ route('user.about.index') }}">
                    <i class="bi bi-info-circle me-2"></i> Tentang
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- ===== FOOTER ===== --}}
<footer class="py-4 mt-auto">
    <div class="container-fluid text-center">
        <p class="mb-0" style="font-size: 13px; color: var(--text-secondary); opacity: 0.8;">
             &copy; {{ date('Y') }} Balai Pengelolaan SUML &mdash; RI. All rights reserved.
        </p>
    </div>
</footer>

{{-- Hidden forms --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
<form id="readall-form" action="{{ route('notif.readAll') }}" method="POST" class="d-none">@csrf</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    .rotate-180 { transform: rotate(180deg); }
</style>

@vite(['resources/js/app.js', 'resources/css/app.css'])
<script>
    // Auto-dismiss flash setelah 4 detik
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(el => {
            new bootstrap.Alert(el).close();
        });
    }, 4000);

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.navbar-main');
        if (window.scrollY > 20) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>
@stack('scripts')

{{-- ===== OFFCANVAS NOTIFIKASI ===== --}}
<div class="offcanvas offcanvas-end offcanvas-notif" tabindex="-1" id="offcanvasNotif" aria-labelledby="offcanvasNotifLabel">
    <div class="offcanvas-header d-flex align-items-center justify-content-between">
        <h5 class="offcanvas-title fw-bold" id="offcanvasNotifLabel" style="font-size: 16px; color: var(--text-primary);">
            <i class="bi bi-bell me-2 text-primary"></i> Notifikasi
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="px-4 py-2 border-bottom d-flex align-items-center justify-content-between bg-light bg-opacity-50">
        <span class="text-muted" style="font-size: 11px;">{{ auth()->user()->notifications->count() }} Notifikasi Terakhir</span>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <a href="#" onclick="event.preventDefault(); document.getElementById('readall-form').submit();" 
               class="text-decoration-none fw-bold" style="font-size: 11px; color: #3b82f6;">
                Tandai semua dibaca
            </a>
        @endif
    </div>
    <div class="offcanvas-body">
        @forelse(auth()->user()->notifications->take(15) as $notif)
            <a href="{{ route('notif.read', $notif->id) }}"
               class="notif-item {{ $notif->read_at ? '' : 'unread' }}">
                <div class="notif-icon {{ $notif->data['type'] ?? 'info' }}">
                    @switch($notif->data['type'] ?? 'info')
                        @case('success') <i class="bi bi-check-circle-fill text-success"></i> @break
                        @case('warning') <i class="bi bi-exclamation-triangle-fill text-warning"></i> @break
                        @case('danger')  <i class="bi bi-x-circle-fill text-danger"></i> @break
                        @default         <i class="bi bi-info-circle-fill text-primary"></i>
                    @endswitch
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size: 13px; font-weight: 600; color: var(--text-primary); line-height: 1.3;">
                        {{ $notif->data['title'] ?? 'Notifikasi' }}
                    </div>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                        {{ Str::limit($notif->data['message'] ?? '', 80) }}
                    </div>
                    <div style="font-size: 10px; color: var(--text-secondary); margin-top: 6px; opacity: 0.7;">
                        <i class="bi bi-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}
                    </div>
                </div>
                @if(!$notif->read_at)
                    <div style="width:8px; height:8px; border-radius:50%; background:#3b82f6; flex-shrink:0; margin-top:6px;"></div>
                @endif
            </a>
        @empty
            <div class="p-5 text-center">
                <i class="bi bi-bell-slash text-muted" style="font-size: 40px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 small">Belum ada notifikasi baru untuk Anda.</p>
            </div>
        @endforelse
    </div>
    <div class="p-3 border-top text-center bg-light bg-opacity-50">
        <a href="{{ route('dashboard') }}" class="text-decoration-none small fw-bold" style="color: #3b82f6;">
            Buka Dashboard <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>

</body>
</html>