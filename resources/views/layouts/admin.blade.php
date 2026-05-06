<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Persuratan BP SUML</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}">



    {{-- Bootstrap CDN for Legacy Grid & Components --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Sora', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50:  '#eef3fb',
                            100: '#d5e2f5',
                            200: '#abc5eb',
                            300: '#7da3df',
                            400: '#4f7fd3',
                            500: '#2b5fbe',
                            600: '#1e3a5f',
                            700: '#172d4a',
                            800: '#0f1f33',
                            900: '#08111c',
                        },
                    },
                    boxShadow: {
                        'glow': '0 0 0 3px rgba(96,165,250,0.3)',
                        'card': '0 1px 3px 0 rgba(0,0,0,0.06), 0 4px 16px -2px rgba(0,0,0,0.08)',
                    },
                    animation: {
                        'slide-in': 'slideIn 0.25s ease-out',
                        'fade-in': 'fadeIn 0.2s ease-out',
                        'bounce-in': 'bounceIn 0.4s ease-out',
                    },
                    keyframes: {
                        slideIn: { '0%': { transform: 'translateX(-8px)', opacity: 0 }, '100%': { transform: 'translateX(0)', opacity: 1 } },
                        fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        bounceIn: { '0%': { transform: 'scale(0.9)', opacity: 0 }, '60%': { transform: 'scale(1.03)' }, '100%': { transform: 'scale(1)', opacity: 1 } },
                    },
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    {{-- Alpine JS CDN (Fallback jika Vite/app.js mati) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ===== SCROLLBAR CUSTOM ===== */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }

        /* Sidebar scrollbar hidden */
        #sidebar-nav { scrollbar-width: none; }
        #sidebar-nav::-webkit-scrollbar { display: none; }

        /* ===== GLASS EFFECT ===== */
        .glass {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* ===== ACTIVE NAV GLOW ===== */
        .nav-active {
            background: rgba(96,165,250,0.12);
            border-left-color: #60a5fa;
            color: #fff;
        }

        /* ===== TOPBAR BACKDROP ===== */
        #topbar {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* ===== DROPDOWN ANIMATION ===== */
        .dropdown-list {
            display: none;
        }
        .dropdown-group.open .dropdown-list {
            display: block;
            animation: slideIn 0.18s ease-out;
        }
        .dropdown-group.open .chevron-icon {
            transform: rotate(180deg);
        }
        .chevron-icon {
            transition: transform 0.2s ease;
        }

        /* ===== TOOLTIP ===== */
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: #f1f5f9;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 99;
        }

        /* ===== FLASH TOAST ===== */
        .toast-enter {
            animation: bounceIn 0.4s ease-out forwards;
        }

        /* ===== SHIMMER LOGO ===== */
        .logo-shimmer {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            animation: shimmer 2.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* ===== STAT CARD HOVER ===== */
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -4px rgba(0,0,0,0.12);
        }

        /* ===== MOBILE BACKDROP ===== */
        #mobile-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 30;
            backdrop-filter: blur(2px);
        }
        #mobile-backdrop.show { display: block; animation: fadeIn 0.2s ease; }

        /* ===== OFFCANVAS NOTIF ===== */
        .offcanvas-notif {
            width: 380px !important;
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-left: 1px solid var(--border-color) !important;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05) !important;
        }
        .dark .offcanvas-notif {
            background: rgba(15, 23, 42, 0.75) !important;
        }
        .offcanvas-notif .offcanvas-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 20px 24px;
        }
        .dark .offcanvas-notif .offcanvas-header {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .offcanvas-notif .notif-item {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            display: flex;
            gap: 14px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }
        .dark .offcanvas-notif .notif-item {
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .offcanvas-notif .notif-item:hover {
            background: rgba(255, 255, 255, 0.5);
            padding-left: 28px;
        }
        .dark .offcanvas-notif .notif-item:hover {
            background: rgba(255, 255, 255, 0.05);
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
            font-size: 16px;
            flex-shrink: 0;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }
        .offcanvas-notif .notif-icon.success { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
        .offcanvas-notif .notif-icon.warning { background: #fef3c7; color: #b45309; border-color: #fde68a; }
        .offcanvas-notif .notif-icon.danger { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
        .offcanvas-notif .notif-icon.info { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
        
        .dark .offcanvas-notif .notif-icon.success { background: rgba(21, 128, 61, 0.2); color: #4ade80; border-color: rgba(74, 222, 128, 0.2); }
        .dark .offcanvas-notif .notif-icon.warning { background: rgba(180, 83, 9, 0.2); color: #fbbf24; border-color: rgba(251, 191, 36, 0.2); }
        .dark .offcanvas-notif .notif-icon.danger { background: rgba(185, 28, 28, 0.2); color: #f87171; border-color: rgba(248, 113, 113, 0.2); }
        .dark .offcanvas-notif .notif-icon.info { background: rgba(29, 78, 216, 0.2); color: #60a5fa; border-color: rgba(96, 165, 250, 0.2); }

        /* ===== LEGACY CSS VARIABLES FOR CONTENT ===== */
        :root {
            --bg-primary: #f3f4f6;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f9fafb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
        }

        .dark {
            --bg-primary: #020617; /* slate-950 */
            --bg-secondary: #0f172a; /* slate-900 */
            --bg-tertiary: #1e293b; /* slate-800 */
            --text-primary: #f1f5f9; /* slate-100 */
            --text-secondary: #94a3b8; /* slate-400 */
            --border-color: #334155; /* slate-700 */
        }

        /* ===== CONTENT ELEMENTS ===== */
        .card {
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 20px;
            transition: background 0.3s, border-color 0.3s;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            transition: all 0.2s ease;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .stat-sub {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        .stat-card.blue .stat-value { color: #3b82f6; }
        .stat-card.green .stat-value { color: #10b981; }
        .stat-card.amber .stat-value { color: #f59e0b; }
        .stat-card.red .stat-value { color: #ef4444; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-header h2 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* TABLES */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: block;
            width: 100%;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            padding: 12px 16px;
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            white-space: nowrap;
        }

        tbody tr:hover td {
            background: var(--bg-tertiary);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* Ensure table background follows theme */
        .dark table, .dark tr, .dark td {
            background-color: transparent !important;
        }
        .dark .card table {
            background-color: var(--bg-secondary);
        }

        /* BADGES */
        .badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 99px;
            line-height: 1;
        }

        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-amber, .badge-yellow { background: #fef3c7; color: #b45309; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .badge-gray { background: #f3f4f6; color: #4b5563; }
        .badge-purple { background: #f3e8ff; color: #6b21a8; }

        html.dark .badge-blue { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
        html.dark .badge-green { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }
        html.dark .badge-amber, html.dark .badge-yellow { background: rgba(245, 158, 11, 0.2); color: #fcd34d; }
        html.dark .badge-red { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
        html.dark .badge-gray { background: #334155; color: #cbd5e1; }
        html.dark .badge-purple { background: rgba(168, 85, 247, 0.2); color: #d8b4fe; }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn:hover {
            background: var(--bg-tertiary);
        }

        .btn-primary {
            background: #2b5fbe;
            color: #fff;
            border-color: #2b5fbe;
        }

        .btn-primary:hover {
            background: #1e3a5f;
            color: #fff;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .btn-success {
            background: #10b981;
            color: #fff;
            border-color: #10b981;
        }

        .btn-danger {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }

        /* FORMS & INPUTS (fallback) */
        .form-control, .form-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 13px;
        }
        .form-control:focus, .form-select:focus {
            outline: 2px solid #3b82f6;
            outline-offset: -1px;
        }

        @media (max-width: 1024px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .dashboard-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .stat-grid { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
</head>

<body class="h-screen overflow-hidden bg-slate-100 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-200 flex transition-colors duration-300">

{{-- ============================================================
     SIDEBAR
============================================================ --}}
<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 w-[240px] flex flex-col bg-navy-600 dark:bg-navy-800 z-40
           -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0 h-screen">

    {{-- Logo --}}
    <div class="px-4 py-3 border-b border-white/10 shrink-0 logo-shimmer">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/BP_SUML2.png') }}" alt="Logo" class="h-10 w-auto object-contain drop-shadow-md">
        </a>
    </div>

    {{-- Navigation --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

        {{-- UTAMA --}}
        <p class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Utama</p>

        <a href="{{ url('/?home=1') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white">
            <i class="bi bi-globe text-base w-5 text-center shrink-0"></i>
            <span>Beranda</span>
        </a>

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white
                  {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
            <i class="bi bi-speedometer2 text-base w-5 text-center shrink-0"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.surat.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white
                  {{ request()->routeIs('admin.surat.index') ? 'nav-active' : '' }}">
            <i class="bi bi-envelope text-base w-5 text-center shrink-0"></i>
            <span class="flex-1">Antrian Surat</span>
            <span id="sidebar-antrian-badge-container">
                @if($antrianCount ?? 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none min-w-[18px] text-center">{{ $antrianCount }}</span>
                @endif
            </span>
        </a>

        {{-- DATA SURAT --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Data Surat</p>

        {{-- Dropdown: Tabel Data Surat --}}
        <div class="dropdown-group {{ request()->routeIs('admin.surat.masuk') || request()->routeIs('admin.surat.proses') || request()->routeIs('admin.surat.selesai') || request()->routeIs('admin.surat.revisi') ? 'open' : '' }}">
            <button type="button" onclick="this.closest('.dropdown-group').classList.toggle('open')"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                       border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white text-left">
                <i class="bi bi-folder2-open text-base w-5 text-center shrink-0"></i>
                <span class="flex-1">Tabel Data Surat</span>
                <i class="bi bi-chevron-down text-[11px] text-white/40 chevron-icon shrink-0"></i>
            </button>
            <div class="dropdown-list pl-3 mt-0.5 space-y-0.5">
                <a href="{{ route('admin.surat.masuk') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.surat.masuk') ? 'nav-active' : '' }}">
                    <i class="bi bi-inbox text-sm w-4 text-center shrink-0"></i> Surat Masuk
                </a>
                <a href="{{ route('admin.surat.proses') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.surat.proses') ? 'nav-active' : '' }}">
                    <i class="bi bi-arrow-repeat text-sm w-4 text-center shrink-0"></i> Surat Diproses
                </a>
                <a href="{{ route('admin.surat.selesai') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.surat.selesai') ? 'nav-active' : '' }}">
                    <i class="bi bi-check-circle text-sm w-4 text-center shrink-0"></i> Surat Selesai
                </a>
                <a href="{{ route('admin.surat.revisi') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.surat.revisi') ? 'nav-active' : '' }}">
                    <i class="bi bi-exclamation-triangle text-sm w-4 text-center shrink-0"></i> Perlu Revisi
                </a>
            </div>
        </div>

        {{-- Dropdown: Laporan --}}
        <div class="dropdown-group {{ request()->routeIs('admin.laporan.*') || request()->routeIs('admin.riwayat.*') ? 'open' : '' }}">
            <button type="button" onclick="this.closest('.dropdown-group').classList.toggle('open')"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                       border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white text-left">
                <i class="bi bi-bar-chart-line text-base w-5 text-center shrink-0"></i>
                <span class="flex-1">Laporan</span>
                <i class="bi bi-chevron-down text-[11px] text-white/40 chevron-icon shrink-0"></i>
            </button>
            <div class="dropdown-list pl-3 mt-0.5 space-y-0.5">
                <a href="{{ route('admin.laporan.index') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.laporan.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph text-sm w-4 text-center shrink-0"></i> Rekap Bulanan
                </a>
                <a href="{{ route('admin.riwayat.index') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.riwayat.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-clock-history text-sm w-4 text-center shrink-0"></i> Riwayat Pemrosesan
                </a>
            </div>
        </div>

        {{-- KOMUNIKASI --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Komunikasi</p>

        <a href="{{ route('admin.notifikasi.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white
                  {{ request()->routeIs('admin.notifikasi.*') ? 'nav-active' : '' }}">
            <i class="bi bi-bell text-base w-5 text-center shrink-0"></i>
            <span class="flex-1">Notifikasi</span>
            <span id="sidebar-notif-badge-container">
                @php $notifCount = Auth::user()->unreadNotifications()->count(); @endphp
                @if($notifCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none min-w-[18px] text-center animate-pulse">{{ $notifCount }}</span>
                @endif
            </span>
        </a>

        <a href="{{ route('admin.aspirasi.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white
                  {{ request()->routeIs('admin.aspirasi.*') ? 'nav-active' : '' }}">
            <i class="bi bi-chat-heart text-base w-5 text-center shrink-0"></i>
            <span>Kotak Aspirasi</span>
        </a>

        {{-- SISTEM --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Sistem</p>

        {{-- Dropdown: Pengaturan --}}
        <div class="dropdown-group {{ request()->routeIs('admin.template.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.file.*') || request()->routeIs('admin.logs.*') ? 'open' : '' }}">
            <button type="button" onclick="this.closest('.dropdown-group').classList.toggle('open')"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                       border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white text-left">
                <i class="bi bi-gear text-base w-5 text-center shrink-0"></i>
                <span class="flex-1">Pengaturan</span>
                <i class="bi bi-chevron-down text-[11px] text-white/40 chevron-icon shrink-0"></i>
            </button>
            <div class="dropdown-list pl-3 mt-0.5 space-y-0.5">
                <a href="{{ route('admin.template.index') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.template.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-file-earmark-text text-sm w-4 text-center shrink-0"></i> Template Surat
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.users.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-people text-sm w-4 text-center shrink-0"></i> Data Pegawai
                </a>
                <a href="{{ route('admin.file.index') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.file.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-hdd text-sm w-4 text-center shrink-0"></i> File Surat
                </a>
                <a href="{{ route('admin.logs.index') }}"
                   class="flex items-center gap-3 pl-5 pr-3 py-2 rounded-xl text-[12.5px] font-medium text-white/55
                          border-l-2 border-transparent transition-all hover:bg-white/8 hover:text-white
                          {{ request()->routeIs('admin.logs.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-journal-text text-sm w-4 text-center shrink-0"></i> System Logs
                </a>
            </div>
        </div>

        <a href="{{ route('admin.chart.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white
                  {{ request()->routeIs('admin.chart.*') ? 'nav-active' : '' }}">
            <i class="bi bi-bar-chart text-base w-5 text-center shrink-0"></i>
            <span>Statistik & Grafik</span>
        </a>

        {{-- BANTUAN --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Bantuan</p>
        <a href="{{ route('admin.faq.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-white/65
                  border-l-2 border-transparent transition-all duration-150 hover:bg-white/8 hover:text-white
                  {{ request()->routeIs('admin.faq.*') ? 'nav-active' : '' }}">
            <i class="bi bi-question-circle text-base w-5 text-center shrink-0"></i>
            <span>FAQ & Panduan</span>
        </a>

        <div class="pb-4"></div>
    </nav>

    {{-- User Footer --}}
    <div class="shrink-0 border-t border-white/10 p-3">
        <div class="flex items-center gap-3 px-2 py-2.5 rounded-xl hover:bg-white/8 transition-colors cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center text-white text-xs font-bold shrink-0 overflow-hidden">
                @if(Auth::user()->profile_photo)
                    <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @endif
            </div>
            <div class="min-w-0">
                <div class="text-white text-xs font-semibold truncate leading-tight">{{ Auth::user()->name }}</div>
                <div class="text-white/40 text-[10px] mt-0.5 leading-none">{{ Auth::user()->getRoleLabel() }}</div>
            </div>
            <div class="ml-auto shrink-0">
                <div class="w-2 h-2 rounded-full bg-emerald-400 ring-2 ring-emerald-400/30"></div>
            </div>
        </div>
    </div>
</aside>

{{-- Mobile Backdrop --}}
<div id="mobile-backdrop" onclick="closeSidebar()"></div>

{{-- ============================================================
     MAIN AREA
============================================================ --}}
<div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">

    {{-- ===== TOPBAR ===== --}}
    <header id="topbar"
        class="sticky top-0 z-30 h-16 px-4 lg:px-10 flex items-center gap-6
               bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800/80
               transition-colors duration-300">

        {{-- Mobile Toggle --}}
        <button id="sidebar-toggle" onclick="openSidebar()"
            class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
            <i class="bi bi-list text-xl"></i>
        </button>

        {{-- Page Title --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-[14px] lg:text-[20px] font-semibold text-slate-800 dark:text-slate-100 truncate">
                {{ $title ?? 'Dashboard' }}
            </h1>
        </div>

        {{-- Right Controls --}}
        <div class="flex items-center gap-2 shrink-0">

            {{-- Dark Mode --}}
            <button onclick="toggleDarkMode()" id="dark-mode-btn"
                class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 dark:text-amber-400
                       hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
                <i class="bi bi-moon-stars text-[15px]" id="dark-mode-icon"></i>
            </button>

            {{-- Notification Bell --}}
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNotif" aria-controls="offcanvasNotif"
                class="relative w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 dark:text-slate-400
                       hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="bi bi-bell text-[15px]"></i>
                <span id="topbar-notif-badge-container">
                    @php $notifCount = Auth::user()->unreadNotifications()->count(); @endphp
                    @if($notifCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900 animate-pulse"></span>
                    @endif
                </span>
            </button>

            {{-- Divider --}}
            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            {{-- User Dropdown --}}
            <div class="relative" id="user-dropdown-wrap">
                <button onclick="toggleUserDropdown()" id="user-avatar-btn"
                    class="flex items-center gap-3 pl-3 pr-5 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-navy-600 text-white flex items-center justify-center text-[13px] font-bold shrink-0 overflow-hidden ring-2 ring-navy-300/30">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <span class="text-[14px] font-medium text-slate-700 dark:text-slate-200 hidden sm:block max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down text-[11px] text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors hidden sm:block"></i>
                </button>

                {{-- Dropdown Panel --}}
                <div id="user-dropdown"
                    style="background: var(--bg-secondary); border-color: var(--border-color);"
                    class="hidden absolute right-0 top-[calc(100%+8px)] w-[230px]
                           border rounded-2xl shadow-xl overflow-hidden z-50
                           animate-fade-in">
                    <div class="px-4 py-3 border-b flex items-center gap-3" style="border-color: var(--border-color);">
                        <div class="w-9 h-9 rounded-full overflow-hidden flex-shrink-0 bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-xs text-slate-500">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="text-[13px] font-semibold truncate" style="color: var(--text-primary);">{{ Auth::user()->name }}</div>
                            <div class="text-[11px] mt-0.5 truncate" style="color: var(--text-secondary);">{{ Auth::user()->email ?? 'Admin' }}</div>
                        </div>
                    </div>
                    <div class="py-1.5">
                        <a href="{{ route('profile.edit') }}"
                            style="color: var(--text-primary);"
                            class="flex items-center gap-3 px-4 py-2.5 text-[13px]
                                   hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <i class="bi bi-person-circle text-slate-400 text-base w-4 text-center"></i>
                            Profil Saya
                        </a>
                    </div>
                    {{-- Switch Account Section --}}
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-1.5 pb-0.5">
                        <div class="px-4 py-1" style="font-size:9px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.1em;">
                            <i class="bi bi-arrow-left-right mr-1"></i> Beralih Akun
                        </div>
                        <div id="admin-saved-accounts-list">{{-- Diisi oleh JS --}}</div>
                        <a href="#" onclick="switchToNewAccount(event)"
                            class="flex items-center gap-3 px-4 py-2 text-[12px] font-medium
                                   hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors"
                            style="color:#2563eb;">
                            <i class="bi bi-plus-circle text-base w-4 text-center"></i>
                            Tambah Akun Lain
                        </a>
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800 py-1.5">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); logoutCurrentUser();"
                            class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-red-600 dark:text-red-400
                                   hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            <i class="bi bi-box-arrow-right text-base w-4 text-center"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if (session('success'))
        <div class="mx-4 lg:mx-6 mt-4 flex items-start gap-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-xl px-4 py-3 toast-enter">
            <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5">
                <i class="bi bi-check text-emerald-600 dark:text-emerald-400 text-xs font-bold"></i>
            </div>
            <p class="text-[13px] text-emerald-700 dark:text-emerald-300 font-medium leading-snug">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="ml-auto shrink-0 text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300 transition-colors text-sm leading-none mt-0.5">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="mx-4 lg:mx-6 mt-4 flex items-start gap-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl px-4 py-3 toast-enter">
            <div class="w-5 h-5 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center shrink-0 mt-0.5">
                <i class="bi bi-x text-red-600 dark:text-red-400 text-xs font-bold"></i>
            </div>
            <p class="text-[13px] text-red-700 dark:text-red-300 font-medium leading-snug">{{ session('error') }}</p>
            <button onclick="this.parentElement.remove()" class="ml-auto shrink-0 text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors text-sm leading-none mt-0.5">&times;</button>
        </div>
    @endif

    {{-- ===== CONTENT ===== --}}
    <main class="flex-1 p-4 lg:p-6">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t border-slate-200/60 dark:border-slate-800/60 py-4 px-6">
        <p class="text-center text-[12px] text-slate-500 dark:text-slate-400">
            &copy; {{ date('Y') }} Balai Pengelolaan SUML &mdash; RI. All rights reserved.
        </p>
    </footer>

</div>

{{-- Logout Form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

{{-- ============================================================
     SCRIPTS
============================================================ --}}
<script>
    // ===== FUNGSI GLOBAL =====
    function updateDarkIcon() {
        const icon = document.getElementById('dark-mode-icon');
        if (!icon) return;
        const isDark = document.documentElement.classList.contains('dark');
        icon.className = isDark ? 'bi bi-sun text-[15px]' : 'bi bi-moon-stars text-[15px]';
    }

    function toggleDarkMode() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', isDark);
        updateDarkIcon();
    }

    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('mobile-backdrop').classList.add('show');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('mobile-backdrop').classList.remove('show');
    }

    function toggleUserDropdown() {
        const menu = document.getElementById('user-dropdown');
        if (menu) menu.classList.toggle('hidden');
    }

    // ===== ACCOUNT SWITCHER (Admin - Token-Based Instant Switch) =====
    const CURRENT_USER = {
        id:           {{ Auth::id() }},
        name:         '{{ addslashes(Auth::user()->name) }}',
        email:        '{{ addslashes(Auth::user()->email) }}',
        initials:     '{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}',
        role:         '{{ Auth::user()->getRoleLabel() }}',
        photo:        '{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : "" }}',
        switch_token: '{{ session("switch_token_raw", "") }}'
    };
    const SWITCH_URL  = '{{ route("auth.switch") }}';
    const CSRF_TOKEN  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const STORAGE_KEY = 'bpsuml_saved_accounts';

    function getSavedAccounts() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch(e) { return []; }
    }

    function saveCurrentAccount() {
        if (!CURRENT_USER.switch_token) return;
        let accounts = getSavedAccounts();
        const idx = accounts.findIndex(a => a.id === CURRENT_USER.id);
        const entry = { ...CURRENT_USER, savedAt: Date.now() };
        if (idx >= 0) { accounts[idx] = entry; } else { accounts.push(entry); }
        if (accounts.length > 5) accounts = accounts.slice(-5);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
    }

    function renderSavedAccounts() {
        const accounts = getSavedAccounts().filter(a => a.id !== CURRENT_USER.id);
        const container = document.getElementById('admin-saved-accounts-list');
        if (!container) return;

        if (accounts.length === 0) {
            container.innerHTML = `<div style="padding:2px 16px 4px; font-size:11px; color:var(--text-secondary); font-style:italic;">Belum ada akun tersimpan lain</div>`;
            return;
        }
        container.innerHTML = accounts.map(acc => `
            <a href="#" id="switch-btn-${acc.id}"
               onclick="doSwitchAccount(event, ${acc.id}, '${acc.switch_token}')"
               class="flex items-center gap-2 px-4 py-2 text-[12px] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
               style="text-decoration:none;">
                <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;overflow:hidden;">
                    ${acc.photo ? `<img src="${acc.photo}" style="width:100%;height:100%;object-fit:cover;">` : acc.initials}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:12px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${acc.name}</div>
                    <div style="font-size:10px;color:var(--text-secondary);">${acc.role || acc.email}</div>
                </div>
                <i class="bi bi-arrow-right-circle" style="color:#2563eb;font-size:13px;flex-shrink:0;"></i>
            </a>
        `).join('');
    }

    function doSwitchAccount(e, userId, token) {
        e.preventDefault();
        if (!token) {
            sessionStorage.setItem('bpsuml_switch_to_email',
                getSavedAccounts().find(a => a.id === userId)?.email || '');
            document.getElementById('logout-form').submit();
            return;
        }

        const btn = document.getElementById('switch-btn-' + userId);
        if (btn) {
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = `<span style="display:inline-flex;align-items:center;gap:8px;padding:0 16px;font-size:12px;color:var(--text-secondary);"><svg style="width:14px;height:14px;animation:spin 1s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Beralih...</span>`;
        }

        fetch(SWITCH_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ user_id: userId, switch_token: token }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                let accounts = getSavedAccounts();
                const idx = accounts.findIndex(a => a.id === data.user_id);
                if (idx >= 0) {
                    accounts[idx].switch_token = data.new_token;
                    accounts[idx].photo = data.photo;
                } else {
                    accounts.push({ id: data.user_id, name: data.name, email: data.email,
                                     initials: data.initials, role: data.role, photo: data.photo, switch_token: data.new_token });
                }
                localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
                window.location.href = data.redirect;
            } else {
                alert('Gagal beralih akun: ' + data.message);
                renderSavedAccounts();
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan. Coba lagi.');
            renderSavedAccounts();
        });
    }

    function switchToNewAccount(e) {
        e.preventDefault();
        document.getElementById('logout-form').submit();
    }

    function logoutCurrentUser() {
        let accounts = getSavedAccounts().filter(a => a.id !== CURRENT_USER.id);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
        document.getElementById('logout-form').submit();
    }

    // ===== INISIALISASI SAAT LOAD =====
    (function() {
        // Dark Mode Init
        const isDark = localStorage.getItem('darkMode') === 'true';
        if (isDark) document.documentElement.classList.add('dark');

        document.addEventListener('DOMContentLoaded', () => {
            updateDarkIcon();

            // Close dropdown on outside click
            document.addEventListener('click', function(e) {
                const wrap = document.getElementById('user-dropdown-wrap');
                const menu = document.getElementById('user-dropdown');
                if (wrap && menu && !wrap.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });

            // Dismiss flash messages
            setTimeout(() => {
                document.querySelectorAll('.toast-enter').forEach(el => {
                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-6px)';
                    setTimeout(() => el.remove(), 400);
                });
            }, 4500);

            // Account switcher init
            saveCurrentAccount();
            renderSavedAccounts();
        });

        // Keydown listener
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    })();
</script>

{{-- Form untuk Tandai Semua Dibaca --}}
<form id="readall-form" action="{{ route('notif.readAll') }}" method="POST" class="d-none">@csrf</form>

{{-- ===== OFFCANVAS NOTIFIKASI ===== --}}
<div class="offcanvas offcanvas-end offcanvas-notif" tabindex="-1" id="offcanvasNotif" aria-labelledby="offcanvasNotifLabel">
    <div class="offcanvas-header d-flex align-items-center justify-content-between">
        <h5 class="offcanvas-title fw-bold" id="offcanvasNotifLabel" style="font-size: 16px; color: var(--text-primary);">
            <i class="bi bi-bell me-2 text-primary"></i> Notifikasi
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="px-4 py-2 border-bottom d-flex align-items-center justify-content-between bg-slate-50 dark:bg-slate-800/50" style="border-color: var(--border-color) !important;">
        <span style="font-size: 11px; color: var(--text-secondary);">{{ auth()->user()->notifications->count() }} Notifikasi Terakhir</span>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <a href="#" onclick="event.preventDefault(); document.getElementById('readall-form').submit();" 
               class="text-decoration-none fw-bold" style="font-size: 11px; color: #3b82f6;">
                Tandai semua dibaca
            </a>
        @endif
    </div>
    <div class="offcanvas-body p-0" style="overflow-y: auto; overflow-x: hidden;">
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
                <i class="bi bi-bell-slash text-slate-300 dark:text-slate-600" style="font-size: 40px; opacity: 0.5;"></i>
                <p class="mt-3 small" style="color: var(--text-secondary);">Belum ada notifikasi baru untuk Anda.</p>
            </div>
        @endforelse
    </div>
    <div class="p-3 border-top text-center bg-slate-50 dark:bg-slate-800/50" style="border-color: var(--border-color) !important;">
        <a href="{{ route('admin.notifikasi.index') }}" class="text-decoration-none small fw-bold" style="color: #3b82f6;">
            Lihat Semua Notifikasi <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>

@stack('scripts')
</body>
</html>