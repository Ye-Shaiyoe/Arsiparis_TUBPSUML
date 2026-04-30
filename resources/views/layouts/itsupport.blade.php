<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support - {{ config('app.name', 'Surat Metrologi') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #f3f4f6;
            --bg-secondary: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --border-color: #e5e7eb;
            --sidebar-bg: #111827;
            --sidebar-hover: #1f2937;
            --sidebar-text: #9ca3af;
            --sidebar-active: #ffffff;
            --topbar-bg: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        #sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .menu-label {
            padding: 0 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
        }

        .menu-item:hover, .menu-item.active {
            background: var(--sidebar-hover);
            color: var(--sidebar-active);
        }

        .menu-icon {
            margin-right: 12px;
            font-size: 16px;
        }

        #main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #topbar {
            height: 60px;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        #content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .card {
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    <aside id="sidebar">
        <div class="sidebar-logo">
            <span style="color: white; font-weight: bold; font-size: 18px;">IT Support Panel</span>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Main</div>
            <a href="{{ route('itsupport.dashboard') }}" class="menu-item active">
                <span class="menu-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
            </a>
            
            <div style="margin-top:auto; padding: 20px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; background: #ef4444; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div id="main">
        <header id="topbar">
            <div style="font-weight: 600;">{{ $title ?? 'Dashboard' }}</div>
            <div>
                <span>{{ Auth::user()->name }} (IT Support)</span>
            </div>
        </header>

        <main id="content">
            @yield('content')
        </main>
    </div>

</body>
</html>
