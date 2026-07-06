<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} — Surat Balai Pengelolaan SUML</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- SweetAlert2 --}}
    {{-- Hotwire Turbo for Smooth SPA-like Transitions --}}
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css'])

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
            --user-sidebar-w: 240px;
            --user-topbar-h: 64px;
            --accent: #06b6d4;
            --accent-soft: rgba(6, 182, 212, 0.14);
            
            --glass-blur: 16px;
            --glass-shadow: 0 8px 32px 0 rgba(6, 182, 212, 0.07);
        }

        body {
            /* Nordic Obsidian & Steel Cyan Soft Mesh Gradient */
            background-color: #f4f7f9;
            background-image: 
                radial-gradient(at 0% 10%, rgba(6, 182, 212, 0.12) 0px, transparent 50%), 
                radial-gradient(at 90% 0%, rgba(37, 99, 235, 0.1) 0px, transparent 50%), 
                radial-gradient(at 50% 90%, rgba(14, 165, 233, 0.08) 0px, transparent 50%),
                linear-gradient(135deg, #eef2f6 0%, #f4f7f9 100%);
            background-attachment: fixed;
            background-size: cover;
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        html {
            scroll-behavior: smooth;
        }

        /* ===== APP SHELL (sidebar + content) ===== */
        .user-app {
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        .user-sidebar {
            width: var(--user-sidebar-w);
            flex-shrink: 0;
            background: linear-gradient(165deg, #0b0c10 0%, #15161e 50%, #07080b 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 8px 0 40px rgba(15, 23, 42, 0.35);
            display: flex;
            flex-direction: column;
            z-index: 1020;
            position: sticky;
            top: 0;
            align-self: flex-start;
            height: 100vh;
            overflow: hidden;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
        }

        .user-sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 0% 0%, rgba(6, 182, 212, 0.3) 0%, transparent 55%),
                radial-gradient(ellipse 90% 60% at 100% 100%, rgba(37, 99, 235, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .user-sidebar > * {
            position: relative;
            z-index: 1;
        }

        .user-sidebar-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }

        .user-sidebar-brand a {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
        }

        .user-sidebar-brand .logo-wrap {
            padding: 0.45rem 0.55rem;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .user-sidebar-brand img {
            height: 34px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .user-sidebar-brand .brand-text .title {
            font-size: 0.82rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .user-sidebar-brand .brand-text .subtitle {
            font-size: 0.625rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.38);
            text-transform: uppercase;
            letter-spacing: 0.14em;
            margin-top: 0.2rem;
        }

        .user-sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.65rem 0.5rem 1rem;
            scrollbar-width: none;
        }

        .user-sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .user-nav-label {
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, 0.32);
            padding: 1rem 0.85rem 0.35rem;
            border: none;
            outline: none;
            box-shadow: none;
            background: transparent;
        }

        .user-sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin: 2px 0.5rem;
            padding: 0.62rem 0.9rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: rgba(226, 232, 240, 0.72);
            text-decoration: none;
            border: none;
            background: transparent;
            width: calc(100% - 1rem);
            text-align: left;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .user-sidebar-item i {
            font-size: 1.05rem;
            width: 1.35rem;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.9;
        }

        .user-sidebar-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            transform: translateX(2px);
        }

        .user-sidebar-item.is-active {
            background: linear-gradient(90deg, var(--accent-soft) 0%, rgba(6, 182, 212, 0.06) 100%);
            color: #67e8f9 !important;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(6, 182, 212, 0.25);
        }

        .user-sidebar-item.is-active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 22%;
            bottom: 22%;
            width: 3px;
            background: linear-gradient(180deg, #22d3ee, #06b6d4);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 14px rgba(6, 182, 212, 0.55);
        }

        .user-sidebar-item {
            position: relative;
        }

        .user-sidebar-icon-stack {
            position: relative;
            display: inline-flex;
            width: 1.35rem;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .user-sidebar-nav-badge {
            position: absolute;
            top: -7px;
            right: -11px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            font-size: 9px;
            font-weight: 800;
            line-height: 16px;
            text-align: center;
            color: #fff;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 99px;
            border: 2px solid #0f172a;
            box-shadow: 0 2px 10px rgba(239, 68, 68, 0.5);
        }

        .user-sidebar-item.sidebar-unread-pulse:not(.is-active) {
            background: rgba(239, 68, 68, 0.1);
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.22);
        }

        .user-sidebar-item.sidebar-unread-pulse:not(.is-active) span:not(.user-sidebar-nav-badge) {
            color: #fecaca;
        }

        .user-sidebar-cta {
            margin: 0.35rem 0.75rem 0.5rem;
            padding: 0.7rem 1rem;
            border-radius: 14px;
            font-size: 0.8125rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #fff !important;
            text-decoration: none;
            background: linear-gradient(135deg, #2563eb 0%, #0284c7 50%, #06b6d4 100%);
            box-shadow: 0 10px 28px rgba(6, 182, 212, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .user-sidebar-cta:hover {
            color: #fff !important;
            transform: translateY(-1px);
            box-shadow: 0 14px 36px rgba(6, 182, 212, 0.38);
        }

        .user-sidebar-cta.is-active {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.35), 0 12px 32px rgba(6, 182, 212, 0.45);
        }

        .user-nav-group {
            margin-bottom: 2px;
        }

        .user-nav-group-toggle {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            width: calc(100% - 1rem);
            margin: 2px 0.5rem;
            padding: 0.62rem 0.9rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: rgba(226, 232, 240, 0.72);
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .user-nav-group-toggle:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
        }

        .user-nav-group-toggle .chev {
            margin-left: auto;
            font-size: 0.65rem;
            opacity: 0.45;
            transition: transform 0.2s ease;
        }

        .user-nav-group.is-open .user-nav-group-toggle .chev {
            transform: rotate(180deg);
        }

        .user-nav-group-body {
            display: none;
            padding: 2px 0 4px 0.35rem;
        }

        .user-nav-group.is-open .user-nav-group-body {
            display: block;
            animation: sidebarFade 0.18s ease-out;
        }

        .user-nav-group-body .user-sidebar-item {
            font-size: 0.78rem;
            padding: 0.52rem 0.85rem;
            margin-left: 0.25rem;
            width: calc(100% - 1.25rem);
        }

        @keyframes sidebarFade {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }        /* ===== SMOOTH MINI SIDEBAR TRANSITIONS & STYLING ===== */
        .user-sidebar.is-mini,
        html.is-sidebar-mini #userSidebar {
            width: 88px !important;
        }

        .user-sidebar.is-mini .user-sidebar-nav {
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }

        /* Brand logo switch */
        .user-sidebar-brand {
            transition: all 0.3s ease;
        }
        .user-sidebar.is-mini .user-sidebar-brand {
            padding: 1.25rem 0.5rem !important;
            display: flex !important;
            justify-content: center !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        .user-sidebar.is-mini .user-sidebar-brand .logo-full {
            display: none !important;
        }
        .user-sidebar.is-mini .user-sidebar-brand .logo-mini {
            display: block !important;
        }

        /* Section Labels: smooth fade out */
        .user-nav-label {
            transition: opacity 0.2s ease, max-height 0.2s ease, padding 0.2s ease, margin 0.2s ease, visibility 0.2s ease;
        }
        .user-sidebar.is-mini .user-nav-label,
        html.is-sidebar-mini #userSidebar .user-nav-label {
            opacity: 0 !important;
            max-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        /* Nav Items & Group Toggles: symmetrical compact styling */
        .user-sidebar-item, 
        .user-nav-group-toggle {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .user-sidebar.is-mini .user-sidebar-item, 
        .user-sidebar.is-mini .user-nav-group-toggle {
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

        .user-sidebar.is-mini .user-sidebar-item i,
        .user-sidebar.is-mini .user-nav-group-toggle i {
            font-size: 1.25rem !important;
            margin: 0 !important;
            width: auto !important;
        }

        /* Hide texts & chevrons */
        .user-sidebar.is-mini .user-sidebar-item span:not(.user-sidebar-nav-badge):not(.user-sidebar-icon-stack),
        .user-sidebar.is-mini .user-nav-group-toggle .flex-grow-1,
        .user-sidebar.is-mini .user-nav-group-toggle .chev {
            display: none !important;
        }

        /* Notification badge alignment in mini sidebar */
        .user-sidebar.is-mini .user-sidebar-item .user-sidebar-icon-stack {
            position: relative !important;
            width: auto !important;
            display: inline-flex !important;
        }
        .user-sidebar.is-mini .user-sidebar-item .user-sidebar-nav-badge {
            position: absolute !important;
            top: -6px !important;
            right: -10px !important;
            z-index: 10 !important;
        }

        /* Active accent indicator for mini sidebar */
        .user-sidebar.is-mini .user-sidebar-item.is-active::before {
            left: 5px !important;
            top: 25% !important;
            bottom: 25% !important;
            width: 3.5px !important;
            border-radius: 99px !important;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.8) !important;
        }

        /* Call To Action button (Ajukan Surat button) mini morph */
        .user-sidebar-cta {
            transition: all 0.3s ease;
        }
        .user-sidebar.is-mini .user-sidebar-cta {
            width: 56px !important;
            height: 56px !important;
            border-radius: 16px !important;
            padding: 0 !important;
            margin: 10px auto !important;
            justify-content: center !important;
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.3) !important;
        }
        .user-sidebar.is-mini .user-sidebar-cta i {
            font-size: 1.3rem !important;
            margin: 0 !important;
        }
        .user-sidebar.is-mini .user-sidebar-cta span {
            display: none !important;
        }

        /* Vertical Accordion Submenus for User Sidebar */
        .user-sidebar.is-mini .user-nav-group-body {
            display: none !important;
            padding-left: 0 !important;
            margin-top: 4px !important;
        }

        .user-sidebar.is-mini .user-nav-group.is-open .user-nav-group-body {
            display: flex !important;
            flex-direction: column !important;
            gap: 4px !important;
        }

        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item {
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

        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            transform: scale(1.05) !important;
        }

        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item span {
            display: none !important;
        }

        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item i {
            font-size: 1.1rem !important;
            margin: 0 !important;
            color: #94a3b8 !important;
        }

        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item:hover i {
            color: #22d3ee !important;
        }

        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item.is-active {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(6, 182, 212, 0.05) 100%) !important;
            border: 1px solid rgba(6, 182, 212, 0.4) !important;
            box-shadow: none !important;
        }
        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item.is-active i {
            color: #22d3ee !important;
        }
        .user-sidebar.is-mini .user-nav-group-body .user-sidebar-item.is-active::before {
            display: none !important;
        }

        /* Profile Footer Widget mini morph */
        .user-sidebar-footer {
            transition: all 0.3s ease;
        }
        .user-sidebar.is-mini .user-sidebar-footer {
            padding: 1rem 0.5rem !important;
            display: flex !important;
            justify-content: center !important;
        }

        .user-sidebar.is-mini .profile-widget-container {
            padding: 0.5rem !important;
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 16px !important;
            justify-content: center !important;
            width: 56px !important;
            height: 56px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
        }
        .user-sidebar.is-mini .profile-widget-container:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-2px) scale(1.03) !important;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3) !important;
        }
        .user-sidebar.is-mini .profile-widget-container .sf-meta,
        .user-sidebar.is-mini .profile-widget-container .sf-chevron {
            display: none !important;
        }
        .user-sidebar.is-mini .profile-widget-container .sf-avatar {
            margin: 0 !important;
            border-radius: 12px !important;
        }

        /* Tooltips for User Sidebar */
        .user-sidebar.is-mini [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #0f172a;
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

        .user-sidebar.is-mini .user-nav-group.is-open [data-tooltip]::after,
        .user-sidebar.is-mini .relative.open[data-tooltip]::after {
            display: none !important;
        }

        .user-sidebar, .user-shell {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .user-sidebar-brand .logo-wrap {
            transition: all 0.3s ease;
        }

        .user-sidebar-footer {
            flex-shrink: 0;
            padding: 0.85rem 1rem 1.1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(0, 0, 0, 0.15);
        }

        .user-sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .user-sidebar-footer a:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.14);
        }

        .user-sidebar-footer .sf-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(145deg, #06b6d4, #2563eb);
            box-shadow: 0 6px 18px rgba(6, 182, 212, 0.3);
        }

        .user-sidebar-footer .sf-meta {
            min-width: 0;
            flex: 1;
        }

        .user-sidebar-footer .sf-name {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-sidebar-footer .sf-hint {
            font-size: 0.625rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.38);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 0.15rem;
        }

        .user-shell {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Turbo: smooth fade saat pindah halaman */
        #userShell {
            will-change: opacity, transform;
        }
        html.turbo-loading #userShell {
            opacity: 0.5;
            transform: translateY(6px);
            pointer-events: none;
        }
        #userShell.is-entering {
            animation: userPageEnter 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        @keyframes userPageEnter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Turbo progress bar */
        .turbo-progress-bar {
            height: 3px;
            background: linear-gradient(90deg, #06b6d4, #2563eb);
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.45);
        }

        .user-topbar {
            height: var(--user-topbar-h);
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 1.25rem;
            background: rgba(255, 255, 255, 0.42);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.04);
            transition: background 0.25s ease, box-shadow 0.25s ease;
        }

        .user-topbar.scrolled {
            background: rgba(255, 255, 255, 0.62);
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.07);
        }

        .user-sidebar-toggle {
            display: flex;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.65);
            background: rgba(255, 255, 255, 0.55);
            color: #475569;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .user-sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.85);
            color: #2563eb;
        }

        .user-topbar-title {
            flex: 1;
            min-width: 0;
            padding-left: 0.5rem;
        }

        .user-topbar-title h1 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-topbar-title p {
            margin: 0.2rem 0 0;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            opacity: 0.85;
        }

        /* ===== ENHANCED PROFILE TRIGGER ===== */
        .user-profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.4rem 0.6rem;
            padding-right: 0.8rem;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .user-profile-trigger:hover {
            background: rgba(255, 255, 255, 0.75);
            border-color: rgba(255, 255, 255, 1);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .user-profile-trigger:active {
            transform: translateY(0);
        }

        .user-meta {
            text-align: right;
            line-height: 1.2;
        }

        .user-meta .u-name {
            display: block;
            font-size: 0.825rem;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
        }

        .user-meta .u-role {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-top: 1px;
        }

        .user-avatar-wrap {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            border: 2px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
            color: #475569;
        }

        .user-avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: rgba(0, 0, 0, 0.08);
            margin: 0 0.5rem;
        }

        /* ===== ENHANCED NOTIF BTN ===== */
        .notif-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.65);
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .notif-btn:hover {
            background: rgba(255, 255, 255, 0.85);
            color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .notif-btn i { font-size: 1.15rem; }

        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.35);
        }

        .user-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 1034;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .user-sidebar-backdrop.show {
            display: block;
            opacity: 1;
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
        .notif-item { transition: all 0.2s ease; position: relative; }
        .btn-notif-action {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.8);
            border: 1px solid var(--border-color);
            transition: all 0.2s;
            font-size: 14px;
            border: none;
        }
        .btn-notif-action:hover {
            background: #fff;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .notif-item-wrapper:hover .notif-item {
            background: rgba(255,255,255,0.5);
        }
        .pr-12 { padding-right: 3rem !important; }
        .mr-4 { margin-right: 1rem !important; }
        .group:hover .notif-actions { opacity: 1 !important; }

        /* ===== AVATAR --===== */
        .user-avatar {
            position: relative;
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
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== HOVER DROPDOWN ===== */
        @media (min-width: 992px) {
            .dropdown { position: relative; }
            
            .dropdown:hover > .dropdown-menu {
                display: block;
                margin-top: 0;
                opacity: 1;
                visibility: visible;
                animation: dropdownFade 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Bridge gap to prevent accidental close */
            .dropdown > .user-avatar::after {
                content: "";
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                height: 10px;
                background: transparent;
            }

            /* Ensure right alignment for end-aligned menus on hover */
            .dropdown-menu-end {
                right: 0 !important;
                left: auto !important;
            }
            
            .dropdown-submenu { position: relative; }
            .dropdown-submenu:hover > .dropdown-menu {
                display: block;
                left: 100% !important;
                right: auto !important;
                top: 0;
                margin-top: -8px;
                margin-left: 0px;
                border-radius: 12px !important;
            }

            /* Submenu bridge gap */
            .dropdown-submenu > a::after {
                content: "";
                position: absolute;
                top: 0;
                left: 100%;
                width: 15px;
                height: 100%;
                background: transparent;
            }

            /* Submenu indicator arrow rotation on hover */
            .dropdown-submenu:hover > a .bi-chevron-right {
                transform: rotate(90deg);
                transition: transform 0.2s;
            }
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
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
            flex: 1;
            padding: 24px;
            width: 100%;
            min-width: 0;
            max-width: 100%;
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
            top: calc(var(--user-topbar-h) + 12px);
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
            .user-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: none;
            }
            .user-sidebar.is-open {
                transform: translateX(0);
                box-shadow: 16px 0 48px rgba(0, 0, 0, 0.35);
                z-index: 1036;
            }
            .user-sidebar-toggle {
                display: inline-flex;
            }
            .main-content {
                padding: 16px 12px;
            }
            .flash-container {
                width: calc(100% - 32px);
                right: 16px;
                left: 16px;
                top: calc(var(--user-topbar-h) + 10px);
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
            
            .table-responsive {
                border: 1px solid rgba(255,255,255,0.5);
                border-radius: 12px;
                background: rgba(255,255,255,0.3);
            }
        }

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
        /* Prevent Transition Flicker on Load */
        .no-transition * {
            transition: none !important;
        }

        /* Suppress focus ring flicker (sidebar + main content) */
        .user-sidebar *:focus,
        .user-sidebar *:focus-visible {
            outline: none !important;
        }
        .surat-item:focus, .notification-item:focus, .stat-card-modern:focus,
        .user-sidebar-item:focus, .user-nav-group-toggle:focus,
        .user-nav-label:focus, a:focus, button:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Early mini-sidebar state (set in <head> before paint) */
        html.is-sidebar-mini #userSidebar .user-sidebar-brand .logo-full { display: none !important; }
        html.is-sidebar-mini #userSidebar .user-sidebar-brand .logo-mini { display: block !important; }

        /* ===== NESTED DROPDOWN (DROPEND) ===== */
        .dropdown-submenu {
            position: relative;
        }
        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            display: none;
        }
        .dropdown-submenu:hover > .dropdown-menu {
            display: block;
        }
        @media (max-width: 991px) {
            .dropdown-submenu .dropdown-menu {
                position: static;
                display: block;
                border: none !important;
                background: rgba(0,0,0,0.03) !important;
                box-shadow: none !important;
                margin-left: 1rem;
                margin-bottom: 0.5rem;
            }
            .dropdown-submenu > .dropdown-toggle::after {
                transform: rotate(90deg);
            }
        }

        /* ===== MODAL Z-INDEX FIX ===== */
        /* Ensure modals are always clickable and not blocked by backdrop */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1059 !important;
            pointer-events: none !important;
        }
        .modal-backdrop.show {
            opacity: 0.5 !important;
            pointer-events: none !important;
        }
        /* Ensure modal content is clickable */
        .modal-dialog,
        .modal-content,
        .modal-body,
        .modal-header,
        .modal-footer {
            pointer-events: auto !important;
        }
    </style>
    <script>
        // Restore mini sidebar before first paint (avoids label/box flash)
        (function () {
            try {
                if (localStorage.getItem('user_sidebar_mini') === 'true' && window.innerWidth >= 992) {
                    document.documentElement.classList.add('is-sidebar-mini');
                }
            } catch (e) {}
        })();

        // Disable transitions until CSS (incl. Vite/Tailwind) is ready
        document.documentElement.classList.add('no-transition');
        function releaseNoTransition() {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.documentElement.classList.remove('no-transition');
                });
            });
        }
        if (document.readyState === 'complete') {
            releaseNoTransition();
        } else {
            window.addEventListener('load', releaseNoTransition, { once: true });
        }

        // ===== SMOOTH SCROLL BEHAVIOR =====
        // Biarkan browser handle scroll restoration secara natural
        if ('scrollRestoration' in window.history) {
            window.history.scrollRestoration = 'auto';
        }
    </script>
    @stack('styles')
