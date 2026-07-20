<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPSUML — Balai Pengelolaan Standar Ukuran Metrologi Legal</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&family=DM+Mono:wght@300;400&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v=7">
</head>

<body>

    {{-- MOBILE MENU BACKDROP --}}
    <div id="mobile-menu-backdrop"></div>

    {{-- MOBILE MENU --}}
    <div id="mobile-menu">
        <a href="{{ url('/?home=1') }}" class="mobile-link" style="transition-delay: 0.05s;">Beranda</a>
        <a href="#about" class="mobile-link" style="transition-delay: 0.1s;">Tentang</a>
        <a href="#stats" class="mobile-link" style="transition-delay: 0.15s;">Statistik</a>
        <a href="#charts" class="mobile-link" style="transition-delay: 0.2s;">Grafik</a>
        <a href="#portals" class="mobile-link" style="transition-delay: 0.25s;">Portal</a>
        <a href="#features-scroller" class="mobile-link" style="transition-delay: 0.3s;">Fitur</a>
        <a href="#alur-kerja" class="mobile-link" style="transition-delay: 0.35s;">Alur</a>
        <a href="#developer" class="mobile-link" style="transition-delay: 0.4s;">Developer</a>
        <a href="#footer" class="mobile-link" style="transition-delay: 0.45s;">Kontak</a>
        <div style="width: 100%; height: 1px; background: var(--glass-border); margin: 8px 0; opacity: 0.5;"></div>
        @auth
            <a href="{{ route('dashboard') }}" class="mobile-link" style="color: var(--accent); font-weight: 600; transition-delay: 0.48s;">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mobile-link" style="color: var(--accent); font-weight: 600; transition-delay: 0.48s;">Sign In</a>
            <a href="{{ route('register') }}" class="mobile-link" style="color: var(--accent-gold); font-weight: 600; transition-delay: 0.52s;">Sign Up</a>
        @endauth
    </div>
    <div id="scroll-bar"></div>
    <canvas id="particles-canvas"></canvas>
    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    {{-- VIDEO BACKGROUND --}}
    <video id="bg-video" loop muted playsinline preload="none">
        <source data-src="{{ asset('videos/background.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    {{-- NAVBAR --}}
    <nav id="navbar">
        <a href="#" class="nav-logo" style="display: flex; align-items: center;">
            <img src="{{ asset('images/BP_SUML2.png') }}" alt="BPSUML"
                style="height: 36px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
        </a>
        <ul class="nav-center">
            <li><a href="{{ url('/?home=1') }}">Beranda</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#stats">Statistik</a></li>
            <li><a href="#spiral-section">Jenis</a></li>
            <li><a href="#features-scroller">Fitur</a></li>
            <li><a href="#alur-kerja">Alur</a></li>
            <li><a href="#developer">Developer</a></li>
            <li><a href="#footer">Kontak</a></li>
            <li><a href="{{ route('panduan') }}" target="_blank" style="font-weight:700;color:#06b6d4;">📖 Panduan</a></li>
        </ul>
        <div class="nav-auth">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-user-pill">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Dashboard</span>
                    </div>
                    <div class="user-avatar-small">
                        @if(Auth::user()->getAvatarUrl())
                            <img src="{{ Auth::user()->getAvatarUrl() }}" alt="Profile">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-btn-login">Sign In</a>
                <a href="{{ route('register') }}" class="nav-btn-register">Sign Up</a>
            @endauth
        </div>

        <button class="nav-mobile-toggle" id="menu-toggle">
            <span class="hamburger"></span>
        </button>
    </nav>

    {{-- HERO --}}
    <section id="hero">
        <div class="hero-eyebrow">
            <span class="hero-eyebrow-line"></span>
            Sistem Persuratan Digital
        </div>
        <h1 class="hero-title" id="hero-title-main">
            <span class="line">Balai Pengelolaan</span>
            <span class="line"><em>Standar Ukuran</em></span>
            <span class="line">Metrologi Legal</span>
        </h1>
        <p class="hero-subtitle" id="hero-subtitle-main">
            Sistem Monitoring dan Pengelolaan Persuratan Balai Pengelolaan Standar Ukuran Metrologi Legal — Efisien,
            Efektif, Sistematis Serta Mudah Diakses Kapan Saja.
        </p>
        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn-primary">
                <span class="btn-primary-liquid"></span>
                <span class="btn-text-inner" data-hover="Masuk Ke Web">
                    <span>Masuk Ke Web</span>
                </span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
            <a href="{{ route('register') }}" class="btn-secondary">
                <span class="btn-secondary-liquid"></span>
                <span class="btn-text-inner" data-hover="Daftar Akun">
                    <span>Daftar Akun</span>
                </span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8zM19 8v6M22 11h-6" />
                </svg>
            </a>
            <div class="cta-divider"></div>
            <a href="#about" class="btn-ghost">
                Pelajari lebih lanjut
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="hero-float-cards">
            <div class="hero-float-card">
                <div class="hero-float-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg></div>
                <div>
                    <div class="hero-float-text">10 Tahap Approval</div>
                    <div class="hero-float-sub">Alur kerja terstruktur</div>
                </div>
            </div>
            <div class="hero-float-card">
                <div class="hero-float-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></div>
                <div>
                    <div class="hero-float-text">SLA 30 Jam Kerja</div>
                    <div class="hero-float-sub">Real-time countdown</div>
                </div>
            </div>
            <div class="hero-float-card">
                <div class="hero-float-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg></div>
                <div>
                    <div class="hero-float-text">Verifikasi QR Code</div>
                    <div class="hero-float-sub">Autentikasi dokumen</div>
                </div>
            </div>
        </div>
        <div class="hero-badge">
            <span class="hero-badge-inner">{{ date('Y') }}</span>
            <span class="hero-badge-label">Manajemen surat<br>BPSUML</span>
        </div>
        <div class="hero-scroll-hint">
            <div class="scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    {{-- TICKER --}}
    <div class="ticker-wrap">
        <div class="ticker-track">
            @php
                $items = ['Surat Masuk', 'Surat Keluar', 'Chart Data', 'Nota Dinas', 'Pengelolaan Surat', 'Pengajuan Surat', 'Notifikasi Realtime', 'Template Surat', 'Dokumen Teknis', 'Status Surat', 'Tracking Surat', 'Surat Revisi'];
            @endphp
            @foreach(array_merge($items, $items) as $item)
                <span class="ticker-item"><span class="ticker-dot"></span>{{ $item }}</span>
            @endforeach
        </div>
    </div>

    {{-- ABOUT --}}
    <section id="about">
        <div class="about-left" data-reveal>
            <div class="about-label">Tentang Kami</div>
            <h2 class="about-title">
                <span class="text-animate">
                    <span class="text-animate-word">Manajemen</span>
                    <span class="text-animate-word">Persuratan</span>
                    <span class="text-animate-word">Digital</span>
                    <span class="text-animate-word">Untuk</span>
                    <span class="text-animate-word"><em>BPSUML</em></span>
                </span>
            </h2>
            <p class="about-body">Balai Pengelolaan Standar Ukuran Metrologi Legal (BPSUML) merupakan unit pelaksana
                teknis di bawah Direktorat Metrologi, Kementerian Perdagangan RI, yang bertugas menyelenggarakan
                Pengelolaan, kalibrasi, dan tera ulang alat ukur.</p>
            <p class="about-body">Sistem manajemen persuratan digital ini hadir untuk mendukung tata kelola administrasi
                yang
                transparan, akuntabel, dan efisien dalam lingkungan pemerintahan yang modern.</p>
        </div>
        <div class="about-cards">
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="about-card-title">Sistem Persuratan Digital</div>
                <div class="about-card-body">Pengelolaan surat masuk, surat keluar, dan dokumen internal secara terpusat
                    dan terdigitalisasi.</div>
            </div>
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div class="about-card-title">Keamanan & Akuntabilitas</div>
                <div class="about-card-body">Sistem berbasis peran (role-based) dengan autentikasi aman dan log
                    aktivitas yang terekam lengkap.</div>
            </div>
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                <div class="about-card-title">Statistik & Pelaporan</div>
                <div class="about-card-body">Visualisasi data surat secara real-time untuk mendukung pengambilan
                    keputusan berbasis data.</div>
            </div>
        </div>
    </section>



    {{-- STATS --}}
    <section id="stats" style="position: relative; overflow: hidden;">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 300px; background: radial-gradient(ellipse, rgba(200, 169, 110, 0.06) 0%, transparent 70%); z-index: 0; pointer-events: none;">
        </div>
        <div class="stats-header" style="position: relative; z-index: 2;">
            <div class="stats-label">Statistik</div>
            <h2 class="stats-title">Data <em>Surat</em> Terkini</h2>
        </div>
        <div class="stats-grid" style="position: relative; z-index: 2;">
            <div class="stat-card">
                <span class="stat-type">Masuk</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalSuratMasuk }}">0</div>
                <div class="stat-label">Total Surat<br> Masuk</div>
                <span class="stat-trend positive">↑ 12% Minggu Ini</span>
            </div>
            <div class="stat-card">
                <span class="stat-type">Selesai</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalDokumenTerarsip }}">0</div>
                <div class="stat-label">Total Surat<br> Selesai</div>
                <span class="stat-trend positive">SLA 100% On-Time</span>
            </div>
            <div class="stat-card">
                <span class="stat-type">Aktif</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.978 11.978 0 0112 20.25a11.978 11.978 0 01-3-1.013v-.11M8.284 15.633a4.125 4.125 0 00-7.533 2.493 9.337 9.337 0 004.117.953 9.38 9.38 0 002.625-.372M21 7.5a3 3 0 11-6 0 3 3 0 016 0zM7.5 7.5a3 3 0 11-6 0 3 3 0 016 0zM12 10.5a3 3 0 110-6 3 3 0 010 6z" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalPengguna }}">0</div>
                <div class="stat-label">Pengguna<br>Terdaftar</div>
                <span class="stat-trend neutral">Pegawai Aktif</span>
            </div>
            <div class="stat-card">
                <span class="stat-type">Rating</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon" style="color: #f59e0b;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499c.15-.358.5-.6.87-.6s.72.242.87.6l1.867 4.475a.722.722 0 00.548.423l4.697.518c.397.044.555.52.282.788l-3.565 3.325a.722.722 0 00-.23.708l1.01 4.545c.085.385-.308.67-.665.485L12 16.747a.722.722 0 00-.67 0l-4.108 2.222c-.357.185-.75-.1-.665-.485l1.01-4.545a.722.722 0 00-.23-.708L3.982 10.61c-.273-.268-.115-.744.282-.788l4.697-.518a.722.722 0 00.548-.423L11.48 3.5z" />
                    </svg>
                </div>
                <div style="display: flex; align-items: baseline; gap: 4px; justify-content: center; position: relative; z-index: 2;">
                    <div class="stat-number-rating" id="rating-number" data-target="{{ $averageRating }}">0.0</div>
                    <span style="font-family: var(--font-display); font-size: 24px; font-weight: 300; color: var(--muted2);">/ 5</span>
                </div>
                <div class="stat-label">Rating Pelayanan</div>
                <span class="stat-trend positive" style="display: inline-flex; align-items: center; gap: 4px; color: #f59e0b !important;">
                    <i class="bi bi-star-fill" style="color: #f59e0b;"></i> Kepuasan Layanan
                </span>
            </div>
        </div>
    </section>

    <!-- 3D SPIRAL SCROLL SECTION -->
    <section id="spiral-section">
        <div class="spiral-sticky">
            <div class="spiral-bg-gradient"></div>
            <div class="spiral-stage" id="spiral-stage">
                <div class="spiral-label-center" id="spiral-center">
                    <div class="slc-num">06 — Fitur Unggulan</div>
                    <h2>Scroll untuk<br><em>Jelajahi Fitur</em></h2>
                    <p>Semua fitur sistem persuratan BPSUML dalam satu tampilan interaktif</p>
                </div>
                <div id="spiral-items-container"></div>
                <div class="spiral-progress" id="spiral-progress"></div>
            </div>
        </div>
    </section>



    {{-- CHARTS --}}
    <section id="charts">
        <div class="charts-header" data-reveal>
            <div class="charts-label">Analitik</div>
            <h2 class="charts-title">
                <span class="text-animate">
                    <span class="text-animate-word">Grafik</span>
                    <span class="text-animate-word">&</span>
                    <span class="text-animate-word"><em>Statistik</em></span>
                    <span class="text-animate-word">Sistem</span>
                </span>
            </h2>
            <p class="charts-subtitle">Visualisasi data surat, kepatuhan SLA, distribusi jenis dokumen, dan tren bulanan
                secara real-time.</p>
        </div>
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Tren Surat & Kepatuhan SLA</div>
                        <div class="chart-card-sub">6 bulan terakhir · mixed chart</div>
                    </div> <span class="chart-badge {{ $growth >= 0 ? 'up' : 'down' }}">
                        {{ $growth >= 0 ? '↑' : '↓' }} {{ abs($growth) }}%
                    </span>
                </div>
                <div class="chart-legend"><span class="legend-item"><span class="legend-dot"
                            style="background:#C8A96E"></span>Surat Masuk</span><span class="legend-item"><span
                            class="legend-dot" style="background:#1D9E75"></span>Surat Keluar</span><span
                        class="legend-item"><span class="legend-dot"
                            style="background:rgba(93,202,165,0.7);border:1px solid #5DCAA5"></span>SLA Rate %</span>
                </div>
                <div style="position:relative;width:100%;height:240px"><canvas id="chartMixed"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Distribusi Jenis Surat</div>
                        <div class="chart-card-sub">Proporsi per kategori</div>
                    </div> <span class="chart-badge">{{ count($doughnutLabels) }} jenis</span>
                </div>
                <div id="doughnut-legend" class="chart-legend" style="flex-wrap:wrap;gap:8px 14px"></div>
                <div style="position:relative;width:100%;height:220px"><canvas id="chartDoughnut"></canvas></div>
            </div>
        </div>
        <div class="charts-grid-bottom">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Aktivitas Arsip 12 Bulan</div>
                        <div class="chart-card-sub">Surat masuk vs keluar · stacked area</div>
                    </div><span class="chart-badge warn">{{ date('Y') }}</span>
                </div>
                <div class="chart-legend"><span class="legend-item"><span class="legend-dot"
                            style="background:#C8A96E;opacity:.7"></span>Masuk</span><span class="legend-item"><span
                            class="legend-dot" style="background:#378ADD;opacity:.7"></span>Keluar</span><span
                        class="legend-item"><span class="legend-dot"
                            style="background:#1D9E75;opacity:.7"></span>Selesai</span></div>
                <div style="position:relative;width:100%;height:240px"><canvas id="chartArea"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Kepatuhan SLA per Jenis</div>
                        <div class="chart-card-sub">% tepat waktu · bulan ini</div>
                    </div><span class="chart-badge up">SLA</span>
                </div>
                <div class="sla-list" id="sla-list" style="margin-top:8px"></div>
            </div>
        </div>
    </section>

    {{-- PORTALS --}}
    <section id="portals">
        <div class="portals-header" data-reveal>
            <div class="portals-label">Ekosistem</div>
            <h2 class="portals-title">Layanan <em>Terkait</em></h2>
            <p class="portals-subtitle">Akses cepat ke portal dan aplikasi pendukung lainnya di lingkungan BPSUML.</p>
        </div>
        <div class="portals-grid">
            @php
                $portals = [
                    ['name' => 'SIMET', 'desc' => 'Sistem Informasi Metrologi', 'img' => 'logo.png', 'url' => 'https://metrologi.kemendag.go.id/'],
                    ['name' => 'KEMENDAG', 'desc' => 'Kementerian Perdagangan', 'img' => 'kemendag.png', 'url' => 'https://www.kemendag.go.id/'],
                    ['name' => 'SPBE', 'desc' => 'Informasi Aplikasi SISWASPK', 'img' => 'logo.png', 'url' => 'https://simpktn.kemendag.go.id/index.php/siswaspk'],
                    ['name' => 'PPID', 'desc' => 'Informasi, Pelaporan & Dokumentasi', 'img' => 'kemendag.png', 'url' => 'https://metrologi.kemendag.go.id/pelaporan_ttu/web/home'],
                    ['name' => 'ASPIRASI', 'desc' => 'Layanan Pengaduan Online', 'img' => 'logo.png', 'url' => route('user.aspirasi.index')],
                    ['name' => 'About BPSUML', 'desc' => 'Sistem BPSUML', 'img' => 'kemendag.png', 'url' => 'https://metrologi.kemendag.go.id/master_suml/']
                ];
            @endphp
            @foreach($portals as $p)
                <a href="{{ $p['url'] }}" target="_blank" class="portal-card">
                    <div class="portal-icon"><img src="{{ asset('images/portals/' . $p['img']) }}" alt="{{ $p['name'] }}"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($p['name']) }}&background=1A73E8&color=FFFFFF'">
                    </div>
                    <div class="portal-info">
                        <div class="portal-name">{{ $p['name'] }}</div>
                        <div class="portal-desc">{{ $p['desc'] }}</div>
                    </div>
                    <div class="portal-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg></div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- HORIZONTAL FEATURES SHOWCASE (Diperbaiki) --}}
    <section id="features-scroller">
        <div class="features-sticky-wrap">
            <div class="features-horizontal-track" id="features-track">
                <div class="features-main-line"></div>
                <!-- Intro slide -->
                <div class="feature-slide first-slide">
                    <div class="feature-content">
                        <div class="feature-label">Fitur Unggulan</div>
                        <h2 class="feature-display-title">Eksplorasi<br><em>Ekosistem</em> Kami</h2>
                        <p class="feature-desc">Sistem yang dirancang untuk efisiensi, transparansi, dan keamanan data
                            dalam setiap proses administrasi persuratan.</p>
                        <div class="feature-hint"><span>Scroll untuk eksplorasi</span>
                            <div class="hint-line"></div>
                        </div>
                    </div>
                </div>
                                <!-- Slide 2: SLA Visual (Circular Chronometer) -->
                <div class="feature-slide">
                    <div class="feature-visual-wrap">
                        <div class="feature-visual-side" style="display: flex; justify-content: center;">
                            <div class="timer-circle-wrap">
                                <div class="timer-circle-bg"></div>
                                <div class="timer-progress-ring"></div>
                                <div class="timer-display">
                                    <div class="timer-val">23:54:12</div>
                                    <div
                                        style="font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 2px;">
                                        Sisa Waktu</div>
                                </div>
                            </div>
                        </div>
                        <div class="feature-text-side">
                            <div class="feature-card-num">01</div>
                            <h3 class="feature-card-title">Akurasi Waktu Pelayanan</h3>
                            <p class="feature-card-text">Setiap dokumen dipantau dengan presisi melalui sistem
                                SLA 30 jam. Memastikan komitmen pelayanan tetap terjaga dan akuntabel.</p>
                            <div class="feature-card-footer">Chronometer Monitoring</div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: QR Code Visual -->
                <div class="feature-slide wide">
                    <div class="feature-visual-wrap">
                        <div class="feature-text-side">
                            <div class="feature-card-num">02</div>
                            <h3 class="feature-card-title">Verifikasi QR Code</h3>
                            <p class="feature-card-text">Keaslian dokumen terjamin , bisa di scan melalui HandPhone(HP)
                                dan
                                verifikasi QR Code yang terintegrasi langsung dengan database sistem.</p>
                            <div class="feature-card-footer">Keamanan Berstandar</div>
                        </div>
                        <div class="feature-visual-side">
                            <div class="doc-preview">
                                <div class="scan-bar"></div>
                                <div class="doc-line"
                                    style="width: 40%; height: 10px; background: #ddd; margin-bottom: 25px;"></div>
                                <div class="doc-line"></div>
                                <div class="doc-line"></div>
                                <div class="doc-line" style="width: 80%;"></div>
                                <div class="doc-line"></div>
                                <div class="doc-line" style="width: 60%;"></div>
                                <div class="doc-qr">
                                    <svg viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v2h-2v-2zm3 3h3v2h-3v-2zm-3 3h2v2h-2v-2zm3-3h3v2h-3v-2zm-3 3h2v2h-2v-2zm3 0h3v2h-3v-2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: Digitalisasi (Floating Stack) -->
                <div class="feature-slide wide">
                    <div class="feature-visual-wrap">
                        <div class="feature-text-side">
                            <div class="feature-card-num">03</div>
                            <h3 class="feature-card-title">Arsip Digital Terpadu</h3>
                            <p class="feature-card-text">Ubah tumpukan kertas menjadi aset digital yang terorganisir.
                                Seluruh dokumen terenkripsi dan tersimpan dalam ekosistem cloud yang aman.</p>
                            <div class="feature-card-footer">Cloud-Based Archiving</div>
                        </div>
                        <div class="feature-visual-side" style="display: flex; justify-content: center;">
                            <div class="archive-stack">
                                <div class="archive-item">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div class="doc-skeleton" style="width: 80%;"></div>
                                    <div class="doc-skeleton" style="width: 60%;"></div>
                                    <div class="doc-skeleton" style="width: 90%;"></div>
                                </div>
                                <div class="archive-item">
                                    <i class="bi bi-file-earmark-word"></i>
                                    <div class="doc-skeleton"></div>
                                    <div class="doc-skeleton"></div>
                                </div>
                                <div class="archive-item">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <div class="doc-skeleton" style="width: 70%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Final CTA -->
                <div class="feature-slide last-slide">
                    <div class="feature-content">
                        <h2 class="feature-display-title">Siap Mencoba?</h2>
                        <p class="feature-desc">Masuk ke dashboard untuk mulai mengelola surat Anda.</p>
                        <div style="margin-top: 2rem;">
                            <a href="{{ route('login') }}" class="btn-primary">
                                <span class="btn-primary-liquid"></span>
                                <span class="btn-text-inner" data-hover="Buka Dashboard">
                                    <span>Buka Dashboard</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SLEEK HORIZONTAL SCROLL PROGRESS INDICATOR -->
            <div class="features-progress-bar-wrap">
                <div class="features-progress-bar-inner" id="features-progress-bar"></div>
            </div>
        </div>
    </section>

    {{-- ALUR KERJA PERSURATAN (Timeline) VERTICAL SCROLL --}}
    <section id="alur-kerja">
        <div class="alur-kerja-container">
            <div class="alur-header" data-reveal>
                <div class="alur-label">Alur Kerja</div>
                <h2 class="alur-title">Alur <em>Persuratan</em> Digital</h2>
                <p class="alur-subtitle">Prosedur pengelolaan persuratan dari awal hingga akhir dokumen terarsip secara sistematis.</p>
            </div>
            <div class="alur-timeline">
                <div class="alur-line-track">
                    <div class="alur-line-fill"></div>
                </div>

                {{-- Tahap 1 --}}
                <div class="alur-step alur-left">
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 01</span>
                            <span class="alur-card-emoji">📤</span>
                        </div>
                        <h3>Usulan Diajukan</h3>
                        <p>Pengaju membuat usulan surat baru beserta draf dokumen dan data pendukung.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator user"></span>
                            Pengaju Surat
                        </div>
                    </div>
                    <div class="alur-dot">
                        <span>1</span>
                    </div>
                    <div class="alur-spacer"></div>
                </div>

                {{-- Tahap 2 --}}
                <div class="alur-step alur-right">
                    <div class="alur-spacer"></div>
                    <div class="alur-dot">
                        <span>2</span>
                    </div>
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 02</span>
                            <span class="alur-card-emoji">🔍</span>
                        </div>
                        <h3>Verifikasi Arsiparis</h3>
                        <p>Arsiparis memeriksa kelengkapan berkas dan kesesuaian format surat yang diajukan.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator admin-tu"></span>
                            Arsiparis
                        </div>
                    </div>
                </div>

                {{-- Tahap 3 --}}
                <div class="alur-step alur-left">
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 03</span>
                            <span class="alur-card-emoji">🏢</span>
                        </div>
                        <h3>Verifikasi Kasubbag</h3>
                        <p>Kepala Subbagian Tata Usaha memeriksa substansi dan memberikan rekomendasi persetujuan.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator admin"></span>
                            Kasubbag TU
                        </div>
                    </div>
                    <div class="alur-dot">
                        <span>3</span>
                    </div>
                    <div class="alur-spacer"></div>
                </div>

                {{-- Tahap 4 --}}
                <div class="alur-step alur-right">
                    <div class="alur-spacer"></div>
                    <div class="alur-dot">
                        <span>4</span>
                    </div>
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 04</span>
                            <span class="alur-card-emoji">✍️</span>
                        </div>
                        <h3>Persetujuan Kepala Balai</h3>
                        <p>Kepala Balai memberikan persetujuan akhir atau disposisi terhadap usulan surat.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator admin-kaplai"></span>
                            Kepala Balai
                        </div>
                    </div>
                </div>

                {{-- Tahap 5 --}}
                <div class="alur-step alur-left">
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 05</span>
                            <span class="alur-card-emoji">🔢</span>
                        </div>
                        <h3>Penomoran Surat</h3>
                        <p>Sistem atau Arsiparis memberikan nomor surat resmi yang valid sesuai klasifikasi arsip.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator admin-tu"></span>
                            Arsiparis
                        </div>
                    </div>
                    <div class="alur-dot">
                        <span>5</span>
                    </div>
                    <div class="alur-spacer"></div>
                </div>

                {{-- Tahap 6 --}}
                <div class="alur-step alur-right">
                    <div class="alur-spacer"></div>
                    <div class="alur-dot">
                        <span>6</span>
                    </div>
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 06</span>
                            <span class="alur-card-emoji">🔏</span>
                        </div>
                        <h3>Tanda Tangan</h3>
                        <p>Penandatanganan dokumen secara elektronik menggunakan tanda tangan tersertifikasi.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator admin-kaplai"></span>
                            Arsiparis
                        </div>
                    </div>
                </div>

                {{-- Tahap 7 --}}
                <div class="alur-step alur-left">
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 07</span>
                            <span class="alur-card-emoji">📡</span>
                        </div>
                        <h3>Kirim TNDe</h3>
                        <p>Pengiriman surat eksternal ke sistem Tata Naskah Dinas elektronik secara otomatis.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator system"></span>
                            Sistem TNDe
                        </div>
                    </div>
                    <div class="alur-dot">
                        <span>7</span>
                    </div>
                    <div class="alur-spacer"></div>
                </div>

                {{-- Tahap 8 --}}
                <div class="alur-step alur-right">
                    <div class="alur-spacer"></div>
                    <div class="alur-dot">
                        <span>8</span>
                    </div>
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 08</span>
                            <span class="alur-card-emoji">📧</span>
                        </div>
                        <h3>Kirim Srikandi</h3>
                        <p>Integrasi dan sinkronisasi surat ke aplikasi Sistem Informasi Kearsipan Dinamis Terintegrasi.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator system"></span>
                            Sistem Srikandi
                        </div>
                    </div>
                </div>

                {{-- Tahap 9 --}}
                <div class="alur-step alur-left">
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 09</span>
                            <span class="alur-card-emoji">🗄️</span>
                        </div>
                        <h3>Pengarsipan</h3>
                        <p>Dokumen yang telah selesai diproses diarsipkan ke dalam sistem penyimpanan berkas digital.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator admin-tu"></span>
                            Arsiparis
                        </div>
                    </div>
                    <div class="alur-dot">
                        <span>9</span>
                    </div>
                    <div class="alur-spacer"></div>
                </div>

                {{-- Tahap 10 --}}
                <div class="alur-step alur-right">
                    <div class="alur-spacer"></div>
                    <div class="alur-dot">
                        <span>10</span>
                    </div>
                    <div class="alur-card">
                        <div class="alur-card-header">
                            <span class="alur-step-num">Tahap 10</span>
                            <span class="alur-card-emoji">✅</span>
                        </div>
                        <h3>Selesai</h3>
                        <p>Seluruh alur selesai, notifikasi akhir dikirim ke pengaju dan status surat menjadi 'Selesai'.</p>
                        <div class="alur-card-role">
                            <span class="alur-role-indicator system"></span>
                            Sistem Persuratan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DEVELOPER SECTION (Repositioned & Redesigned) --}}
    <section id="developer" class="dev-section">
        <div class="dev-header-minimal" data-reveal>
            <h3>Teknologi <em>Modern</em></h3>
            <p>Dibangun menggunakan stack teknologi mutakhir untuk memastikan performa tinggi, keamanan maksimal, dan
                skalabilitas jangka panjang.</p>
        </div>

        <div class="tm-wrap">
            <div class="tm-label">Architecture & Data</div>
            <div class="tm-track">
                <div class="tm-row go-right" id="tm-row1"></div>
            </div>

            <div class="tm-label">Interface & Experience</div>
            <div class="tm-track">
                <div class="tm-row go-left" id="tm-row2"></div>
            </div>

            <div class="tm-label">Environment & Intelligence</div>
            <div class="tm-track">
                <div class="tm-row go-right2" id="tm-row3"></div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS SECTION --}}
    @php
        $testimonialsGroup1 = [
            [
                'quote' => 'Sistem ini benar-benar mengubah cara kami mengelola dokumen. Proses yang biasanya memakan waktu berhari-hari kini hanya membutuhkan hitungan jam.',
                'avatar' => 'SY',
                'avatar_style' => '',
                'name' => 'Siti Yuliana',
                'role' => 'Admin Aspirasi',
                'stars' => 5
            ],
            [
                'quote' => 'Interface yang user-friendly membuat semua orang di divisi dapat menggunakan sistem tanpa kesulitan. Support tim juga sangat responsif.',
                'avatar' => 'AR',
                'avatar_style' => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);',
                'name' => 'Ahmad Riyanto',
                'role' => 'Kasubbag TU',
                'stars' => 4
            ],
            [
                'quote' => 'Fitur tracking real-time sangat membantu kami memantau progres dokumen. Notifikasi otomatis memastikan tidak ada yang terlewat.',
                'avatar' => 'HS',
                'avatar_style' => 'background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);',
                'name' => 'Hendra Setiawan',
                'role' => 'User - Pengaju Surat',
                'stars' => 5
            ]
        ];

        $testimonialsGroup2 = [
            [
                'quote' => 'Keamanan data terjamin dan SLA yang jelas membuat kami percaya untuk menggunakan sistem ini untuk dokumen penting.',
                'avatar' => 'DW',
                'avatar_style' => 'background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);',
                'name' => 'Dewi Wahyuningsih',
                'role' => 'Kepala Balai',
                'stars' => 5
            ],
            [
                'quote' => 'Dashboard analytics memberikan insight berharga tentang performa tim kami. Membantu dalam evaluasi dan improvement berkelanjutan.',
                'avatar' => 'RT',
                'avatar_style' => 'background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);',
                'name' => 'Rudi Taparia',
                'role' => 'Admin - IT Support',
                'stars' => 4
            ],
            [
                'quote' => 'Migrasi dari sistem lama sangat smooth. Tim support memberikan training yang excellent dan dokumentasi yang lengkap.',
                'avatar' => 'NI',
                'avatar_style' => 'background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;',
                'name' => 'Nurul Iffah',
                'role' => 'User - Pengaju Surat',
                'stars' => 5
            ]
        ];
    @endphp

    <section id="testimonials">
        <div class="testimonials-header" data-reveal>
            <div class="testimonials-label">Kepuasan Pengguna</div>
            <h2 class="testimonials-title">Apa kata mereka tentang <em>Persuratan Digital</em></h2>
            <p class="testimonials-subtitle">Ribuan pegawai telah merasakan manfaat sistem manajemen surat modern kami</p>
        </div>

        <div class="testimonials-container">
            <div class="testimonials-row row-left">
                <div class="testimonials-track track-1">
                    @foreach(array_merge($testimonialsGroup1, $testimonialsGroup1, $testimonialsGroup1) as $item)
                        <div class="testimonial-card">
                            <p class="testimonial-quote">{{ $item['quote'] }}</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar" style="{{ $item['avatar_style'] }}">{{ $item['avatar'] }}</div>
                                <div class="testimonial-info">
                                    <div class="testimonial-name">{{ $item['name'] }}</div>
                                    <div class="testimonial-role">{{ $item['role'] }}</div>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i > $item['stars'] ? 'empty' : '' }}">★</span>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="testimonials-row row-right">
                <div class="testimonials-track track-2">
                    @foreach(array_merge($testimonialsGroup2, $testimonialsGroup2, $testimonialsGroup2) as $item)
                        <div class="testimonial-card">
                            <p class="testimonial-quote">{{ $item['quote'] }}</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar" style="{{ $item['avatar_style'] }}">{{ $item['avatar'] }}</div>
                                <div class="testimonial-info">
                                    <div class="testimonial-name">{{ $item['name'] }}</div>
                                    <div class="testimonial-role">{{ $item['role'] }}</div>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i > $item['stars'] ? 'empty' : '' }}">★</span>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- SECURITY & COMPLIANCE BADGES --}}
    <section id="security">
        <div class="security-header" data-reveal>
            <div class="security-label">Standar Keamanan</div>
            <h2 class="security-title">Dibangun dengan <em>Keamanan Enterprise</em></h2>
            <p class="security-subtitle">Kami mematuhi standar keamanan internasional dan melindungi data Anda dengan teknologi terdepan</p>
        </div>

        <div class="security-grid">
            <div class="badge-card" data-badge="ssl">
                <div class="badge-icon">🔒</div>
                <div class="badge-name">SSL/TLS</div>
                <div class="badge-description">Enkripsi komunikasi end-to-end</div>
            </div>

            <div class="badge-card" data-badge="encryption">
                <div class="badge-icon">🛡️</div>
                <div class="badge-name">AES-256</div>
                <div class="badge-description">Enkripsi data tersimpan military-grade</div>
            </div>

            <div class="badge-card" data-badge="2fa">
                <div class="badge-icon">🔑</div>
                <div class="badge-name">2FA/MFA</div>
                <div class="badge-description">Autentikasi multi-faktor</div>
            </div>

            <div class="badge-card" data-badge="backup">
                <div class="badge-icon">💾</div>
                <div class="badge-name">Backup</div>
                <div class="badge-description">Backup otomatis & redundansi</div>
            </div>

            <div class="badge-card" data-badge="gdpr">
                <div class="badge-icon">⚖️</div>
                <div class="badge-name">GDPR Ready</div>
                <div class="badge-description">Kepatuhan regulasi privasi data</div>
            </div>

            <div class="badge-card" data-badge="iso">
                <div class="badge-icon">✅</div>
                <div class="badge-name">ISO 27001</div>
                <div class="badge-description">Manajemen keamanan informasi</div>
            </div>
        </div>

        <div class="security-footer">
            <div class="security-footer-content">
                <div class="security-footer-title">Audit & Monitoring 24/7</div>
                <p class="security-footer-desc">Sistem kami dimonitor secara real-time dengan logging lengkap untuk memastikan integritas dan keamanan data Anda setiap saat.</p>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer id="footer">
        <div class="footer-cta-band">
            <div class="footer-cta-text">Siap menggunakan<br><em>sistem arsip digital</em>?</div>
            <div class="footer-cta-btns">
                <a href="{{ route('login') }}" class="btn-primary">
                    <span class="btn-primary-liquid"></span>
                    <span class="btn-text-inner" data-hover="Sign In">
                        <span>Sign In</span>
                    </span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="btn-secondary">
                    <span class="btn-secondary-liquid"></span>
                    <span class="btn-text-inner" data-hover="Sign Up">
                        <span>Sign Up</span>
                    </span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="footer-grid">
            <div>
                <div class="footer-brief">Website Tracking Persuratan BPSUML &copy; {{ date('Y') }}</div>
                <div class="footer-brand">BPSUML</div>
                <p class="footer-desc">Balai Pengelolaan Standar Ukuran Metrologi Legal — unit pelaksana teknis
                    Direktorat Metrologi.</p>
                <address class="footer-address">Jl. Pasteur No. 27, Pasteur<br>Kec. Sukajadi, Kota Bandung<br>Jawa Barat
                    40161</address>
            </div>
            <div>
                <div class="footer-col-title">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#stats">Statistik</a></li>
                    <li><a href="#charts">Grafik</a></li>
                    <li><a href="{{ route('login') }}">Masuk Sistem</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Akses</div>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}">Sign In</a></li>
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Kontak</div>
                <ul class="footer-links">
                    <li><a href="mailto:tubpsuml@gmail.com"><i class="bi bi-envelope-at me-2"></i>tubpsuml@gmail.com</a>

                    </li>
                    <li><a href="https://metrologi.kemendag.go.id/" target="_blank"><i
                                class="bi bi-globe me-2"></i>metrologi.kemendag.go.id</a></li>
                </ul>
                <div class="footer-socials">
                    <a href="https://www.instagram.com/direktorat_metrologi/" target="_blank" class="social-link ig"
                        title="Instagram">
                        <img src="https://cdn.simpleicons.org/instagram/E4405F" alt="Instagram">
                    </a>
                    <a href="mailto:tubpsuml@gmail.com" class="social-link mail" title="Gmail">
                        <img src="https://cdn.simpleicons.org/gmail/EA4335" alt="Gmail">
                    </a>
                    <a href="#" target="_blank" class="social-link fb" title="Facebook">
                        <img src="https://cdn.simpleicons.org/facebook/1877F2" alt="Facebook">
                    </a>
                    <a href="https://x.com/DitMetrologi" target="_blank" class="social-link tw" title="Twitter/X">
                        <img src="https://cdn.simpleicons.org/x/white" alt="X">
                    </a>
                </div>

                {{-- DEVELOPER INFO --}}
                <div class="footer-dev-info">
                    <div class="footer-dev-label">Pengembang</div>
                    <div class="footer-dev-links">
                        <a href="https://akromdev.portofolio.app" target="_blank">
                            <img class="dev-link-icon" src="https://cdn.simpleicons.org/googlechrome/4285F4" alt="">
                            akromdev.portofolio.app
                        </a>
                        <a href="https://github.com/Ye-Shaiyoe" target="_blank">
                            <img class="dev-link-icon" src="https://cdn.simpleicons.org/github/000000" alt="">
                            github.com/akromdev
                        </a>
                        <a href="https://instagram.com/akrom.dev" target="_blank">
                            <img class="dev-link-icon" src="https://cdn.simpleicons.org/instagram/E4405F" alt="">
                            @akrom.dev
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom"><span class="footer-copy">© {{ date('Y') }} BPSUML — Direktorat Metrologi
            </span><span class="footer-tagline">Mengukur dengan Adil, Melayani dengan Tepat</span></div>
    </footer>

    {{-- BACK TO TOP BUTTON --}}
    <button class="back-to-top" id="backToTop" title="Kembali ke atas">
        <svg class="btt-progress" viewBox="0 0 52 52">
            <circle cx="26" cy="26" r="25" id="bttCircle"></circle>
        </svg>
        <svg viewBox="0 0 24 24">
            <path d="M18 15l-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"></script>

    <script>


        // Mobile Menu Logic
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
        if (menuToggle && mobileMenu) {
            const toggleMenu = () => {
                const isOpen = mobileMenu.classList.toggle('open');
                menuToggle.classList.toggle('active', isOpen);
                if (mobileMenuBackdrop) mobileMenuBackdrop.classList.toggle('open', isOpen);
                document.body.style.overflow = isOpen ? 'hidden' : '';
            };
            menuToggle.addEventListener('click', toggleMenu);
            if (mobileMenuBackdrop) {
                mobileMenuBackdrop.addEventListener('click', toggleMenu);
            }
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    menuToggle.classList.remove('active');
                    mobileMenu.classList.remove('open');
                    if (mobileMenuBackdrop) mobileMenuBackdrop.classList.remove('open');
                    document.body.style.overflow = '';
                });
            });
        }

        // ─── UNIFIED SCROLL HANDLER (satu listener, rAF throttled) ───
        const scrollBar = document.getElementById('scroll-bar');
        const navbar    = document.getElementById('navbar');
        let _scrollTick = false;
        const _scrollCbs = []; // daftar callback yang ingin dieksekusi tiap scroll
        window.addEventListener('scroll', () => {
            if (_scrollTick) return;
            _scrollTick = true;
            requestAnimationFrame(() => {
                const sy   = window.scrollY;
                const docH = document.documentElement.scrollHeight - window.innerHeight;
                const p    = docH > 0 ? (sy / docH) * 100 : 0;
                if (scrollBar) scrollBar.style.width = p + '%';
                if (navbar)    navbar.classList.toggle('scrolled', sy > 40);
                // run other scroll cbs
                for (let i = 0; i < _scrollCbs.length; i++) _scrollCbs[i](sy, p, docH);
                _scrollTick = false;
            });
        }, { passive: true });

        // Particles — skip on low-end/mobile, pause when hero not visible
        (function () {
            const cvs = document.getElementById('particles-canvas');
            if (!cvs) return;
            const isMobileOrLowEnd = window.innerWidth < 768 ||
                (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2);
            if (isMobileOrLowEnd) { cvs.style.display = 'none'; return; }

            const ctx = cvs.getContext('2d');
            let running = true;
            let heroVisible = true; // pause ketika hero di luar viewport
            let rafId = null;
            function resize() { cvs.width = window.innerWidth; cvs.height = window.innerHeight; }
            resize();

            let resizeTimer;
            window.addEventListener('resize', () => { clearTimeout(resizeTimer); resizeTimer = setTimeout(resize, 250); }, { passive: true });
            document.addEventListener('visibilitychange', () => {
                running = !document.hidden;
                if (running && heroVisible && !rafId) loop();
            });

            // Pause saat hero tidak visible (sudah scroll jauh ke bawah)
            const heroSection = document.getElementById('hero');
            if (heroSection && 'IntersectionObserver' in window) {
                new IntersectionObserver((entries) => {
                    heroVisible = entries[0].isIntersecting;
                    if (heroVisible && running && !rafId) loop();
                }, { threshold: 0.05 }).observe(heroSection);
            }

            // Reduced to 12 particles
            const N = 12;
            const DIST_SQ = 6000;
            const particles = Array.from({ length: N }, () => ({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                r: Math.random() * 1.2 + 0.3,
                vx: (Math.random() - 0.5) * 0.15,
                vy: (Math.random() - 0.5) * 0.15,
                a: Math.random() * 0.2 + 0.05
            }));
            const fillStyles = particles.map(p => `rgba(26,115,232,${p.a})`);

            function loop() {
                if (!running || !heroVisible) { rafId = null; return; }
                rafId = requestAnimationFrame(loop);
                ctx.clearRect(0, 0, cvs.width, cvs.height);

                for (let i = 0; i < N; i++) {
                    const p = particles[i];
                    p.x += p.vx; p.y += p.vy;
                    if (p.x < 0) p.x = cvs.width; else if (p.x > cvs.width) p.x = 0;
                    if (p.y < 0) p.y = cvs.height; else if (p.y > cvs.height) p.y = 0;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = fillStyles[i];
                    ctx.fill();
                }

                ctx.lineWidth = 0.4;
                for (let i = 0; i < N - 1; i++) {
                    for (let j = i + 1; j < N; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dSq = dx * dx + dy * dy;
                        if (dSq < DIST_SQ) {
                            const alpha = (0.06 * (1 - dSq / DIST_SQ)).toFixed(3);
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(26,115,232,${alpha})`;
                            ctx.stroke();
                        }
                    }
                }
            }
            loop();
        })();

        /* ─── BACKGROUND VIDEO SCROLL FADE (lazy load source) ─── */
        (() => {
            const video = document.getElementById('bg-video');
            if (!video) return;

            let videoLoaded = false;
            let isAboutInView = false;
            let isPortalsInView = false;
            let videoVisible = false;

            function loadVideoSource() {
                if (videoLoaded) return;
                videoLoaded = true;
                const source = video.querySelector('source[data-src]');
                if (source) {
                    source.src = source.dataset.src;
                    video.load();
                }
            }

            function updateVideoVisibility() {
                const shouldBeVisible = isAboutInView || isPortalsInView;
                if (shouldBeVisible !== videoVisible) {
                    videoVisible = shouldBeVisible;
                    video.classList.toggle('visible', videoVisible);
                    if (videoVisible) {
                        loadVideoSource();
                        video.play().catch(err => console.log("Video play prevented:", err));
                    } else {
                        video.pause();
                    }
                }
            }

            const aboutSection = document.getElementById('about');
            const portalsSection = document.getElementById('portals');

            if ('IntersectionObserver' in window) {
                if (aboutSection) {
                    new IntersectionObserver((entries) => {
                        isAboutInView = entries[0].isIntersecting;
                        updateVideoVisibility();
                    }, { threshold: 0.3, rootMargin: '-30px' }).observe(aboutSection);
                }
                if (portalsSection) {
                    new IntersectionObserver((entries) => {
                        isPortalsInView = entries[0].isIntersecting;
                        updateVideoVisibility();
                    }, { threshold: 0.3, rootMargin: '-30px' }).observe(portalsSection);
                }
            } else {
                isAboutInView = true;
                updateVideoVisibility();
            }
        })();

        gsap.registerPlugin(ScrollTrigger);

        // Lenis smooth scroll — hanya desktop, mobile pakai native scroll
        let lenis;
        const isDesktop = window.innerWidth > 768 && !('ontouchstart' in window);
        if (isDesktop) {
            lenis = new Lenis({
                duration: 1.1,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                smoothWheel: true,
                wheelMultiplier: 0.9,
                infinite: false,
            });
            lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add((time) => { lenis.raf(time * 1000); });
        }
        gsap.ticker.lagSmoothing(0);

        // Magnetic Effect Optimized with quickTo
        function initMagnetic() {
            const magnetics = document.querySelectorAll('.btn-primary, .btn-secondary, .nav-btn-register, .nav-user-pill');
            magnetics.forEach(el => {
                const xTo = gsap.quickTo(el, "x", { duration: 0.4, ease: "power2.out" });
                const yTo = gsap.quickTo(el, "y", { duration: 0.4, ease: "power2.out" });

                el.addEventListener('mousemove', function (e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    xTo(x * 0.3);
                    yTo(y * 0.3);
                });
                el.addEventListener('mouseleave', function (e) {
                    xTo(0);
                    yTo(0);
                    gsap.to(this, { x: 0, y: 0, duration: 0.6, ease: 'elastic.out(1, 0.3)' });
                });
            });
        }
        initMagnetic();

        // Hero Parallax — hanya pada elemen konten, bukan background
        // bg-orb & bg-mesh sudah punya CSS animation — tidak perlu GSAP parallax ganda
        gsap.to('.hero-title', {
            y: -50,
            scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1.5 }
        });
        gsap.to('.hero-float-card', {
            y: (i) => -60 - (i * 30),
            scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 2 }
        });
        gsap.to('.hero-badge', {
            y: -100,
            rotation: 90,
            scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 2.5 }
        });

        // ─── HERO ENTRANCE ANIMATION ───
        const splitIntoWords = (elementId) => {
            const el = document.getElementById(elementId);
            if (!el) return;
            if (elementId === 'hero-title-main') {
                el.querySelectorAll('.line').forEach(line => {
                    const isItalic = !!line.querySelector('em');
                    const html = line.innerText.split(' ').map(w => `<span class="word-span">${w}</span>`).join(' ');
                    line.innerHTML = isItalic ? `<em>${html}</em>` : html;
                });
            } else {
                el.innerHTML = el.innerText.split(' ').map(w => `<span class="word-span">${w}</span>`).join(' ');
            }
        };
        splitIntoWords('hero-title-main');
        splitIntoWords('hero-subtitle-main');

        gsap.set('.hero-title, .hero-subtitle', { opacity: 1 });

        // Tidak pakai filter:blur — berat di GPU. Cukup opacity + y.
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        tl.fromTo('.hero-eyebrow', { opacity: 0, x: -20 }, { opacity: 1, x: 0, duration: 0.7, delay: 0.1 })
          .fromTo('.hero-eyebrow-line', { width: 0 }, { width: 28, duration: 0.6 }, '-=0.5')
          .to('#hero-title-main .word-span', { opacity: 1, y: 0, duration: 0.45, stagger: 0.06, ease: 'back.out(1.4)', delay: 0.3 })
          .to('#hero-subtitle-main .word-span', { opacity: 1, y: 0, duration: 0.35, stagger: 0.03, ease: 'power2.out' }, '-=0.2')
          .to('.hero-cta', { opacity: 1, y: 0, duration: 0.6 }, '-=0.3')
          .to('.hero-float-cards', { opacity: 1, x: 0, duration: 0.7 }, '-=0.4')
          .to('.hero-badge', { opacity: 1, duration: 0.6 }, '-=0.3')
          .to('.hero-scroll-hint', { opacity: 1, duration: 0.5 }, '-=0.2');

        // Float cards — CSS animation lebih ringan dari GSAP repeat:-1
        // (handled via CSS class, tidak perlu GSAP loop)

        // ─── SCROLL REVEAL ANIMATIONS ───
        // About — hapus rotationY, cukup opacity + translate
        gsap.fromTo('.about-card', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.12, scrollTrigger: { trigger: '#about', start: 'top 70%' } });
        gsap.fromTo('.about-left > *', { opacity: 0, x: -30 }, { opacity: 1, x: 0, duration: 0.65, stagger: 0.1, scrollTrigger: { trigger: '#about', start: 'top 70%' } });

        document.querySelectorAll('.about-card').forEach(card => {
            const icon = card.querySelector('.about-card-icon');
            card.addEventListener('mouseenter', () => gsap.to(icon, { rotation: 10, scale: 1.12, duration: 0.35, ease: 'back.out(1.5)' }));
            card.addEventListener('mouseleave', () => gsap.to(icon, { rotation: 0, scale: 1, duration: 0.3 }));
        });

        // Stats
        gsap.fromTo('.stat-card', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.55, stagger: 0.09, scrollTrigger: { trigger: '#stats', start: 'top 72%' } });

        // Charts
        gsap.fromTo('.chart-card', { opacity: 0, y: 35 }, { opacity: 1, y: 0, duration: 0.65, stagger: 0.1, scrollTrigger: { trigger: '#charts', start: 'top 72%' } });
        gsap.fromTo('.charts-header > *', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.09, scrollTrigger: { trigger: '#charts', start: 'top 78%' } });

        // Testimonials — kurangi jarak scrub agar lebih smooth saat fast scroll
        gsap.fromTo('.track-1', { x: '0%' }, { x: '-20%', scrollTrigger: { trigger: '#testimonials', start: 'top bottom', end: 'bottom top', scrub: 2 } });
        gsap.fromTo('.track-2', { x: '-20%' }, { x: '0%', scrollTrigger: { trigger: '#testimonials', start: 'top bottom', end: 'bottom top', scrub: 2 } });
        gsap.fromTo('.testimonials-header > *', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.1, scrollTrigger: { trigger: '#testimonials', start: 'top 75%' } });
        gsap.fromTo('.testimonials-row', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.75, stagger: 0.12, scrollTrigger: { trigger: '#testimonials', start: 'top 78%' } });

        // Security
        gsap.fromTo('.security-header > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.1, scrollTrigger: { trigger: '#security', start: 'top 78%' } });
        gsap.fromTo('.badge-card', { opacity: 0, y: 35 }, { opacity: 1, y: 0, duration: 0.65, stagger: 0.08, scrollTrigger: { trigger: '.security-grid', start: 'top 72%' } });
        gsap.fromTo('.security-footer', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7, scrollTrigger: { trigger: '.security-footer', start: 'top 82%' } });

        // Footer
        gsap.fromTo('#footer > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.1, scrollTrigger: { trigger: '#footer', start: 'top 82%' } });

        // Dev section
        gsap.fromTo('.dev-header-minimal > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.09, scrollTrigger: { trigger: '#developer', start: 'top 78%' } });

        // ─── STAT COUNTERS ───
        document.querySelectorAll('.stat-number').forEach(el => {
            const target = parseInt(el.dataset.target) || 0;
            ScrollTrigger.create({
                trigger: el, start: 'top 88%', once: true,
                onEnter: () => {
                    anime({ targets: el, innerHTML: [0, target], round: 1, duration: 1800, easing: 'easeOutExpo',
                        update: function (a) { el.innerHTML = Math.round(a.animations[0].currentValue); } });
                }
            });
        });
        const ratingEl = document.getElementById('rating-number');
        if (ratingEl) {
            const targetRating = parseFloat(ratingEl.dataset.target) || 5.0;
            ScrollTrigger.create({
                trigger: ratingEl, start: 'top 88%', once: true,
                onEnter: () => {
                    const obj = { val: 0.0 };
                    anime({ targets: obj, val: targetRating, round: 10, duration: 1800, easing: 'easeOutExpo',
                        update: () => { ratingEl.innerHTML = obj.val.toFixed(1); } });
                }
            });
        }
        const isMobile = window.innerWidth < 768;

        // ─── 3D SPIRAL (Desktop only) ─── cache all refs upfront, no querySelector in loop
        (() => {
            if (isMobile) return;
            const spiralContainer = document.getElementById('spiral-items-container');
            const spiralCenter   = document.getElementById('spiral-center');
            const spiralProgressEl = document.getElementById('spiral-progress');
            const spiralSection  = document.getElementById('spiral-section');
            if (!spiralContainer || !spiralSection) return;

            const spiralData = [
                { label: 'Surat Masuk', emoji: '📥' },
                { label: 'Surat Selesai', emoji: '📤' },
                { label: 'Chart Data', emoji: '📊' },
                { label: 'Nota Dinas', emoji: '📝' },
                { label: 'Notifikasi', emoji: '🔔' },
                { label: 'Template Surat', emoji: '📋' },
                { label: 'Revisi Surat', emoji: '📨' },
                { label: 'Laporan Tahunan', emoji: '📊' },
                { label: 'Laporan Bulanan', emoji: '📆' },
                { label: 'Permohonan', emoji: '📌' },
                { label: 'QR Verifikasi', emoji: '🔐' },
                { label: '10 Tahap SLA', emoji: '⏱️' },
                { label: 'Aplikasi', emoji: '📱' },
                { label: 'Kontribusi', emoji: '🤝' },
            ];
            const N = spiralData.length;
            const TOTAL_TURNS = 2.5;
            const RADIUS = 280;
            const HEIGHT_SPREAD = 180;

            // Build DOM + cache refs — no getElementById inside animation loop
            const itemEls  = [];
            const pillEls  = [];
            const dotEls   = [];

            spiralData.forEach((d, i) => {
                const wrap = document.createElement('div');
                wrap.className = 'spiral-item-3d';
                const pill = document.createElement('div');
                pill.className = 'spiral-pill';
                pill.innerHTML = `<span class="sdot"></span>${d.emoji} ${d.label}`;
                wrap.appendChild(pill);
                spiralContainer.appendChild(wrap);
                itemEls.push(wrap);
                pillEls.push(pill);
            });

            if (spiralProgressEl) {
                spiralData.forEach((_, i) => {
                    const dot = document.createElement('div');
                    dot.className = 'sp-dot';
                    spiralProgressEl.appendChild(dot);
                    dotEls.push(dot);
                });
            }

            // Pre-cache center h2/p refs
            const centerH2 = spiralCenter ? spiralCenter.querySelector('h2') : null;
            const centerP  = spiralCenter ? spiralCenter.querySelector('p')  : null;
            const centerTexts = [
                { h: 'Scroll untuk<br><em>Melihat</em>',     p: 'Semua fitur sistem persuratan BPSUML' },
                { h: 'Pengelolaan<br><em>Dokumen</em>',       p: 'Dari surat masuk hingga pengarsipan digital' },
                { h: 'Keamanan<br><em>Terverifikasi</em>',    p: 'QR Code & SLA monitoring real-time' },
                { h: 'Sistem<br><em>Terintegrasi</em>',       p: 'Seluruh alur kerja dalam satu platform' },
            ];
            let lastStep = -1;
            let lastActiveIdx = -1;

            function updateSpiral(progress) {
                const TWO_PI = Math.PI * 2;
                const currentRotation = progress * TOTAL_TURNS * TWO_PI;

                for (let i = 0; i < N; i++) {
                    const el   = itemEls[i];
                    const pill = pillEls[i];
                    const angle = (i / N) * TWO_PI - currentRotation;

                    const x = Math.cos(angle) * RADIUS;
                    const yMapped = ((((i / N) - progress * TOTAL_TURNS) % 1) + 1) % 1;
                    const y = (yMapped - 0.5) * HEIGHT_SPREAD * 3;
                    const z = Math.sin(angle) * RADIUS;

                    const depth   = (z + RADIUS) / (RADIUS * 2);
                    const scale   = 0.6 + depth * 0.7;
                    const opacity = Math.abs(y) < 500 ? 0.15 + depth * 0.85 : 0;

                    el.style.transform = `translate3d(calc(-50% + ${x.toFixed(1)}px), calc(-50% + ${y.toFixed(1)}px), 0) scale(${scale.toFixed(3)})`;
                    el.style.opacity   = opacity.toFixed(3);
                    el.style.zIndex    = Math.round(depth * 100);

                    const isActive = Math.abs(y) < 60 && depth > 0.6;
                    if (isActive && pill.dataset.active !== '1') {
                        pill.dataset.active = '1';
                        pill.style.cssText += ';border-color:var(--accent);color:var(--white);background:var(--accent-dim)';
                    } else if (!isActive && pill.dataset.active === '1') {
                        pill.dataset.active = '';
                        pill.style.borderColor = '';
                        pill.style.color       = '';
                        pill.style.background  = '';
                    }
                }

                // Progress dots — only update when active changes
                const activeIdx = Math.floor(progress * N * TOTAL_TURNS) % N;
                if (activeIdx !== lastActiveIdx) {
                    if (lastActiveIdx >= 0 && dotEls[lastActiveIdx]) dotEls[lastActiveIdx].classList.remove('active');
                    if (dotEls[activeIdx]) dotEls[activeIdx].classList.add('active');
                    lastActiveIdx = activeIdx;
                }

                // Center text — only rewrite DOM when step changes
                const step = Math.min(Math.floor(progress * 4), centerTexts.length - 1);
                if (step !== lastStep) {
                    lastStep = step;
                    if (centerH2) centerH2.innerHTML  = centerTexts[step].h;
                    if (centerP)  centerP.textContent = centerTexts[step].p;
                }
            }

            ScrollTrigger.create({
                trigger: spiralSection,
                start: 'top top',
                end: 'bottom bottom',
                scrub: 0.8,
                onUpdate: self => updateSpiral(self.progress)
            });
            updateSpiral(0);
        })();

        // Chart.js: init saat section terlihat (lazy)
        let chartsReady = false;
        function initWelcomeCharts() {
            if (chartsReady || typeof Chart === 'undefined') return;
            chartsReady = true;
        const months = {!! json_encode($chartMixedMonths) !!};
        const dataIn = {!! json_encode($chartMixedMasuk) !!};
        const dataOut = {!! json_encode($chartMixedKeluar) !!};
        const dataSLA = {!! json_encode($chartMixedSLA) !!};
        new Chart(document.getElementById('chartMixed'), { data: { labels: months, datasets: [{ type: 'bar', label: 'Surat Masuk', data: dataIn, backgroundColor: 'rgba(26,115,232,0.55)', borderColor: '#1A73E8', borderWidth: 1, borderRadius: 5, order: 2 }, { type: 'bar', label: 'Surat Keluar', data: dataOut, backgroundColor: 'rgba(29,158,117,0.55)', borderColor: '#1D9E75', borderWidth: 1, borderRadius: 5, order: 2 }, { type: 'line', label: 'SLA Rate %', data: dataSLA, borderColor: '#5DCAA5', backgroundColor: 'rgba(93,202,165,0.08)', borderWidth: 2.5, pointBackgroundColor: '#5DCAA5', pointRadius: 4, pointHoverRadius: 6, tension: 0.4, fill: true, yAxisID: 'y2', order: 1 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(255,255,255,0.95)', borderColor: 'rgba(26,115,232,0.2)', borderWidth: 1, titleColor: '#1A73E8', bodyColor: 'rgba(26,29,38,0.7)', padding: 10 } }, scales: { x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } } }, y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } }, beginAtZero: true }, y2: { position: 'right', min: 70, max: 100, grid: { drawOnChartArea: false }, ticks: { color: 'rgba(93,202,165,0.6)', font: { size: 11 }, callback: v => v + '%' } } } } });
        // Doughnut
        const doughnutLabels = {!! json_encode($doughnutLabels) !!};
        const doughnutDataObj = {!! json_encode($doughnutData) !!};
        const doughnutData = doughnutDataObj.map(d => d.pct);
        const doughnutColors = ['#1A73E8', '#1D9E75', '#378ADD', '#EF9F27', '#D85A30', '#D4537E', '#6366F1'];
        const dlegend = document.getElementById('doughnut-legend');
        doughnutLabels.forEach((l, i) => {
            const d = doughnutDataObj[i];
            dlegend.innerHTML += `<span class="legend-item"><span class="legend-dot" style="background:${doughnutColors[i]}"></span>${l} <strong>(${d.count})</strong> ${d.pct}%</span>`;
        });
        new Chart(document.getElementById('chartDoughnut'), {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: doughnutColors.map(c => c + 'CC'),
                    borderColor: doughnutColors,
                    borderWidth: 1.5,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '64%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        borderColor: 'rgba(26,115,232,0.2)',
                        borderWidth: 1,
                        titleColor: '#1A73E8',
                        bodyColor: 'rgba(26,29,38,0.7)',
                        padding: 10,
                        callbacks: { label: ctx => ` ${ctx.label}: ${doughnutDataObj[ctx.dataIndex].count} surat (${ctx.raw}%)` }
                    }
                }
            }
        });
        // Stacked Area
        const months12 = {!! json_encode($chartAreaMonths) !!};
        new Chart(document.getElementById('chartArea'), {
            type: 'line',
            data: {
                labels: months12,
                datasets: [
                    { label: 'Masuk', data: {!! json_encode($chartAreaMasuk) !!}, borderColor: '#1A73E8', backgroundColor: 'rgba(26,115,232,0.10)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true },
                    { label: 'Keluar', data: {!! json_encode($chartAreaKeluar) !!}, borderColor: '#378ADD', backgroundColor: 'rgba(55,138,221,0.08)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true },
                    { label: 'Selesai', data: {!! json_encode($chartAreaSelesai) !!}, borderColor: '#1D9E75', backgroundColor: 'rgba(29,158,117,0.08)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: 'rgba(255,255,255,0.95)', borderColor: 'rgba(26,115,232,0.2)', borderWidth: 1, titleColor: '#1A73E8', bodyColor: 'rgba(26,29,38,0.7)', padding: 10 }
                },
                scales: {
                    x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } } },
                    y: { stacked: false, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } }, beginAtZero: true }
                }
            }
        });
        // SLA bars
        const slaData = {!! json_encode($slaPerJenis) !!};
        const slaList = document.getElementById('sla-list');
        slaData.forEach((s, i) => {
            slaList.innerHTML += `<div class="sla-item"><div class="sla-row"><span class="sla-name">${s.name}</span><span class="sla-val" id="sla-val-${i}">0%</span></div><div class="sla-bar-wrap"><div class="sla-bar" id="sla-bar-${i}" style="background:${s.color}"></div></div></div>`;
        });
        ScrollTrigger.create({
            trigger: '#sla-list',
            start: 'top 80%',
            once: true,
            onEnter: () => {
                slaData.forEach((s, i) => {
                    anime({ targets: `#sla-bar-${i}`, width: s.pct + '%', duration: 1400, delay: i * 120, easing: 'easeOutExpo' });

                    const obj = { val: 0 };
                    anime({
                        targets: obj,
                        val: s.pct,
                        round: 1,
                        duration: 1400,
                        delay: i * 120,
                        easing: 'easeOutExpo',
                        update: function () {
                            const el = document.getElementById(`sla-val-${i}`);
                            if (el) el.innerHTML = Math.round(obj.val) + '%';
                        }
                    });
                });
            }
        });
        }

        const chartsSection = document.getElementById('charts');
        if (chartsSection && 'IntersectionObserver' in window) {
            const chartIo = new IntersectionObserver((entries) => {
                if (entries.some(e => e.isIntersecting)) {
                    initWelcomeCharts();
                    chartIo.disconnect();
                }
            }, { rootMargin: '120px' });
            chartIo.observe(chartsSection);
        } else {
            initWelcomeCharts();
        }

        // Portals reveal
        gsap.fromTo('.portal-card', { opacity: 0, scale: 0.95, y: 20 }, { opacity: 1, scale: 1, y: 0, duration: 0.6, stagger: 0.08, scrollTrigger: { trigger: '#portals', start: 'top 75%' } });
        gsap.fromTo('.portals-header > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, scrollTrigger: { trigger: '#portals', start: 'top 80%' } });
        // Tilt/glow mousemove — throttled rAF, getBoundingClientRect cached on enter only
        document.querySelectorAll('.portal-card, .stat-card, .doc-preview, .archive-item').forEach(card => {
            let ticking = false;
            let rect = null;
            card.addEventListener('mouseenter', () => { rect = card.getBoundingClientRect(); });
            card.addEventListener('mousemove', e => {
                if (ticking || !rect) return;
                ticking = true;
                requestAnimationFrame(() => {
                    card.style.setProperty('--x', `${e.clientX - rect.left}px`);
                    card.style.setProperty('--y', `${e.clientY - rect.top}px`);
                    ticking = false;
                });
            });
        });

        // ========== HORIZONTAL FEATURE SHOWCASE ==========
        const scrollerSection = document.getElementById('features-scroller');
        const trackHorizontal = document.getElementById('features-track');
        if (scrollerSection && trackHorizontal && window.innerWidth > 768 && !('ontouchstart' in window)) {
            const slides = trackHorizontal.querySelectorAll('.feature-slide');
            let totalWidth = 0;
            slides.forEach(s => { totalWidth += s.offsetWidth; });
            trackHorizontal.style.width = totalWidth + 'px';

            const progressBar = document.getElementById('features-progress-bar');

            const horizontalScroll = gsap.to(trackHorizontal, {
                x: () => -(totalWidth - window.innerWidth),
                ease: 'none',
                scrollTrigger: {
                    trigger: scrollerSection,
                    start: 'top top',
                    end: () => `+=${totalWidth - window.innerWidth}`,
                    scrub: 1.5,
                    pin: true,
                    anticipatePin: 1,
                    invalidateOnRefresh: true,
                    onUpdate: self => {
                        if (progressBar) progressBar.style.width = (self.progress * 100) + '%';
                    }
                }
            });

            // Per-slide entrance — pakai containerAnimation tapi hanya opacity+x sederhana
            slides.forEach(slide => {
                const textSide   = slide.querySelector('.feature-text-side');
                const visualSide = slide.querySelector('.feature-visual-side');
                const introContent = slide.querySelector('.feature-content');

                if (textSide) {
                    gsap.fromTo(textSide, { x: 50, opacity: 0 }, {
                        x: 0, opacity: 1,
                        scrollTrigger: {
                            trigger: slide, containerAnimation: horizontalScroll,
                            start: 'left 88%', end: 'left 52%', scrub: true
                        }
                    });
                }
                if (visualSide) {
                    gsap.fromTo(visualSide, { x: 80, opacity: 0 }, {
                        x: 0, opacity: 1,
                        scrollTrigger: {
                            trigger: slide, containerAnimation: horizontalScroll,
                            start: 'left 90%', end: 'left 48%', scrub: true
                        }
                    });
                }
                if (introContent) {
                    gsap.fromTo(introContent, { x: -40, opacity: 0.5 }, {
                        x: 0, opacity: 1,
                        scrollTrigger: {
                            trigger: slide, containerAnimation: horizontalScroll,
                            start: 'left 85%', end: 'left 42%', scrub: true
                        }
                    });
                }

                // Archive stack fan
                const archiveCards = slide.querySelectorAll('.archive-item');
                if (archiveCards.length >= 3) {
                    gsap.fromTo(archiveCards[0], { rotation: 0 }, { rotation: -10,
                        scrollTrigger: { trigger: slide, containerAnimation: horizontalScroll, start: 'left 85%', end: 'left 45%', scrub: true } });
                    gsap.fromTo(archiveCards[1], { x: 0, y: 0, opacity: 0 }, { x: 40, y: 30, opacity: 0.85,
                        scrollTrigger: { trigger: slide, containerAnimation: horizontalScroll, start: 'left 85%', end: 'left 45%', scrub: true } });
                    gsap.fromTo(archiveCards[2], { x: 0, y: 0, opacity: 0 }, { x: -30, y: 60, opacity: 0.45,
                        scrollTrigger: { trigger: slide, containerAnimation: horizontalScroll, start: 'left 85%', end: 'left 45%', scrub: true } });
                }
            });

            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (window.innerWidth > 768) {
                        let newTotal = 0;
                        slides.forEach(s => { newTotal += s.offsetWidth; });
                        trackHorizontal.style.width = newTotal + 'px';
                        ScrollTrigger.refresh();
                    }
                }, 200);
            }, { passive: true });
        } else if (trackHorizontal) {
            trackHorizontal.style.width = '100%';
            trackHorizontal.style.transform = 'none';
            trackHorizontal.style.flexDirection = 'column';
        }

        // ========== ALUR KERJA PERSURATAN TRIGGER ==========
        const alurTimeline = document.querySelector('#alur-kerja .alur-timeline');
        if (alurTimeline) {
            // Animasi bar timeline terisi berdasarkan scroll
            gsap.to('#alur-kerja .alur-line-fill', {
                height: '100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: alurTimeline,
                    start: 'top 75%',
                    end: 'bottom 75%',
                    scrub: true
                }
            });

            // Trigger kelas visible untuk masing-masing step (once: true agar tidak hilang saat di-scroll ke atas)
            document.querySelectorAll('#alur-kerja .alur-step').forEach(step => {
                ScrollTrigger.create({
                    trigger: step,
                    start: 'top 85%',
                    once: true,
                    onEnter: () => step.classList.add('visible')
                });
            });

            // ── Sticky Header Detection + Live Step Counter ──
            const alurHeader = document.querySelector('#alur-kerja .alur-header');
            if (alurHeader) {
                // Create live step counter element
                const stepCounter = document.createElement('div');
                stepCounter.className = 'alur-step-counter';
                stepCounter.innerHTML = '<span class="counter-current">0</span> <span class="counter-sep">/</span> <span class="counter-total">10</span>';
                alurHeader.appendChild(stepCounter);

                // Detect when header becomes sticky using IntersectionObserver with sentinel
                const sentinel = document.createElement('div');
                sentinel.style.cssText = 'height:1px;width:100%;pointer-events:none;position:absolute;top:0;left:0;';
                alurHeader.style.position && document.querySelector('#alur-kerja .alur-kerja-container').prepend(sentinel);

                // Use unified scroll handler for sticky state detection
                const updateStickyState = () => {
                    const headerRect = alurHeader.getBoundingClientRect();
                    const isStuck = headerRect.top <= 32;
                    alurHeader.classList.toggle('is-stuck', isStuck);

                    const steps = document.querySelectorAll('#alur-kerja .alur-step');
                    let lastVisible = 0;
                    steps.forEach((step, i) => {
                        if (step.classList.contains('visible')) lastVisible = i + 1;
                    });
                    const counterEl = stepCounter.querySelector('.counter-current');
                    if (counterEl) counterEl.textContent = lastVisible;
                };

                _scrollCbs.push(updateStickyState);
                updateStickyState();
            }

            // ── Per-Step Color Themes ──
            const stepThemes = [
                { color: '#4ade80', end: '#16a34a', glow: 'rgba(74,222,128,0.35)', glowSoft: 'rgba(74,222,128,0.12)', bg: 'rgba(74,222,128,0.06)', bgHover: 'rgba(74,222,128,0.12)', border: 'rgba(74,222,128,0.12)' },
                { color: '#a78bfa', end: '#7c3aed', glow: 'rgba(167,139,250,0.35)', glowSoft: 'rgba(167,139,250,0.12)', bg: 'rgba(167,139,250,0.06)', bgHover: 'rgba(167,139,250,0.12)', border: 'rgba(167,139,250,0.12)' },
                { color: '#3b82f6', end: '#1d4ed8', glow: 'rgba(59,130,246,0.35)', glowSoft: 'rgba(59,130,246,0.12)', bg: 'rgba(59,130,246,0.06)', bgHover: 'rgba(59,130,246,0.12)', border: 'rgba(59,130,246,0.12)' },
                { color: '#fbbf24', end: '#d97706', glow: 'rgba(251,191,36,0.35)', glowSoft: 'rgba(251,191,36,0.12)', bg: 'rgba(251,191,36,0.06)', bgHover: 'rgba(251,191,36,0.12)', border: 'rgba(251,191,36,0.12)' },
                { color: '#c084fc', end: '#9333ea', glow: 'rgba(192,132,252,0.35)', glowSoft: 'rgba(192,132,252,0.12)', bg: 'rgba(192,132,252,0.06)', bgHover: 'rgba(192,132,252,0.12)', border: 'rgba(192,132,252,0.12)' },
                { color: '#f472b6', end: '#db2777', glow: 'rgba(244,114,182,0.35)', glowSoft: 'rgba(244,114,182,0.12)', bg: 'rgba(244,114,182,0.06)', bgHover: 'rgba(244,114,182,0.12)', border: 'rgba(244,114,182,0.12)' },
                { color: '#06b6d4', end: '#0891b2', glow: 'rgba(6,182,212,0.35)', glowSoft: 'rgba(6,182,212,0.12)', bg: 'rgba(6,182,212,0.06)', bgHover: 'rgba(6,182,212,0.12)', border: 'rgba(6,182,212,0.12)' },
                { color: '#fb923c', end: '#ea580c', glow: 'rgba(251,146,60,0.35)', glowSoft: 'rgba(251,146,60,0.12)', bg: 'rgba(251,146,60,0.06)', bgHover: 'rgba(251,146,60,0.12)', border: 'rgba(251,146,60,0.12)' },
                { color: '#818cf8', end: '#4f46e5', glow: 'rgba(129,140,248,0.35)', glowSoft: 'rgba(129,140,248,0.12)', bg: 'rgba(129,140,248,0.06)', bgHover: 'rgba(129,140,248,0.12)', border: 'rgba(129,140,248,0.12)' },
                { color: '#C8A96E', end: '#a67c3d', glow: 'rgba(200,169,110,0.45)', glowSoft: 'rgba(200,169,110,0.2)', bg: 'rgba(200,169,110,0.08)', bgHover: 'rgba(200,169,110,0.15)', border: 'rgba(200,169,110,0.15)' },
            ];

            document.querySelectorAll('#alur-kerja .alur-step').forEach((step, i) => {
                const theme = stepThemes[i] || stepThemes[0];
                step.style.setProperty('--step-color', theme.color);
                step.style.setProperty('--step-color-end', theme.end);
                step.style.setProperty('--step-glow', theme.glow);
                step.style.setProperty('--step-glow-soft', theme.glowSoft);
                step.style.setProperty('--step-bg', theme.bg);
                step.style.setProperty('--step-bg-hover', theme.bgHover);
                step.style.setProperty('--step-border', theme.border);
            });
        }

        // SLA Chronometer — hanya pulse, tidak ada GSAP repeat:-1 yang berat
        const progressRing = document.querySelector('.timer-progress-ring');
        if (progressRing) {
            // CSS animation lebih ringan dari GSAP infinite. Add via class.
            progressRing.style.animation = 'none'; // reset any existing
            gsap.fromTo(progressRing, { scale: 0.96, opacity: 0.3 }, {
                scale: 1.04, opacity: 0.8, duration: 1.5, repeat: -1, yoyo: true, ease: 'sine.inOut'
            });
        }

        // SLA Timer countdown — pause saat tab hidden, pause saat slide tidak visible
        (() => {
            const timerEl = document.querySelector('.timer-val');
            if (!timerEl) return;
            let h = 23, m = 54, s = 12;
            let timerId = null;
            const tick = () => {
                s--;
                if (s < 0) { s = 59; m--; if (m < 0) { m = 59; h--; if (h < 0) h = 23; } }
                timerEl.textContent =
                    String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            };
            const start = () => { if (!timerId) timerId = setInterval(tick, 1000); };
            const stop  = () => { if (timerId) { clearInterval(timerId); timerId = null; } };

            // Only run when timer section is visible
            const featureSection = document.getElementById('features-scroller');
            if (featureSection && 'IntersectionObserver' in window) {
                new IntersectionObserver(entries => {
                    entries[0].isIntersecting ? start() : stop();
                }, { threshold: 0.1 }).observe(featureSection);
            } else {
                start();
            }
            document.addEventListener('visibilitychange', () => { document.hidden ? stop() : start(); });
        })();

        (function () {
            const stacks = {
                'tm-row1': [
                    { name: 'Laravel', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'Redis', img: 'https://cdn.simpleicons.org/redis/DC382D' },
                    { name: 'PostgreSQL', img: 'https://cdn.simpleicons.org/postgresql/646CFF' },
                    { name: 'Eloquent ORM', dot: '#e11d48' },
                    { name: 'REST API', dot: '#0ea5e9' },
                    { name: 'Sanctum Auth', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'Composer', img: 'https://cdn.simpleicons.org/composer/885630' },
                    { name: 'JQuery', img: 'https://cdn.simpleicons.org/jquery/1621A5' },
                ],
                'tm-row2': [
                    { name: 'Bootstrap 5', img: 'https://cdn.simpleicons.org/bootstrap/7952B3' },
                    { name: 'TailwindCSS', img: 'https://cdn.simpleicons.org/tailwindcss/38B2AC' },
                    { name: 'Alpine.js', img: 'https://cdn.simpleicons.org/alpinedotjs/8BC0D0' },
                    { name: 'JavaScript', img: 'https://cdn.simpleicons.org/javascript/F7DF1E' },
                    { name: 'Chart.js', img: 'https://cdn.simpleicons.org/chartdotjs/FF6384' },
                    { name: 'MVC', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'Vite', img: 'https://cdn.simpleicons.org/vite/646CFF' },
                    { name: 'GSAP', img: 'https://cdn.simpleicons.org/greensock/88CE02' },
                    { name: 'Anime.js', img: 'https://cdn.simpleicons.org/anime.js/FF2D20' },
                ],
                'tm-row3': [ 
                    { name: 'Docker', img: 'https://cdn.simpleicons.org/docker/2496ED' },
                    { name: 'Git', img: 'https://cdn.simpleicons.org/git/F05032' },
                    { name: 'GitHub', img: 'https://cdn.simpleicons.org/github/000000' },
                    { name: 'Gemini AI', img: 'https://cdn.simpleicons.org/googlegemini/4285F4' },
                    { name: 'reCAPTCHA v3', dot: '#4285F4' },
                    { name: 'Postman', img: 'https://cdn.simpleicons.org/postman/FF6C37' },
                    { name: 'npm', img: 'https://cdn.simpleicons.org/npm/CB3837' },
                    { name: 'Nginx', img: 'https://cdn.simpleicons.org/nginx/009639' },
                    { name: 'Linux', img: 'https://cdn.simpleicons.org/linux/000000' },
                    
                ]
            };

            function makeChip(item) {
                const chip = document.createElement('div');
                chip.className = 'tech-chip';
                if (item.img) {
                    const img = document.createElement('img');
                    img.src = item.img;
                    img.alt = item.name;
                    img.onerror = function () { this.style.display = 'none'; };
                    chip.appendChild(img);
                } else {
                    const dot = document.createElement('span');
                    dot.className = 'chip-dot';
                    dot.style.background = item.dot;
                    chip.appendChild(dot);
                }
                const label = document.createElement('span');
                label.textContent = item.name;
                chip.appendChild(label);
                return chip;
            }

            Object.entries(stacks).forEach(([id, items]) => {
                const row = document.getElementById(id);
                if (!row) return;
                // Double items for seamless loop
                [...items, ...items, ...items].forEach(item => row.appendChild(makeChip(item)));
            });
        })();

        /* ─── TEXT ANIMATIONS WITH GSAP ─── */
        (() => {
            // Initialize GSAP animations for text
            const initTextAnimations = () => {
                document.querySelectorAll('.text-animate').forEach(section => {
                    const words = section.querySelectorAll('.text-animate-word');

                    // Create GSAP timeline
                    const tl = gsap.timeline({
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 80%',
                            toggleActions: 'play none none none'
                        }
                    });

                    // Animate each word with stagger
                    words.forEach((word, index) => {
                        tl.to(word, {
                            opacity: 1,
                            y: 0,
                            duration: 0.6,
                            ease: 'back.out'
                        }, index * 0.08); // Stagger each word by 80ms
                    });
                });
            };

            // Wait for DOM to be ready and init
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTextAnimations);
            } else {
                initTextAnimations();
            }
        })();

        /* ─── BACK TO TOP BUTTON ─── */
        (() => {
            const btn    = document.getElementById('backToTop');
            const circle = document.getElementById('bttCircle');
            if (!btn) return;
            const CIRCUMFERENCE = 2 * Math.PI * 25;

            // Reuse the same scroll RAF as the navbar scroll listener
            // (tidak perlu tambah scroll listener baru)
            const updateBTT = (sy, p, docH) => {
                btn.classList.toggle('visible', sy > 400);
                if (circle) {
                    circle.style.strokeDashoffset = CIRCUMFERENCE - ((sy / docH) * CIRCUMFERENCE);
                }
            };
            _scrollCbs.push(updateBTT);
            updateBTT(window.scrollY, 0, document.documentElement.scrollHeight - window.innerHeight);

            btn.addEventListener('click', () => {
                if (typeof lenis !== 'undefined' && lenis && lenis.scrollTo) {
                    lenis.scrollTo(0, { duration: 1.8 });
                } else {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        })();
    </script>
</body>

</html>