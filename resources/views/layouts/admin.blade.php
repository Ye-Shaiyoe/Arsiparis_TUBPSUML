<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Surat Metrologi</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        :root {
            --bg-primary: #f3f4f6;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f9fafb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --sidebar-bg: #1e3a5f;
            --sidebar-text: rgba(255,255,255,0.65);
            --topbar-bg: #ffffff;
        }

        html.dark-mode {
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --bg-tertiary: #374151;
            --text-primary: #f3f4f6;
            --text-secondary: #d1d5db;
            --border-color: #4b5563;
            --sidebar-bg: #0f172a;
            --sidebar-text: rgba(243,244,246,0.65);
            --topbar-bg: #1f2937;
        }

        /* ===== LAYOUT ===== */
        body {
            display: flex;
            min-height: 100vh;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Figtree', sans-serif;
            transition: background 0.3s, color 0.3s;
        }

        /* SIDEBAR */
        #sidebar {
            width: 240px;
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            overflow-y: auto;
            transition: width 0.2s, background 0.3s;
            /* Hide scrollbar for Firefox */
            scrollbar-width: none;
            /* Hide scrollbar for IE and Edge */
            -ms-overflow-style: none;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        #sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-logo {
            padding: 20px 16px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-logo span {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .sidebar-logo small {
            display: block;
            font-size: 10px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 2px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 12px 0;
        }

        .menu-label {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 10px 16px 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left-color: #60a5fa;
        }

        .menu-icon {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        /* SIDEBAR DROPDOWN */
        .menu-dropdown-btn {
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            justify-content: flex-start;
        }

        .dropdown-chevron {
            margin-left: auto;
            transition: transform 0.2s;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        .menu-dropdown.is-active .menu-dropdown-btn .dropdown-chevron {
            transform: rotate(180deg);
        }

        .menu-dropdown-list {
            display: none;
            background: rgba(0, 0, 0, 0.15);
        }

        .menu-dropdown.is-active .menu-dropdown-list {
            display: block;
        }

        .menu-dropdown-list .menu-item {
            padding-left: 44px;
            font-size: 12.5px;
        }

        .sidebar-user {
            padding: 12px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 12px;
            color: rgba(255, 255, 255, 0.55);
        }

        .sidebar-user strong {
            display: block;
            color: #fff;
            font-size: 13px;
        }

        /* MAIN AREA */
        #main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* TOPBAR */
        #topbar {
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 10;
            transition: background 0.3s, border-color 0.3s;
        }

        .sidebar-toggle {
            display: none;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            transition: color 0.3s;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-badge {
            position: relative;
            font-size: 18px;
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.3s;
        }

        .notif-dot {
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid var(--topbar-bg);
            transition: border-color 0.3s;
        }

        .topbar-avatar {
            width: 35px;
            height: 35px;
            min-width: 35px;
            min-height: 35px;
            border-radius: 50%;
            background: var(--sidebar-bg);
            color: #fff;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition: background 0.15s, box-shadow 0.15s;
        }

        .topbar-avatar:hover {
            background: #16304f;
        }

        .topbar-avatar:focus-visible {
            outline: 2px solid #60a5fa;
            outline-offset: 2px;
        }

        .dropdown {
            position: relative;
            flex-shrink: 0;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 6px);
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            min-width: 180px;
            z-index: 99;
            overflow: hidden;
            transition: background 0.3s, border-color 0.3s;
        }

        .dropdown.is-open .dropdown-menu {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 11px 14px;
            font-size: 13px;
            color: var(--text-primary);
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
        }

        .dropdown-menu a:hover {
            background: var(--bg-tertiary);
        }

        .dropdown-menu hr {
            border-color: var(--border-color);
            margin: 0;
            transition: border-color 0.3s;
        }

        /* CONTENT */
        #content {
            padding: 24px;
            flex: 1;
        }

        /* CARDS */
        .card {
            background: var(--bg-secondary);
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 20px;
            transition: background 0.3s, border-color 0.3s;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 16px 20px;
            transition: background 0.3s, border-color 0.3s;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 4px;
            transition: color 0.3s;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            transition: color 0.3s;
        }

        .stat-sub {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 4px;
            transition: color 0.3s;
        }

        .stat-card.blue .stat-value {
            color: #1d4ed8;
        }

        .stat-card.green .stat-value {
            color: #15803d;
        }

        .stat-card.amber .stat-value {
            color: #b45309;
        }

        .stat-card.red .stat-value {
            color: #b91c1c;
        }

        /* TABLES */
        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            padding: 10px 12px;
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s, color 0.3s, border-color 0.3s;
        }

        tbody td {
            padding: 11px 12px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: border-color 0.3s, color 0.3s;
        }

        tbody tr:hover td {
            background: var(--bg-tertiary);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #6b7280;
        }

        .badge-purple {
            background: #ede9fe;
            color: #6d28d9;
        }

        html.dark-mode .badge-blue {
            background: rgba(29, 78, 216, 0.2);
            color: #93c5fd;
        }

        html.dark-mode .badge-green {
            background: rgba(21, 128, 61, 0.2);
            color: #86efac;
        }

        html.dark-mode .badge-amber {
            background: rgba(180, 83, 9, 0.2);
            color: #fcd34d;
        }

        html.dark-mode .badge-red {
            background: rgba(185, 28, 28, 0.2);
            color: #fca5a5;
        }

        html.dark-mode .badge-gray {
            background: #374151;
            color: #d1d5db;
        }

        html.dark-mode .badge-purple {
            background: rgba(109, 40, 217, 0.2);
            color: #d8b4fe;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn:hover {
            background: var(--bg-tertiary);
        }

        .btn-primary {
            background: #1e3a5f;
            color: #fff;
            border-color: #1e3a5f;
        }

        .btn-primary:hover {
            background: #16304f;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-success {
            background: #15803d;
            color: #fff;
            border-color: #15803d;
        }

        .btn-danger {
            background: #b91c1c;
            color: #fff;
            border-color: #b91c1c;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 6px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 16px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .dark-mode-toggle:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        html.dark-mode .dark-mode-toggle {
            color: #fbbf24;
        }

        /* SLA bar */
        .sla-bar {
            height: 4px;
            background: var(--border-color);
            transition: background 0.3s;
            border-radius: 99px;
            overflow: hidden;
            width: 80px;
        }

        .sla-fill {
            height: 100%;
            border-radius: 99px;
        }

        /* Progress */
        .progress-bar {
            height: 6px;
            background: var(--border-color);
            border-radius: 99px;
            overflow: hidden;
            transition: background 0.3s;
        }

        .progress-fill {
            height: 100%;
            background: #1e3a5f;
            border-radius: 99px;
        }

        /* Section header */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-header h2 {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            transition: color 0.3s;
        }

        .section-header small {
            font-size: 12px;
            color: var(--text-secondary);
            transition: color 0.3s;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            /* Show sidebar toggle */
            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                min-width: 40px;
                background: none;
                border: none;
                color: var(--text-secondary);
                cursor: pointer;
                font-size: 20px;
                padding: 0;
                margin: 0;
                transition: color 0.3s;
            }

            .sidebar-toggle:active {
                color: var(--text-primary);
            }

            /* Hide sidebar on mobile, show toggle button */
            #sidebar {
                width: 260px;
                height: auto;
                position: fixed;
                left: 0;
                top: 56px;
                bottom: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 40;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                overflow-y: auto;
            }

            #sidebar.is-open {
                transform: translateX(0);
            }

            /* Sidebar backdrop */
            #sidebar-backdrop {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 35;
            }

            #sidebar.is-open~#sidebar-backdrop {
                display: block;
            }

            #main {
                width: 100%;
            }

            #topbar {
                padding: 0 12px;
                height: 56px;
            }

            .topbar-title {
                font-size: 14px;
                flex: 1;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .topbar-right {
                gap: 8px;
            }

            #content {
                padding: 12px;
                overflow-x: hidden;
            }

            .card {
                padding: 14px;
                border-radius: 8px;
            }

            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .stat-card {
                padding: 12px 14px;
            }

            .stat-label {
                font-size: 11px;
            }

            .stat-value {
                font-size: 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            thead th {
                padding: 8px 10px;
                font-size: 10px;
            }

            tbody td {
                padding: 8px 10px;
            }

            .btn {
                padding: 6px 10px;
                font-size: 11px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 10px;
            }

            .section-header {
                flex-direction: column;
                gap: 8px;
            }

            .section-header h2 {
                font-size: 14px;
            }

            .section-header small {
                font-size: 11px;
            }

            .badge {
                font-size: 9px;
                padding: 2px 6px;
            }
        }

        @media (max-width: 480px) {
            #topbar {
                padding: 0 8px;
                height: 52px;
            }

            .sidebar-toggle {
                width: 36px;
                height: 36px;
            }

            .topbar-title {
                font-size: 12px;
            }

            #content {
                padding: 8px;
            }

            .stat-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .card {
                padding: 10px;
            }

            table {
                font-size: 11px;
            }

            thead th {
                padding: 6px 8px;
            }

            tbody td {
                padding: 6px 8px;
            }

            .btn {
                padding: 5px 8px;
                font-size: 10px;
            }
        }

        .notif-dot {
            color: rgb(0, 0, 0);
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- ============ SIDEBAR ============ --}}
    <aside id="sidebar">
        <div class="sidebar-logo" style="text-align: center;">
            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">
                <img src="{{ asset('images/BP_SUML2.png') }}" alt="Logo Balai Pengelolaan SUML"
                    style="max-width: 100%; max-height: 60px; height: auto;">
            </a>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Utama</div>
            <a href="{{ route('admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="menu-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
            </a>
            <a href="{{ route('admin.surat.index') }}"
                class="menu-item {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="bi bi-envelope"></i></span> Antrian Surat
                @if($antrianCount ?? 0)
                    <span class="badge badge-red" style="margin-left:auto;font-size:10px;">{{ $antrianCount }}</span>
                @endif
            </a>

            <div class="menu-dropdown {{ request()->routeIs('admin.laporan.*') || request()->routeIs('admin.riwayat.*') ? 'is-active' : '' }}">
                <button type="button" class="menu-item menu-dropdown-btn" onclick="this.parentElement.classList.toggle('is-active')">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="menu-icon"><i class="bi bi-folder2-open"></i></span> Laporan
                    </div>
                    <span class="dropdown-chevron"><i class="bi bi-chevron-down"></i></span>
                </button>
                <div class="menu-dropdown-list">
                    <a href="{{ route('admin.laporan.index') }}"
                        class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-file-earmark-text"></i></span> Rekap Bulanan
                    </a>
                    <a href="{{ route('admin.riwayat.index') }}"
                        class="menu-item {{ request()->routeIs('admin.riwayat.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-clock-history"></i></span> Riwayat Pemrosesan
                    </a>
                </div>
            </div> 

            <div class="menu-label">Komunikasi</div>
            <a href="{{ route('admin.notifikasi.index') }}"
                class="menu-item {{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="bi bi-bell"></i></span> Notifikasi
                @php $notifCount = Auth::user()->unreadNotifications()->count(); @endphp
                @if($notifCount > 0)
                    <span class="badge badge-red" style="margin-left:auto;font-size:10px;">{{ $notifCount }}</span>
                @endif
            </a>

            <div class="menu-dropdown {{ request()->routeIs('admin.template.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.logs.*') ? 'is-active' : '' }}">
                <button type="button" class="menu-item menu-dropdown-btn" onclick="this.parentElement.classList.toggle('is-active')">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="menu-icon"><i class="bi bi-gear"></i></span> Pengaturan
                    </div>
                    <span class="dropdown-chevron"><i class="bi bi-chevron-down"></i></span>
                </button>
                <div class="menu-dropdown-list">
                    <a href="{{ route('admin.template.index') }}"
                        class="menu-item {{ request()->routeIs('admin.template.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-file-earmark-text"></i></span> Template Surat
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-people"></i></span> Data Pegawai
                    </a>
                    <a href="{{ route('admin.logs.index') }}"
                        class="menu-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-journal-text"></i></span> System Logs
                    </a>
                </div>
            </div>            
            <div class="menu-label">Chart</div>
            <a href="{{ route('admin.chart.index') }}"
                class="menu-item {{ request()->routeIs('admin.chart.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="bi bi-bar-chart"></i></span> Statistik & Grafik
            </a>
        </nav>

        <div class="sidebar-user">
            <strong>{{ Auth::user()->name }}</strong>
            {{ Auth::user()->getRoleLabel() }}
        </div>
    </aside>

    <div id="main">

        <header id="topbar">
            <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                ☰
            </button>
            <div class="topbar-title">{{ $title ?? 'Dashboard' }}</div>
            <div class="topbar-right">
                <button type="button" class="dark-mode-toggle" id="dark-mode-toggle" title="Toggle Dark Mode" onclick="toggleDarkMode()">
                    <i class="bi bi-moon-stars" id="dark-mode-icon"></i>
                </button>
                <a href="{{ route('admin.notifikasi.index') }}" class="topbar-badge" style="text-decoration:none;"
                    title="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-bell" viewBox="0 0 16 16" style="color: currentColor;">
                        <path
                            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                    </svg>
                    @php $notifCount = Auth::user()->unreadNotifications()->count(); @endphp
                    @if($notifCount > 0)
                        <span class="notif-dot"></span>
                    @endif
                </a>
                <div class="dropdown ms-2">
                    <button class="topbar-avatar" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0; overflow: hidden; border: none; background: transparent;">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; padding: 8px 0; min-width: 240px; z-index: 1050; margin-top: 10px;">
                        <li class="px-3 py-2 mb-1 border-bottom" style="border-color: var(--border-color) !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="topbar-avatar" style="width: 40px; height: 40px; min-width: 40px; font-size: 14px; background: var(--sidebar-bg); padding: 0;">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-bold text-truncate" style="color: var(--text-primary); font-size: 14px;">{{ Auth::user()->name }}</h6>
                                    <small class="text-truncate d-block" style="color: var(--text-secondary); font-size: 12px;">{{ Auth::user()->email ?? 'Admin' }}</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 mt-1 px-3" href="{{ route('profile.edit') }}" style="color: var(--text-primary); font-size: 14px; transition: 0.2s; background-color: transparent;" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                <i class="bi bi-person-circle fs-5" style="color: var(--text-secondary);"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1" style="border-color: var(--border-color);">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size: 14px; transition: 0.2s; background-color: transparent;" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                <i class="bi bi-box-arrow-right fs-5"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div
                style="margin:16px 24px 0; background:#dcfce7; border:1px solid #86efac; border-radius:8px; padding:10px 16px; font-size:13px; color:#15803d;">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                style="margin:16px 24px 0; background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:10px 16px; font-size:13px; color:#b91c1c;">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- CONTENT --}}
        <main id="content">
            @yield('content')
        </main>

        {{-- ===== FOOTER ===== --}}
        <footer class="pb-4 mt-auto">
            <div class="container-fluid text-center">
                <p class="mb-0" style="font-size: 13px; color: var(--text-secondary); opacity: 0.8;">
                    {{ date('Y') }} &copy; 2026 Balai Pengelolaan SUML &mdash; RI. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
        @csrf
    </form>

    <div id="sidebar-backdrop"></div>

    <script>
        // Dark Mode Toggle
        function initDarkMode() {
            const isDark = localStorage.getItem('darkMode') === 'true';
            if (isDark) {
                document.documentElement.classList.add('dark-mode');
                updateDarkModeIcon();
            }
        }

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', isDark);
            updateDarkModeIcon();
        }

        function updateDarkModeIcon() {
            const icon = document.getElementById('dark-mode-icon');
            if (document.documentElement.classList.contains('dark-mode')) {
                icon.classList.remove('bi-moon-stars');
                icon.classList.add('bi-sun');
            } else {
                icon.classList.remove('bi-sun');
                icon.classList.add('bi-moon-stars');
            }
        }

        // Initialize dark mode on page load
        initDarkMode();

        // Mobile menu toggle
        (function () {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (!toggle || !sidebar) return;

            function closeSidebar() {
                sidebar.classList.remove('is-open');
            }

            // Toggle button
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('is-open');
            });

            // Close on backdrop click
            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            // Close on menu item click
            const menuItems = sidebar.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', closeSidebar);
            });

            // Close on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                    closeSidebar();
                }
            });
        })();

        // User menu dropdown
        (function () {
            var root = document.getElementById('user-menu-dropdown');
            var btn = document.getElementById('user-menu-btn');
            if (!root || !btn) return;

            function setOpen(open) {
                root.classList.toggle('is-open', open);
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                setOpen(!root.classList.contains('is-open'));
            });

            document.addEventListener('click', function () {
                setOpen(false);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && root.classList.contains('is-open')) {
                    setOpen(false);
                    btn.focus();
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>