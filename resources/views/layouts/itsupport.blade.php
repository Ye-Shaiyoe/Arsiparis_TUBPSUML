<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Panel - {{ config('app.name', 'Surat BP Suml') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #1e3a5f;
            --primary-light: #eff6ff;
            --sidebar-bg: #111827;
            --sidebar-hover: #1f2937;
            --sidebar-text: #9ca3af;
            --sidebar-active: #ffffff;
            --bg-main: #f8fafc;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-main);
            color: #1e293b;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 50;
        }

        .sidebar-header {
            padding: 30px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-box {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
        }

        .sidebar-menu {
            flex: 1;
            padding: 10px 15px;
            overflow-y: auto;
        }

        .menu-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #4b5563;
            padding: 20px 15px 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-active);
        }

        .menu-item.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        .menu-item i {
            font-size: 18px;
        }

        /* Main Content */
        #main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        header {
            height: 75px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            z-index: 40;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary);
        }

        #content-wrapper {
            flex: 1;
            padding: 35px;
            overflow-y: auto;
        }

        /* Utility */
        .badge-itsupport {
            background: #fef2f2;
            color: #dc2626;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid #374151;
            color: #9ca3af;
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
            font-weight: 600;
        }

        .logout-btn:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }
    </style>
</head>
<body>

    <aside id="sidebar">
        <div class="sidebar-header">
            <div class="logo-box">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <div style="font-weight: 700; color: white; font-size: 16px; line-height: 1;">IT Panel</div>
                <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">Systems Support</div>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Console</div>
            <a href="{{ route('itsupport.dashboard') }}" class="menu-item active">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="#" class="menu-item">
                <i class="bi bi-code-square"></i> System Config
            </a>
            <a href="#" class="menu-item">
                <i class="bi bi-database-fill-gear"></i> Database Tools
            </a>
            
            <div class="menu-label">Monitoring</div>
            <a href="#" class="menu-item">
                <i class="bi bi-journal-text"></i> System Logs
            </a>
            <a href="#" class="menu-item">
                <i class="bi bi-hdd-stack-fill"></i> Server Health
            </a>

            <div style="margin-top: 40px; padding: 0 15px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div id="main">
        <header>
            <div class="d-flex align-items-center gap-3">
                <div style="color: #94a3b8; font-size: 20px;"><i class="bi bi-list"></i></div>
                <h4 style="margin: 0; font-size: 18px; font-weight: 600;">{{ $title ?? 'Control Center' }}</h4>
            </div>

            <div class="user-profile">
                <div class="text-end">
                    <div style="font-weight: 600; font-size: 14px; color: #1e293b;">{{ Auth::user()->name }}</div>
                    <div class="badge-itsupport">Technical Support</div>
                </div>
                <div class="avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main id="content-wrapper">
            @yield('content')
        </main>
    </div>

</body>
</html>
