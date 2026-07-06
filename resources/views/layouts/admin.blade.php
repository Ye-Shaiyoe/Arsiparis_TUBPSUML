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
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>

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
                            50:  '#f5f7ff',
                            100: '#ebf0fe',
                            200: '#ced9fd',
                            300: '#a1b6fb',
                            400: '#6d8bf7',
                            500: '#4361ee', /* Modern Electric Blue */
                            600: '#3a50e0',
                            700: '#3041c7',
                            800: '#1e293b', /* Slate 800 for sidebar */
                            900: '#0f172a', /* Slate 900 for sidebar */
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

        /* ===== ACTIVE NAV MODEREN ===== */
        .nav-active {
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.15) 0%, rgba(67, 97, 238, 0.05) 100%);
            color: #4361ee !important;
            font-weight: 700 !important;
            position: relative;
        }

        .nav-active::after {
            content: "";
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 4px;
            background: #4361ee;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px rgba(67, 97, 238, 0.5);
        }

        .sidebar-item {
            margin: 2px 12px;
            padding: 10px 16px;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
            color: #fff;
        }

        .dark .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.03);
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

        /* ===== MINI SIDEBAR ADMIN (Premium YouTube Style) ===== */
        #sidebar.is-mini {
            width: 88px !important;
        }

        #sidebar.is-mini #sidebar-nav {
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }

        #sidebar.is-mini .brand-info,
        #sidebar.is-mini nav p,
        #sidebar.is-mini .sidebar-item span:not(.bg-red-500),
        #sidebar.is-mini .chevron-icon,
        #sidebar.is-mini .shrink-0.p-4 .flex-1,
        #sidebar.is-mini .shrink-0.p-4 .shrink-0:last-child {
            display: none !important;
        }

        /* Enforce absolute positioning for notifications in mini sidebar */
        #sidebar.is-mini .sidebar-item {
            position: relative !important;
        }
        #sidebar.is-mini .sidebar-item #sidebar-antrian-badge-container,
        #sidebar.is-mini .sidebar-item #sidebar-notif-badge-container {
            display: block !important;
            position: absolute !important;
            top: 6px !important;
            right: 12px !important;
            z-index: 10 !important;
        }

        /* Sidebar Brand Mini Adjustments */
        #sidebar.is-mini .admin-sidebar-brand {
            padding: 1.25rem 0.5rem !important;
            display: flex !important;
            justify-content: center !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        #sidebar.is-mini .admin-sidebar-brand .logo-full {
            display: none !important;
        }
        #sidebar.is-mini .admin-sidebar-brand .logo-mini {
            display: block !important;
        }

        /* Premium User Profile Widget in Mini Mode */
        #sidebar.is-mini .shrink-0.p-4 {
            padding: 1rem 0.5rem !important;
            display: flex !important;
            justify-content: center !important;
        }

        #sidebar.is-mini .profile-widget-container {
            padding: 0.5rem !important;
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 16px !important;
            justify-content: center !important;
            width: 56px !important;
            height: 56px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
        }
        #sidebar.is-mini .profile-widget-container:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-2px) scale(1.03) !important;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3) !important;
        }

        #sidebar.is-mini .profile-avatar-wrapper .mini-online-dot {
            display: block !important;
        }
        #sidebar.is-mini .normal-online-dot {
            display: none !important;
        }

        /* Refined Sidebar Items & Dropdown Buttons */
        #sidebar.is-mini .sidebar-item,
        #sidebar.is-mini .dropdown-group button {
            flex-direction: column !important;
            gap: 0.25rem !important;
            padding: 0.75rem 0.25rem !important;
            margin: 6px auto !important;
            width: 72px !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            border-radius: 14px !important;
        }

        #sidebar.is-mini .sidebar-item i {
            font-size: 1.3rem !important;
            margin: 0 !important;
            width: auto !important;
        }

        /* Refined Active State indicator for Mini Mode */
        #sidebar.is-mini .nav-active::after {
            left: 5px !important;
            top: 25% !important;
            bottom: 25% !important;
            width: 3.5px !important;
            border-radius: 99px !important;
            box-shadow: 0 0 12px rgba(67, 97, 238, 0.8) !important;
        }

        /* Vertical Accordion Submenus for Mini Sidebar */
        #sidebar.is-mini .dropdown-list {
            display: none !important;
            padding-left: 0 !important;
            margin-top: 4px !important;
        }

        #sidebar.is-mini .dropdown-group.open .dropdown-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 4px !important;
        }

        #sidebar.is-mini .dropdown-list .sidebar-item {
            width: 48px !important;
            height: 48px !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 2px auto !important;
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px !important;
        }

        #sidebar.is-mini .dropdown-list .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            transform: scale(1.05) !important;
        }

        #sidebar.is-mini .dropdown-list .sidebar-item span {
            display: none !important;
        }

        #sidebar.is-mini .dropdown-list .sidebar-item i {
            font-size: 1.1rem !important;
            margin: 0 !important;
            color: #94a3b8 !important;
        }

        #sidebar.is-mini .dropdown-list .sidebar-item:hover i {
            color: #38bdf8 !important;
        }

        #sidebar.is-mini .dropdown-list .sidebar-item.nav-active {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.2) 0%, rgba(67, 97, 238, 0.05) 100%) !important;
            border: 1px solid rgba(67, 97, 238, 0.4) !important;
        }
        #sidebar.is-mini .dropdown-list .sidebar-item.nav-active i {
            color: #60a5fa !important;
        }
        #sidebar.is-mini .dropdown-list .sidebar-item.nav-active::after {
            display: none !important;
        }

        /* Vertical IT Support Submenu */
        #sidebar.is-mini [x-show="open"] {
            display: flex !important;
            flex-direction: column !important;
            gap: 4px !important;
            position: static !important;
            width: auto !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin-top: 4px !important;
        }

        #sidebar.is-mini [x-show="open"] a {
            width: 48px !important;
            height: 48px !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 2px auto !important;
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px !important;
            transition: all 0.2s !important;
        }

        #sidebar.is-mini [x-show="open"] a:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            transform: scale(1.05) !important;
        }

        #sidebar.is-mini [x-show="open"] a span {
            display: none !important;
        }

        #sidebar.is-mini [x-show="open"] a i {
            font-size: 1.1rem !important;
            margin: 0 !important;
        }

        /* Tooltips for Mini Sidebar */
        #sidebar.is-mini [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: #f1f5f9;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.08);
            animation: fadeIn 0.15s ease-out;
        }

        #sidebar.is-mini .dropdown-group.open [data-tooltip]::after,
        #sidebar.is-mini .relative.open[data-tooltip]::after,
        #sidebar.is-mini .relative[data-tooltip]:focus-within::after {
            display: none !important;
        }

        #sidebar, .flex-1 {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease;
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

        .notif-item { transition: all 0.2s ease; position: relative; }
        .btn-notif-action {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            transition: all 0.2s;
            font-size: 14px;
        }
        .btn-notif-action:hover {
            background: var(--bg-tertiary);
            transform: scale(1.1);
        }
        .notif-item-wrapper:hover .notif-item {
            background: var(--bg-tertiary);
        }
        .pr-12 { padding-right: 3rem !important; }
        .mr-4 { margin-right: 1rem !important; }
        .group:hover .notif-actions { opacity: 1 !important; }

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
<aside id="sidebar" data-turbo-permanent
    class="fixed lg:static inset-y-0 left-0 w-[240px] flex flex-col bg-slate-900 z-40
           -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0 h-screen border-r border-white/5 shadow-2xl">

    {{-- Logo --}}
    <div class="admin-sidebar-brand px-6 py-6 border-b border-white/5 shrink-0 flex items-center justify-center">
        <a href="{{ route('admin.dashboard') }}" data-turbo="false" class="flex items-center justify-center">
            <!-- Full Logo -->
            <div class="p-2 bg-white/5 rounded-xl border border-white/10 logo-full">
                <img src="{{ asset('images/BP_SUML2.png') }}" alt="Logo" class="h-8 w-auto object-contain">
            </div>
            <!-- Mini Logo -->
            <div class="p-2 bg-white/5 rounded-xl border border-white/10 logo-mini hidden">
                <img src="{{ asset('images/metrologi.png') }}" alt="Logo" class="h-8 w-8 object-contain">
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

        {{-- UTAMA --}}
        <p class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Utama</p>

        <a href="{{ url('/?home=1') }}" data-turbo="false" data-tooltip="Beranda Utama"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400 hover:text-white">
            <i class="bi bi-globe text-lg w-5 text-center shrink-0"></i>
            <span>Beranda Utama</span>
        </a>

        <a href="{{ route('admin.dashboard') }}" data-turbo="false" data-tooltip="Dashboard"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
            <i class="bi bi-grid-1x2-fill text-lg w-5 text-center shrink-0"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.surat.index') }}" data-turbo="false" data-tooltip="Antrian Surat"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.surat.index') ? 'nav-active' : '' }}">
            <i class="bi bi-stack text-lg w-5 text-center shrink-0"></i>
            <span class="flex-1">Antrian Surat</span>
            <span id="sidebar-antrian-badge-container">
                @if($antrianCount ?? 0)
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-red-500/20">{{ $antrianCount }}</span>
                @endif
            </span>
        </a>

        {{-- DATA SURAT --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Data Surat</p>

        {{-- Dropdown: Tabel Data Surat --}}
        <div class="dropdown-group {{ request()->routeIs('admin.surat.masuk') || request()->routeIs('admin.surat.proses') || request()->routeIs('admin.surat.selesai') || request()->routeIs('admin.surat.revisi') ? 'open' : '' }}">
            <button type="button" onclick="this.closest('.dropdown-group').classList.toggle('open')" data-tooltip="Tabel Data Surat"
                class="sidebar-item w-[calc(100%-24px)] flex items-center gap-3 text-[13px] font-semibold text-slate-400 text-left">
                <i class="bi bi-folder2-open text-lg w-5 text-center shrink-0"></i>
                <span class="flex-1">Tabel Data Surat</span>
                <i class="bi bi-chevron-down text-[11px] text-white/40 chevron-icon shrink-0"></i>
            </button>
            <div class="dropdown-list pl-3 mt-1 space-y-1">
                <a href="{{ route('admin.surat.masuk') }}" data-tooltip="Surat Masuk"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.surat.masuk') ? 'nav-active' : '' }}">
                    <i class="bi bi-inbox text-base w-5 text-center shrink-0"></i> Surat Masuk
                </a>
                <a href="{{ route('admin.surat.proses') }}" data-tooltip="Surat Diproses"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.surat.proses') ? 'nav-active' : '' }}">
                    <i class="bi bi-arrow-repeat text-base w-5 text-center shrink-0"></i> Surat Diproses
                </a>
                <a href="{{ route('admin.surat.selesai') }}" data-tooltip="Surat Selesai"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.surat.selesai') ? 'nav-active' : '' }}">
                    <i class="bi bi-check-circle text-base w-5 text-center shrink-0"></i> Surat Selesai
                </a>
                <a href="{{ route('admin.surat.revisi') }}" data-tooltip="Perlu Revisi"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.surat.revisi') ? 'nav-active' : '' }}">
                    <i class="bi bi-exclamation-triangle text-base w-5 text-center shrink-0"></i> Perlu Revisi
                </a>
            </div>
        </div>

        {{-- Dropdown: Laporan --}}
        <div class="dropdown-group {{ request()->routeIs('admin.laporan.*') || request()->routeIs('admin.riwayat.*') ? 'open' : '' }}">
            <button type="button" onclick="this.closest('.dropdown-group').classList.toggle('open')" data-tooltip="Laporan & Riwayat"
                class="sidebar-item w-[calc(100%-24px)] flex items-center gap-3 text-[13px] font-semibold text-slate-400 text-left">
                <i class="bi bi-bar-chart-line text-lg w-5 text-center shrink-0"></i>
                <span class="flex-1">Laporan</span>
                <i class="bi bi-chevron-down text-[11px] text-white/40 chevron-icon shrink-0"></i>
            </button>
            <div class="dropdown-list pl-3 mt-1 space-y-1">
                <a href="{{ route('admin.laporan.index') }}" data-tooltip="Rekap Bulanan"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.laporan.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph text-base w-5 text-center shrink-0"></i> Rekap Bulanan
                </a>
                <a href="{{ route('admin.riwayat.index') }}" data-tooltip="Riwayat Pemrosesan"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.riwayat.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-clock-history text-base w-5 text-center shrink-0"></i> Riwayat Pemrosesan
                </a>
                <a href="{{ route('admin.analytics.sla') }}" data-tooltip="Monitoring SLA"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.analytics.sla') ? 'nav-active' : '' }}">
                    <i class="bi bi-lightning-charge text-base w-5 text-center shrink-0"></i> Monitoring SLA
                </a>
            </div>
        </div>

        {{-- KOMUNIKASI --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Komunikasi</p>

        <a href="{{ route('admin.notifikasi.index') }}" data-tooltip="Notifikasi"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.notifikasi.*') ? 'nav-active' : '' }}">
            <i class="bi bi-bell text-lg w-5 text-center shrink-0"></i>
            <span class="flex-1">Notifikasi</span>
            <span id="sidebar-notif-badge-container">
                @php $notifCount = Auth::user()->unreadNotifications()->count(); @endphp
                @if($notifCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-red-500/20 animate-pulse">{{ $notifCount }}</span>
                @endif
            </span>
        </a>

        <a href="{{ route('admin.aspirasi.index') }}" data-tooltip="Kotak Aspirasi"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.aspirasi.*') ? 'nav-active' : '' }}">
            <i class="bi bi-chat-heart text-lg w-5 text-center shrink-0"></i>
            <span>Kotak Aspirasi</span>
        </a>

        {{-- SISTEM --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Sistem</p>

        {{-- Dropdown: Pengaturan --}}
        <div class="dropdown-group {{ request()->routeIs('admin.template.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.file.*') || request()->routeIs('admin.logs.*') ? 'open' : '' }}">
            <button type="button" onclick="this.closest('.dropdown-group').classList.toggle('open')" data-tooltip="Pengaturan"
                class="sidebar-item w-[calc(100%-24px)] flex items-center gap-3 text-[13px] font-semibold text-slate-400 text-left">
                <i class="bi bi-gear text-lg w-5 text-center shrink-0"></i>
                <span class="flex-1">Pengaturan</span>
                <i class="bi bi-chevron-down text-[11px] text-white/40 chevron-icon shrink-0"></i>
            </button>
            <div class="dropdown-list pl-3 mt-1 space-y-1">
                <a href="{{ route('admin.template.index') }}" data-tooltip="Template Surat"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.template.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-file-earmark-text text-base w-5 text-center shrink-0"></i> Template Surat
                </a>
                <a href="{{ route('admin.users.index') }}" data-tooltip="Data Pegawai"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.users.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-people text-base w-5 text-center shrink-0"></i> Data Pegawai
                </a>
                <a href="{{ route('admin.file.index') }}" data-tooltip="File Surat"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.file.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-hdd text-base w-5 text-center shrink-0"></i> File Surat
                </a>
                <a href="{{ route('admin.logs.index') }}" data-tooltip="System Logs"
                   class="sidebar-item flex items-center gap-3 text-[12.5px] font-semibold text-slate-400
                          {{ request()->routeIs('admin.logs.*') ? 'nav-active' : '' }}">
                    <i class="bi bi-journal-text text-base w-5 text-center shrink-0"></i> System Logs
                </a>
            </div>
        </div>

        <a href="{{ route('admin.chart.index') }}" data-tooltip="Statistik & Grafik"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.chart.*') ? 'nav-active' : '' }}">
            <i class="bi bi-bar-chart text-lg w-5 text-center shrink-0"></i>
            <span>Statistik & Grafik</span>
        </a>

        {{-- BANTUAN --}}
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-widest text-white/30">Bantuan</p>
        <a href="{{ route('admin.faq.index') }}" data-tooltip="FAQ & Panduan"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.faq.*') ? 'nav-active' : '' }}">
            <i class="bi bi-question-circle text-lg w-5 text-center shrink-0"></i>
            <span>FAQ & Panduan</span>
        </a>
        <a href="{{ route('admin.bug-report.index') }}" data-tooltip="Laporan Bug"
           class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400
                  {{ request()->routeIs('admin.bug-report.*') ? 'nav-active' : '' }}">
            <i class="bi bi-bug-fill text-lg w-5 text-center shrink-0"></i>
            <span>Laporan Bug</span>
        </a>

        <div x-data="{ open: false }" class="relative" data-tooltip="Hubungi IT Support">
            <button @click="open = !open" @click.outside="open = false"
                    class="sidebar-item flex items-center gap-3 text-[13px] font-semibold text-slate-400 hover:text-blue-400 transition-colors w-full">
                <i class="bi bi-headset text-lg w-5 text-center shrink-0"></i>
                <span>Hubungi IT Support</span>
                <i class="bi bi-chevron-down ms-auto text-[10px]" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open"
                 xTransition
                 class="absolute left-0 top-full mt-1 w-56 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl py-2 z-50"
                 style="display: none;">
                @php
                    $waNumber = config('services.whatsapp.number');
                    $telegramUsername = config('services.telegram.admin_username');
                @endphp
                @if($waNumber)
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20IT%20Support%2C%20saya%20ingin%20melaporkan%20bug%20atau%20masalah%20di%20sistem."
                   target="_blank" data-tooltip="WhatsApp"
                   class="flex items-center gap-2 px-4 py-2.5 text-[12px] text-slate-300 hover:bg-slate-700 hover:text-white transition-all">
                    <i class="bi bi-whatsapp text-lg text-emerald-400"></i>
                    <span>WhatsApp</span>
                </a>
                @endif
                @if($telegramUsername)
                <a href="https://t.me/{{ $telegramUsername }}" target="_blank" data-tooltip="Telegram"
                   class="flex items-center gap-2 px-4 py-2.5 text-[12px] text-slate-300 hover:bg-slate-700 hover:text-white transition-all">
                    <i class="bi bi-telegram text-lg text-blue-400"></i>
                    <span>Telegram</span>
                </a>
                @endif
                <a href="mailto:tubpsuml@gmail.com?subject=Laporan%20Bug%20Sistem%20Persuratan" data-tooltip="Email"
                   class="flex items-center gap-2 px-4 py-2.5 text-[12px] text-slate-300 hover:bg-slate-700 hover:text-white transition-all">
                    <i class="bi bi-envelope text-lg text-red-400"></i>
                    <span>Email</span>
                </a>
            </div>
        </div>

        <div class="pb-4"></div>
    </nav>

    {{-- User Footer --}}
    <div class="shrink-0 p-4">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-3 flex items-center gap-3 hover:bg-white/10 transition-all cursor-pointer group relative profile-widget-container"
             data-tooltip="Profil: {{ Auth::user()->name }}">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 p-0.5 shrink-0 relative profile-avatar-wrapper">
                <div class="w-full h-full rounded-[10px] bg-slate-900 flex items-center justify-center text-white text-xs font-black overflow-hidden">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <!-- Floating online status dot for mini sidebar avatar -->
                <div class="absolute -bottom-1 -right-1 w-3 h-3 rounded-full bg-emerald-500 border-2 border-slate-900 shadow-[0_0_10px_rgba(16,185,129,0.5)] hidden mini-online-dot"></div>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-white text-[13px] font-bold truncate leading-none group-hover:text-blue-400 transition-colors">{{ Auth::user()->name }}</div>
                <div class="text-white/40 text-[10px] font-bold uppercase tracking-wider mt-1.5 leading-none">{{ Auth::user()->getRoleLabel() }}</div>
            </div>
            <div class="shrink-0 normal-online-dot">
                <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
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

        {{-- Mobile/Desktop Toggle --}}
        <button id="sidebar-toggle" onclick="openSidebar()"
            class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
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
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center ring-2 ring-white dark:ring-slate-900 shadow-lg shadow-red-500/20 animate-pulse">
                            {{ $notifCount }}
                        </span>
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
        if (window.innerWidth >= 1024) {
            document.getElementById('sidebar').classList.toggle('is-mini');
            localStorage.setItem('admin_sidebar_mini', document.getElementById('sidebar').classList.contains('is-mini'));
        } else {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('mobile-backdrop').classList.add('show');
        }
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
    var CURRENT_USER = {
        id:           {{ Auth::id() }},
        name:         '{{ addslashes(Auth::user()->name) }}',
        email:        '{{ addslashes(Auth::user()->email) }}',
        initials:     '{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}',
        role:         'admin',
        photo:        '{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : "" }}',
        switch_token: '{{ session("switch_token_raw", "") }}'
    };
    var SWITCH_URL  = '{{ route("auth.switch") }}';
    var CSRF_TOKEN  = '{{ csrf_token() }}';
    var STORAGE_KEY = 'bpsuml_saved_accounts';

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
        // Simpan akun saat ini ke localStorage sebelum pergi ke register
        // agar tidak hilang saat sesi baru dibuat
        saveCurrentAccount();
        // Langsung ke register tanpa logout — akun lama tetap tersimpan di localStorage
        window.location.href = '{{ route("register") }}';
    }

    function logoutCurrentUser() {
        let accounts = getSavedAccounts().filter(a => a.id !== CURRENT_USER.id);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
        document.getElementById('logout-form').submit();
    }

    // ===== INITIALIZATION =====
    (function() {
        var initAdminLayout = function() {
            // Refresh CSRF Token
            CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || CSRF_TOKEN;

            // Dark Mode Init
            var isDark = localStorage.getItem('darkMode') === 'true';
            if (isDark) document.documentElement.classList.add('dark');
            updateDarkIcon();

            // Restore mini state
            if (localStorage.getItem('admin_sidebar_mini') === 'true' && window.innerWidth >= 1024) {
                document.getElementById('sidebar')?.classList.add('is-mini');
            }

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
        };

        document.addEventListener('turbo:load', initAdminLayout);
        if (document.readyState !== 'loading') initAdminLayout();
        else document.addEventListener('DOMContentLoaded', initAdminLayout);

        // Global listeners (only once)
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('user-dropdown-wrap');
            var menu = document.getElementById('user-dropdown');
            if (wrap && menu && !wrap.contains(e.target)) {
                menu.classList.add('hidden');
            }

            // Close open mini sidebar dropdowns when clicking outside
            const sidebar = document.getElementById('sidebar');
            if (sidebar && sidebar.classList.contains('is-mini')) {
                document.querySelectorAll('.dropdown-group.open').forEach(group => {
                    if (!group.contains(e.target)) {
                        group.classList.remove('open');
                    }
                });
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && typeof closeSidebar === 'function') closeSidebar();
        });
    })();

    function markNotifRead(id) {
        const item = document.querySelector(`.notif-item-wrapper[data-id="${id}"]`);
        if(item) item.style.opacity = '0.5';

        fetch(`/notif/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if(item) {
                    item.style.opacity = '1';
                    const link = item.querySelector('.notif-item');
                    link.classList.remove('unread');
                    const dot = item.querySelector('.unread-dot');
                    if(dot) dot.remove();
                    const readBtn = item.querySelector('.btn-notif-action.text-primary');
                    if(readBtn) readBtn.remove();
                }
                // Update Badge counts if possible
                updateNotifBadges(data.unreadCount);
            }
        })
        .catch(err => {
            console.error(err);
            if(item) item.style.opacity = '1';
        });
    }

    function deleteNotif(id) {
        if(!confirm('Hapus notifikasi ini?')) return;
        const item = document.querySelector(`.notif-item-wrapper[data-id="${id}"]`);
        if(item) item.style.opacity = '0.5';

        fetch(`/notif/delete/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if(item) {
                    item.style.transition = 'all 0.3s ease';
                    item.style.transform = 'translateX(50px)';
                    item.style.opacity = '0';
                    setTimeout(() => item.remove(), 300);
                }
                updateNotifBadges(data.unreadCount);
            }
        })
        .catch(err => {
            console.error(err);
            if(item) item.style.opacity = '1';
        });
    }

    function updateNotifBadges(count) {
        const top = document.getElementById('topbar-notif-badge-container');
        const side = document.getElementById('sidebar-notif-badge-container');
        const badgeHtml = count > 0 ? `<span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center ring-2 ring-white dark:ring-slate-900 shadow-lg shadow-red-500/20 animate-pulse">${count}</span>` : '';
        const sideHtml = count > 0 ? `<span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-red-500/20 animate-pulse">${count}</span>` : '';
        
        if(top) top.innerHTML = badgeHtml;
        if(side) side.innerHTML = sideHtml;
    }
</script>

{{-- Form untuk Tandai Semua Dibaca --}}
<form id="readall-form" action="{{ route('notif.readAll') }}" method="POST" class="d-none">@csrf</form>
<form id="deleteall-form" action="{{ route('notif.deleteAll') }}" method="POST" class="d-none">@csrf</form>

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
        <div class="d-flex gap-3">
            @if(auth()->user()->unreadNotifications->count() > 0)
                <a href="#" onclick="event.preventDefault(); document.getElementById('readall-form').submit();" 
                   class="text-decoration-none fw-bold" style="font-size: 11px; color: #3b82f6;">
                    Tandai semua dibaca
                </a>
            @endif
            @if(auth()->user()->notifications->count() > 0)
                <a href="#" onclick="event.preventDefault(); if(confirm('Hapus semua notifikasi?')) document.getElementById('deleteall-form').submit();" 
                   class="text-decoration-none fw-bold text-danger" style="font-size: 11px;">
                    Hapus semua
                </a>
            @endif
        </div>
    </div>
    <div class="offcanvas-body p-0" style="overflow-y: auto; overflow-x: hidden;">
        @forelse(auth()->user()->notifications->take(15) as $notif)
            <div class="notif-item-wrapper position-relative group" data-id="{{ $notif->id }}">
                <a href="{{ route('notif.read', $notif->id) }}"
                   class="notif-item {{ $notif->read_at ? '' : 'unread' }} pr-12">
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
                </a>
                
                {{-- Quick Actions --}}
                <div class="notif-actions position-absolute top-50 translate-middle-y end-0 pr-4 d-flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @if(!$notif->read_at)
                        <button onclick="event.preventDefault(); event.stopPropagation(); markNotifRead('{{ $notif->id }}')" 
                                class="btn-notif-action text-primary" title="Tandai dibaca">
                            <i class="bi bi-check2-circle"></i>
                        </button>
                    @endif
                    <button onclick="event.preventDefault(); event.stopPropagation(); deleteNotif('{{ $notif->id }}')" 
                            class="btn-notif-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                @if(!$notif->read_at)
                    <div class="unread-dot position-absolute top-50 translate-middle-y end-0 mr-4 group-hover:opacity-0 transition-opacity" 
                         style="width:8px; height:8px; border-radius:50%; background:#3b82f6;"></div>
                @endif
            </div>
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

@stack('modals')
@stack('scripts')
<script>
    document.addEventListener('turbo:load', () => {
        // Update active sidebar items
        const currentPath = window.location.pathname;
        const currentSearch = window.location.search;
        
        let bestScore = -1;
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        
        sidebarItems.forEach(item => {
            const href = item.getAttribute('href');
            if (!href || href === '#') return;
            
            try {
                const url = new URL(href, window.location.origin);
                const linkPath = url.pathname;
                const linkSearch = url.search;
                let score = -1;

                if (currentPath === linkPath) {
                    score = 1; // Path matches
                    if (linkSearch && currentSearch.includes(linkSearch)) {
                        score = 2; // Path and Search match
                    } else if (linkSearch && !currentSearch.includes(linkSearch)) {
                        score = -1; // Specific parameter mismatch
                    }
                }
                
                item.setAttribute('data-active-score', score);
                if (score > bestScore) bestScore = score;
            } catch(e) {
                item.setAttribute('data-active-score', -1);
            }
        });

        sidebarItems.forEach(item => {
            const score = parseInt(item.getAttribute('data-active-score') || -1);
            if (score > 0 && score === bestScore) {
                item.classList.add('nav-active');
            } else {
                item.classList.remove('nav-active');
            }
        });
        
        // Auto close mobile sidebar
        if (typeof closeSidebar === 'function') closeSidebar();
    });

    document.addEventListener('turbo:before-render', (event) => {
        if (document.startViewTransition) {
            event.preventDefault();
            document.startViewTransition(() => {
                event.detail.resume();
            });
        }
    });
</script>
<style>
    ::view-transition-old(root),
    ::view-transition-new(root) {
        animation-duration: 0.4s;
    }
</style>

<script>
    window.NOTIF_CONFIG = {
        enablePoll: false,
        urlRead: '/notif/mark-read/',
        urlReadAll: '{{ route("notif.readAll") }}',
        urlDelete: '/notif/delete/',
        urlDeleteAll: '{{ route("notif.deleteAll") }}',
        csrf: '{{ csrf_token() }}',
    };
</script>
<script src="{{ asset('js/notif-system.js') }}" defer></script>

</body>
</html>