</head>
<body>

@php
    $unreadNotif = auth()->user()->unreadNotifications->count();
    $userSuratGroupOpen = request()->routeIs('user.surat.*')
        && !request()->routeIs('user.surat.create');
    $userLainnyaOpen = request()->routeIs('user.faq.*', 'user.about.*', 'user.notifikasi.index', 'user.aspirasi.index', 'user.activity-log.*');
    $userAspirasiOpen = request()->routeIs('user.aspirasi.index');
    $userProfilOpen = request()->routeIs('profile.edit');
@endphp

<div class="user-app">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="userSidebar" class="user-sidebar" aria-label="Menu utama" data-turbo-permanent>
        <div class="user-sidebar-brand d-flex align-items-center justify-content-center">
            <a href="{{ route('dashboard') }}" data-turbo="false" class="d-flex align-items-center justify-content-center">
                <!-- Full Logo -->
                <div class="logo-wrap logo-full">
                    <img src="{{ asset('images/BP_SUML2.png') }}" alt="Logo BP SUML">
                </div>
                <!-- Mini Logo -->
                <div class="logo-wrap logo-mini d-none">
                    <img src="{{ asset('images/metrologi.png') }}" alt="Logo BP SUML" style="height: 32px; width: 32px;">
                </div>
            </a>
        </div>

        <nav class="user-sidebar-nav">
            <div class="user-nav-label">Utama</div>
            <a href="{{ url('/?home=1') }}" data-turbo="false" class="user-sidebar-item" data-tooltip="Beranda Publik">
                <i class="bi bi-globe2"></i>
                <span>Beranda Publik</span>
            </a>
            <a href="{{ route('dashboard') }}" 
               class="user-sidebar-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" data-tooltip="Dashboard">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <div class="user-nav-label">Notification</div>
            <a href="{{ route('user.notifikasi.index') }}" 
                 class="user-sidebar-item {{ request()->routeIs('user.notifikasi.index') ? 'is-active' : '' }} {{ $unreadNotif > 0 ? 'sidebar-unread-pulse' : '' }}" data-tooltip="Notifikasi">
                    <span class="user-sidebar-icon-stack">
                        <i class="bi bi-bell{{ $unreadNotif > 0 ? '-fill' : '' }}"></i>
                            @if($unreadNotif > 0)
                                <span class="user-sidebar-nav-badge">{{ $unreadNotif > 99 ? '99+' : $unreadNotif }}</span>
                            @endif
                        </span>
                    <span>Notifikasi</span>
                </a>
                
            <div class="user-nav-label">Upload Surat</div>
            <a href="{{ route('user.surat.create') }}"
               class="user-sidebar-cta {{ request()->routeIs('user.surat.create') ? 'is-active' : '' }}" data-tooltip="Ajukan Surat">
                <i class="bi bi-plus-lg"></i>
                <span>Ajukan Surat</span>
            </a>
            

            <div class="user-nav-label">Surat</div>
            <div class="user-nav-group {{ $userSuratGroupOpen ? 'is-open' : '' }}">
                <button type="button" class="user-nav-group-toggle" onclick="this.closest('.user-nav-group').classList.toggle('is-open')" data-tooltip="Surat Saya">
                    <i class="bi bi-envelope-paper"></i>
                    <span class="flex-grow-1">Surat Saya</span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>
                <div class="user-nav-group-body">
                    <a href="{{ route('user.surat.index') }}"
                       class="user-sidebar-item {{ request()->routeIs('user.surat.index') && request('status') !== 'draft' ? 'is-active' : '' }}" data-tooltip="Card">
                        <i class="bi bi-envelope-open"></i>
                        <span>Card</span>
                    </a>
                    <a href="{{ route('user.surat.index', ['status' => 'draft']) }}"
                       class="user-sidebar-item {{ request('status') === 'draft' ? 'is-active' : '' }}" data-tooltip="Draft">
                        <i class="bi bi-pencil-square"></i>
                        <span>Draft</span>
                    </a>
                    <a href="{{ route('user.surat.table') }}"
                       class="user-sidebar-item {{ request()->routeIs('user.surat.table') ? 'is-active' : '' }}" data-tooltip="Tabel detail">
                        <i class="bi bi-table"></i>
                        <span>Tabel detail</span>
                    </a>
                    <a href="{{ route('user.surat.file_index') }}"
                         class="user-sidebar-item {{ request()->routeIs('user.surat.file_index') ? 'is-active' : '' }}" data-tooltip="Hapus File Surat">
                        <i class="bi bi-file-earmark-x"></i>
                        <span>Hapus File Surat</span>
                    </a>
                    <a href="{{ route('user.surat.exportExcel') }}" data-turbo="false"
                       class="user-sidebar-item {{ request()->routeIs('user.surat.exportExcel') ? 'is-active' : '' }}" data-tooltip="Ekspor Excel">
                        <i class="bi bi-download"></i>
                        <span>Ekspor Excel</span>
                    </a>
                </div>
            </div>

            <div class="user-nav-label">Insight</div>
            <a href="{{ route('user.statistik.index') }}" 
               class="user-sidebar-item {{ request()->routeIs('user.statistik.index') ? 'is-active' : '' }}" data-tooltip="Statistik">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Statistik</span>
            </a>
            <a href="{{ route('user.sla.index') }}"
               class="user-sidebar-item {{ request()->routeIs('user.sla.index') ? 'is-active' : '' }}" data-tooltip="Monitoring SLA">
                <i class="bi bi-speedometer2"></i>
                <span>Monitoring SLA</span>
            </a>
            <a href="{{ route('user.agenda.index') }}"
               class="user-sidebar-item {{ request()->routeIs('user.agenda.*') ? 'is-active' : '' }}" data-tooltip="Agenda">
                <i class="bi bi-calendar2-week"></i>
                <span>Agenda</span>
            </a>
            <a href="{{ route('user.template.index') }}" 
               class="user-sidebar-item {{ request()->routeIs('user.template.*') ? 'is-active' : '' }}" data-tooltip="Template">
                <i class="bi bi-file-earmark-word"></i>
                <span>Template</span>
            </a>
            <a href="{{ route('user.pegawai.index') }}" 
               class="user-sidebar-item {{ request()->routeIs('user.pegawai.*') ? 'is-active' : '' }}" data-tooltip="Cari Pegawai">
                <i class="bi bi-people"></i>
                <span>Cari Pegawai</span>
            </a>

            <div class="user-nav-group {{ $userProfilOpen ? 'is-open' : '' }}">
                <button type="button" class="user-nav-group-toggle" onclick="this.closest('.user-nav-group').classList.toggle('is-open')" data-tooltip="Profil & Sesi">
                    <i class="bi bi-person-gear"></i>
                    <span class="flex-grow-1">Profil & Sesi</span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>
                <div class="user-nav-group-body">
                    <a href="{{ route('profile.edit') }}#info-umum" data-turbo="false"
                       class="user-sidebar-item {{ request()->routeIs('profile.edit') && !str_contains(request()->fullUrl(), '#') ? 'is-active' : '' }}" data-tooltip="Informasi Umum">
                        <i class="bi bi-person-bounding-box"></i>
                        <span>Informasi Umum</span>
                    </a>
                    <a href="{{ route('profile.edit') }}#keamanan" data-turbo="false"
                       class="user-sidebar-item" data-tooltip="Ubah Kata Sandi">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Ubah Kata Sandi</span>
                    </a>
                    <a href="{{ route('profile.edit') }}#sesi-aktif" data-turbo="false"
                       class="user-sidebar-item" data-tooltip="Sesi & Perangkat">
                        <i class="bi bi-laptop"></i>
                        <span>Sesi & Perangkat</span>
                    </a>
                    <a href="{{ route('profile.edit') }}#akun-lain" data-turbo="false"
                       class="user-sidebar-item" data-tooltip="Multi-Akun">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Multi-Akun</span>
                    </a>
                </div>
            </div>

            <div class="user-nav-label">Lainnya</div>
            <div class="user-nav-group {{ $userLainnyaOpen ? 'is-open' : '' }}">
                <button type="button" class="user-nav-group-toggle" onclick="this.closest('.user-nav-group').classList.toggle('is-open')" data-tooltip="Menu tambahan">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span class="flex-grow-1">Menu tambahan</span>
                    <i class="bi bi-chevron-down chev"></i>
                </button>
                <div class="user-nav-group-body">


                        <div class="user-nav-group-body">
                            <a href="{{ route('user.aspirasi.index', ['to' => 'admin']) }}"
                               class="user-sidebar-item {{ request()->routeIs('user.aspirasi.index') && request('to') === 'admin' ? 'is-active' : '' }}" data-tooltip="Ke Admin">
                                <i class="bi bi-person-badge"></i>
                                <span>Ke Admin</span>
                            </a>
                            <a href="{{ route('user.aspirasi.index', ['to' => 'it_support']) }}"
                               class="user-sidebar-item {{ request()->routeIs('user.aspirasi.index') && request('to') === 'it_support' ? 'is-active' : '' }}" data-tooltip="IT Support">
                                <i class="bi bi-cpu"></i>
                                <span>IT Support</span>
                            </a>


                    <a href="{{ route('user.faq.index') }}"
                       class="user-sidebar-item {{ request()->routeIs('user.faq.*') ? 'is-active' : '' }}" data-tooltip="FAQ">
                        <i class="bi bi-question-circle"></i>
                        <span>FAQ</span>
                    </a>
                    <a href="{{ route('user.about.index') }}"
                       class="user-sidebar-item {{ request()->routeIs('user.about.*') ? 'is-active' : '' }}" data-tooltip="Tentang">
                        <i class="bi bi-info-circle"></i>
                        <span>Tentang</span>
                    </a>
                    <a href="{{ route('user.activity-log.index') }}"
                       class="user-sidebar-item {{ request()->routeIs('user.activity-log.*') ? 'is-active' : '' }}" data-tooltip="Log User">
                        <i class="bi bi-clock-history"></i>
                        <span>Log User</span>
                    </a>
                    <a href="#" onclick="showPublicVerificationModal(event)"
                       class="user-sidebar-item" data-tooltip="Verifikasi Surat">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Verifikasi Surat</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="user-sidebar-footer">
            <a href="{{ route('profile.edit') }}" data-tooltip="Kelola Profil" class="profile-widget-container position-relative d-flex align-items-center">
                <div class="sf-avatar-wrapper position-relative">
                    <div class="sf-avatar">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <!-- Online Status Indicator -->
                    <span class="position-absolute bottom-0 end-0 bg-emerald-500 border border-slate-900 rounded-full"
                          style="width: 10px; height: 10px; box-shadow: 0 0 10px rgba(16, 185, 129, 0.6); display: block;"></span>
                </div>
                <div class="sf-meta ms-3">
                    <div class="sf-name">{{ Auth::user()->name }}</div>
                    <div class="sf-hint">Kelola profil</div>
                </div>
                <i class="bi bi-chevron-right text-white-50 small ms-auto sf-chevron"></i>
            </a>
        </div>
    </aside>

    <div id="userSidebarBackdrop" class="user-sidebar-backdrop" onclick="closeUserSidebar()" aria-hidden="true"></div>

    <div id="userShell" class="user-shell">

        <header class="user-topbar">
            <button type="button" class="user-sidebar-toggle" onclick="openUserSidebar()" aria-label="Buka menu">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="user-topbar-title">
                <h1>{{ $title ?? 'Dashboard Pegawai' }}</h1>
            </div>

            <div class="d-flex align-items-center ms-auto">
                {{-- Notifications --}}
                <button type="button" class="notif-btn"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasNotif"
                        aria-controls="offcanvasNotif" id="notif-toggle">
                    <i class="bi bi-bell"></i>
                    @if($unreadNotif > 0)
                        <span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>
                    @endif
                </button>

                <div class="topbar-divider d-none d-sm-block"></div>

                {{-- User Profile --}}
                <div class="dropdown">
                    <div class="user-profile-trigger" data-bs-toggle="dropdown" role="button" id="userProfileDropdown">
                        <div class="user-meta d-none d-lg-block text-end">
                            <span class="u-name">{{ Auth::user()->name }}</span>
                            <span class="u-role">NIP. {{ Auth::user()->nip ?? '00000000' }}</span>
                        </div>
                        <div class="user-avatar-wrap">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <i class="bi bi-chevron-down d-none d-sm-block opacity-50 ms-1 small"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px; border:none; box-shadow:0 8px 24px rgba(0,0,0,0.1); font-size:13px; min-width:180px;">
                        <li>
                            <div class="px-3 py-3 border-bottom d-flex align-items-center gap-3">
                                <div style="width:32px; height:32px; border-radius:50%; overflow:hidden; flex-shrink:0; background:rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:11px; color:var(--text-secondary);">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-weight:600; color:var(--text-primary); font-size:13px; line-height:1.2; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->name }}</div>
                                    <div style="font-size:11px; color:var(--text-secondary); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </li>
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
                            <div class="px-3 py-1.5" style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.08em;">
                                <i class="bi bi-arrow-left-right me-1"></i> Beralih Akun
                            </div>
                        </li>
                        <li id="user-saved-accounts-list">
                            {{-- Diisi oleh JS --}}
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#" onclick="switchToNewAccount(event)" style="color:#2563eb;">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Akun Lain
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="#"
                               onclick="event.preventDefault(); logoutCurrentUser();">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

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

        <main class="main-content">
            @yield('content')
        </main>

        {{-- ===== FOOTER ===== --}}
        <footer class="py-4 mt-auto">
            <div class="container-fluid text-center">
                <p class="mb-0" style="font-size: 13px; color: var(--text-secondary); opacity: 0.8;">
                    &copy; {{ date('Y') }} Balai Pengelolaan SUML &mdash; RI. All rights reserved.
                </p>
            </div>
        </footer>

    </div>{{-- /.user-shell --}}

    {{-- Modals stack: dirender di LUAR user-shell untuk menghindari stacking context yang membuat modal terhalang --}}
    @stack('modals')

</div>{{-- /.user-app --}}

{{-- Hidden forms --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" data-turbo-permanent>@csrf</form>
<form id="readall-form" action="{{ route('notif.readAll') }}" method="POST" class="d-none" data-turbo-permanent>@csrf</form>
<form id="deleteall-form" action="{{ route('notif.deleteAll') }}" method="POST" class="d-none" data-turbo-permanent>@csrf</form>

{{-- Switch Account Modal --}}
<div class="modal fade" id="switchAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
        <div class="modal-content" style="border-radius:20px; border:1px solid rgba(255,255,255,0.7); background:rgba(255,255,255,0.85); backdrop-filter:blur(20px);">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h6 class="modal-title fw-bold" style="font-size:15px; color:var(--text-primary);"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Beralih Akun</h6>
                    <p class="mb-0" style="font-size:11px; color:var(--text-secondary); margin-top:2px;">Pilih akun untuk masuk</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3" id="switchAccountModalBody">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm w-100" onclick="switchToNewAccount(event)" 
                        style="background:rgba(37,99,235,0.1); color:#2563eb; border:1px solid rgba(37,99,235,0.2); border-radius:10px; font-size:13px; padding:10px;">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Akun Baru
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    .rotate-180 { transform: rotate(180deg); }
</style>
@vite(['resources/js/app.js'])

    <script>
        // ===== GLOBAL FUNCTIONS (Available to onclick) =====
        var syncSidebarMiniState = function() {
            var sidebar = document.getElementById('userSidebar');
            var isMini = !!sidebar?.classList.contains('is-mini');
            document.documentElement.classList.toggle('is-sidebar-mini', isMini);
            return isMini;
        };

        var openUserSidebar = function() {
            var sidebar = document.getElementById('userSidebar');
            if (window.innerWidth >= 992) {
                sidebar?.classList.toggle('is-mini');
                localStorage.setItem('user_sidebar_mini', sidebar?.classList.contains('is-mini'));
                syncSidebarMiniState();
            } else {
                sidebar?.classList.add('is-open');
                document.getElementById('userSidebarBackdrop')?.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        };

        var closeUserSidebar = function() {
            document.getElementById('userSidebar')?.classList.remove('is-open');
            document.getElementById('userSidebarBackdrop')?.classList.remove('show');
            document.body.style.overflow = '';
        };

        var switchToNewAccount = function(e) {
            if (e) e.preventDefault();
            // Simpan akun saat ini ke localStorage sebelum pergi ke register
            // agar tidak hilang saat sesi baru dibuat
            saveCurrentAccount();
            // Langsung ke register tanpa logout — akun lama tetap tersimpan di localStorage
            window.location.href = '{{ route("register") }}';
        };

        var logoutCurrentUser = function() {
            var accounts = getSavedAccounts().filter(a => a.id !== CURRENT_USER.id);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
            document.getElementById('logout-form').submit();
        };

        // ===== DATA & UTILS =====
        var CURRENT_USER = {
            id:          {{ Auth::id() }},
            name:        '{{ addslashes(Auth::user()->name) }}',
            email:       '{{ addslashes(Auth::user()->email) }}',
            initials:    '{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}',
            role:        'user',
            photo:       '{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : "" }}',
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
            var accounts = getSavedAccounts();
            var idx = accounts.findIndex(a => a.id === CURRENT_USER.id);
            var entry = Object.assign({}, CURRENT_USER, { savedAt: Date.now() });
            if (idx >= 0) { accounts[idx] = entry; } else { accounts.push(entry); }
            if (accounts.length > 5) accounts = accounts.slice(-5);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
        }

        function renderSavedAccounts() {
            var accounts = getSavedAccounts().filter(a => a.id !== CURRENT_USER.id);
            var container = document.getElementById('user-saved-accounts-list');
            if (!container) return;

            if (accounts.length === 0) {
                container.innerHTML = '<div style="padding:4px 16px 2px; font-size:11px; color:var(--text-secondary); font-style:italic;">Belum ada akun tersimpan lain</div>';
                return;
            }
            container.innerHTML = accounts.map(acc => `
                <div>
                    <a class="dropdown-item py-2" href="#" id="switch-btn-${acc.id}"
                       onclick="doSwitchAccount(event, ${acc.id}, '${acc.switch_token}')"
                       style="display:flex; align-items:center; gap:10px; padding:8px 16px;">
                        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;overflow:hidden;">
                            ${acc.photo ? `<img src="${acc.photo}" style="width:100%;height:100%;object-fit:cover;">` : acc.initials}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:12px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${acc.name}</div>
                            <div style="font-size:10px;color:var(--text-secondary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${acc.email}</div>
                        </div>
                        <i class="bi bi-arrow-right-circle" style="color:#2563eb;font-size:14px;flex-shrink:0;"></i>
                    </a>
                </div>
            `).join('');
        }

        function doSwitchAccount(e, userId, token) {
            e.preventDefault();
            if (!token) {
                sessionStorage.setItem('bpsuml_switch_to_email', getSavedAccounts().find(a => a.id === userId)?.email || '');
                document.getElementById('logout-form').submit();
                return;
            }
            var btn = document.getElementById('switch-btn-' + userId);
            if (btn) {
                btn.style.opacity = '0.6';
                btn.style.pointerEvents = 'none';
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="width:16px;height:16px;"></span> Beralih...';
            }
            fetch(SWITCH_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ user_id: userId, switch_token: token }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    var accounts = getSavedAccounts();
                    var idx = accounts.findIndex(a => a.id === data.user_id);
                    if (idx >= 0) { accounts[idx].switch_token = data.new_token; accounts[idx].photo = data.photo; }
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
                    window.location.href = data.redirect;
                } else {
                    alert('Gagal beralih akun: ' + data.message);
                    renderSavedAccounts();
                }
            }).catch(() => {
                alert('Terjadi kesalahan jaringan.');
                renderSavedAccounts();
            });
        }

        // ===== INITIALIZATION =====
        (function() {
            var initUserLayout = function() {
                // Refresh CSRF Token and User Data from the newly loaded page
                // (This is important because Turbo only replaces the body/title)
                CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || CSRF_TOKEN;

                // Restore sidebar state (html class may already be set in <head>)
                if (localStorage.getItem('user_sidebar_mini') === 'true' && window.innerWidth >= 992) {
                    document.getElementById('userSidebar')?.classList.add('is-mini');
                } else {
                    document.getElementById('userSidebar')?.classList.remove('is-mini');
                    document.documentElement.classList.remove('is-sidebar-mini');
                }
                syncSidebarMiniState();

                // Update active sidebar items
                var currentPath = window.location.pathname;
                var currentSearch = window.location.search;
                var currentFull = currentPath + currentSearch;
                
                var bestScore = -1;
                var sidebarItems = document.querySelectorAll('.user-sidebar-item, .user-sidebar-cta');
                
                // First pass: find the best score
                sidebarItems.forEach(item => {
                    var href = item.getAttribute('href');
                    if (!href || href === '#') return;
                    
                    try {
                        var url = new URL(href, window.location.origin);
                        var linkPath = url.pathname;
                        var linkSearch = url.search;
                        var score = -1;

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

                // Second pass: apply classes
                sidebarItems.forEach(item => {
                    var score = parseInt(item.getAttribute('data-active-score') || -1);
                    if (score > 0 && score === bestScore) {
                        item.classList.add('is-active');
                    } else {
                        item.classList.remove('is-active');
                    }
                });

                saveCurrentAccount();
                renderSavedAccounts();

                closeUserSidebar();
            };

            if (!window.__userLayoutEventsBound) {
                window.__userLayoutEventsBound = true;
                document.addEventListener('turbo:load', initUserLayout);
                document.addEventListener('click', function (e) {
                    var item = e.target.closest('.dropdown-item');
                    if (!item) return;
                    var dropdown = item.closest('.dropdown-menu');
                    if (dropdown && window.innerWidth >= 992) {
                        dropdown.style.display = 'none';
                        setTimeout(function () { dropdown.style.display = ''; }, 100);
                    }
                });
            }
            initUserLayout();
        })();

        // ===== NOTIFICATION FUNCTIONS =====
        var markNotifRead = function(id) {
            var item = document.querySelector(`.notif-item-wrapper[data-id="${id}"]`);
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
                        item.classList.remove('unread');
                        var dot = item.querySelector('.unread-dot, .badge-baru');
                        if(dot) dot.remove();
                        var readBtn = item.querySelector('.btn-notif-action.text-primary, button[id^="btnRead-"]');
                        if(readBtn) readBtn.remove();
                        var link = item.querySelector('.notif-item');
                        if(link) link.classList.remove('unread');
                    }
                    updateNotifBadges(data.unreadCount);
                }
            })
            .catch(err => {
                console.error(err);
                if(item) item.style.opacity = '1';
            });
        };

        var deleteNotif = function(id) {
            if(!confirm('Hapus notifikasi ini?')) return;
            var item = document.querySelector(`.notif-item-wrapper[data-id="${id}"]`);
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
        };

        var updateNotifBadges = function(count) {
            document.querySelectorAll('.notif-badge').forEach(badge => {
                if(count > 0) {
                    badge.style.display = 'flex';
                    badge.textContent = count > 9 ? '9+' : count;
                } else {
                    badge.style.display = 'none';
                }
            });
            document.querySelectorAll('.user-sidebar-nav-badge').forEach(badge => {
                if(count > 0) {
                    badge.style.display = 'block';
                    badge.textContent = count > 99 ? '99+' : count;
                } else {
                    badge.style.display = 'none';
                }
            });
        };

        // ===== SMOOTH PAGE NAVIGATION (Turbo Drive) =====
        if (!window.__userTurboNavBound) {
            window.__userTurboNavBound = true;

            document.addEventListener('turbo:before-visit', function () {
                document.documentElement.classList.add('turbo-loading');
            });

            document.addEventListener('turbo:before-render', function () {
                document.documentElement.classList.add('turbo-loading');
                document.querySelectorAll('.modal-backdrop').forEach(function (el) { el.remove(); });
                document.querySelectorAll('.modal.show').forEach(function (el) {
                    el.classList.remove('show');
                    el.style.display = '';
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });

            document.addEventListener('turbo:render', function () {
                document.documentElement.classList.remove('turbo-loading');
                var shell = document.getElementById('userShell');
                if (shell) {
                    shell.classList.remove('is-entering');
                    void shell.offsetWidth;
                    shell.classList.add('is-entering');
                    setTimeout(function () { shell.classList.remove('is-entering'); }, 320);
                }
                window.scrollTo({ top: 0, behavior: 'auto' });
            });

            document.addEventListener('turbo:load', function () {
                document.documentElement.classList.remove('turbo-loading');
                document.querySelectorAll('form[enctype="multipart/form-data"]').forEach(function (f) {
                    f.setAttribute('data-turbo', 'false');
                });
                document.querySelectorAll('a[download], a[target="_blank"]').forEach(function (a) {
                    a.setAttribute('data-turbo', 'false');
                });
            });

            document.addEventListener('turbo:submit-start', function (e) {
                var form = e.target;
                if (form && form.enctype === 'multipart/form-data') {
                    e.detail.formSubmission.stop();
                    form.removeAttribute('data-turbo');
                    form.submit();
                    return;
                }
                var modal = form ? form.closest('.modal') : null;
                if (modal) {
                    var instance = bootstrap.Modal.getInstance(modal);
                    if (instance) instance.hide();
                }
            });
        }

        function showPublicVerificationModal(e) {
            if (e) e.preventDefault();
            Swal.fire({
                title: '🔒 Verifikasi Keaslian Surat',
                html: `
                    <div class="text-start" style="font-size:13px; color:#475569;">
                        <p class="mb-3">Masukkan Kode UUID verifikasi surat untuk memvalidasi keaslian surat langsung dari sistem resmi.</p>
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold mb-1" style="font-size:12px;color:#1e293b;">Kode UUID Surat</label>
                            <input type="text" id="swal-verification-uuid" class="form-control py-2 px-3" 
                                   placeholder="Contoh: 123e4567-e89b-12d3-a456-426614174000" 
                                   style="border-radius:10px; font-size:13px; background:rgba(255,255,255,0.7); border:1px solid #cbd5e1;">
                        </div>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-shield-check me-1"></i> Verifikasi Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#1e3a5f',
                cancelButtonColor: '#64748b',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: `rgba(15, 23, 42, 0.35)`,
                customClass: {
                    popup: 'rounded-4 shadow-xl border border-white',
                    confirmButton: 'px-4 py-2 text-sm fw-bold',
                    cancelButton: 'px-4 py-2 text-sm fw-semibold'
                },
                preConfirm: () => {
                    const uuid = Swal.getPopup().querySelector('#swal-verification-uuid').value.trim();
                    if (!uuid) {
                        Swal.showValidationMessage('Kode UUID surat wajib diisi!');
                        return false;
                    }
                    const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
                    if (!uuidRegex.test(uuid)) {
                        Swal.showValidationMessage('Format Kode UUID tidak valid!');
                        return false;
                    }
                    return { uuid: uuid };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/v/${result.value.uuid}`;
                }
            });
        }
        window.showPublicVerificationModal = showPublicVerificationModal;
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
    <div class="offcanvas-body">
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
                <i class="bi bi-bell-slash text-muted" style="font-size: 40px; opacity: 0.3;"></i>
                <p class="text-muted mt-3 small">Belum ada notifikasi baru untuk Anda.</p>
            </div>
        @endforelse
    </div>
    <div class="p-3 border-top text-center bg-light bg-opacity-50">
        <a href="{{ route('user.notifikasi.index') }}" class="text-decoration-none small fw-bold" style="color: #3b82f6;">
            Buka Notifikasi Lainnya <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>

<script>
    window.NOTIF_CONFIG = {
        urlPoll: '{{ route("notif.poll") }}',
        urlRead: '/notif/mark-read/',
        urlReadAll: '{{ route("notif.readAll") }}',
        urlDelete: '/notif/delete/',
        urlDeleteAll: '{{ route("notif.deleteAll") }}',
        csrf: '{{ csrf_token() }}',
        pollInterval: false // AJAX Polling disabled as per user request
    };
</script>
<script src="{{ asset('js/notif-system.js') }}" defer></script>

</body>
</html>