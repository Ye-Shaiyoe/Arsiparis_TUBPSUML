@extends('layouts.user')

@section('content')
<style>
    /* Gradient Text Animation */
    .animate-gradient-text {
        background: linear-gradient(to right, #1e3a5f, #3b82f6, #1e3a5f);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shine 3s linear infinite;
    }

    @keyframes shine {
        to { background-position: 200% center; }
    }

    /* Floating Animation */
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    /* Typing Effect for Badge */
    .typing-badge {
        overflow: hidden;
        border-right: 2px solid var(--text-primary);
        white-space: nowrap;
        animation: typing 2.5s steps(30, end), blink-caret .75s step-end infinite;
        max-width: fit-content;
    }

    @keyframes typing {
        from { width: 0 }
        to { width: 100% }
    }

    @keyframes blink-caret {
        from, to { border-color: transparent }
        50% { border-color: #3b82f6; }
    }

    /* List item hover sparkle */
    .list-fitur li {
        transition: all 0.3s ease;
    }
    .list-fitur li:hover {
        padding-left: 10px !important;
        background: rgba(59, 130, 246, 0.05);
        color: #1e3a5f;
    }

    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: 160px;
        grid-gap: 16px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.05);
        background: #f8fafc;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-item.large {
        grid-column: span 2;
        grid-row: span 2;
    }

    .gallery-item.tall {
        grid-row: span 2;
    }

    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.9), transparent 60%);
        color: white;
        padding: 20px;
        font-size: 13px;
        font-weight: 600;
        opacity: 0;
        transition: all 0.4s ease;
        display: flex;
        align-items: flex-end;
        backdrop-filter: blur(2px);
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay span {
        transform: translateY(15px);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-item:hover .gallery-overlay span {
        transform: translateY(0);
    }

    @media (max-width: 992px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .gallery-item.large {
            grid-column: span 2;
        }
    }

    @media (max-width: 576px) {
        .gallery-grid {
            grid-template-columns: 1fr;
            grid-auto-rows: 200px;
        }
        .gallery-item.large, .gallery-item.tall {
            grid-column: span 1;
            grid-row: span 1;
        }
    }

    /* Tech Stack Marquee */
    .tech-marquee-container {
        overflow: hidden;
        user-select: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 10px 0;
        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }

    .tech-marquee {
        display: flex;
        overflow: hidden;
        gap: 15px;
    }

    .tech-marquee-content {
        display: flex;
        flex-shrink: 0;
        gap: 15px;
        min-width: 100%;
    }

    .tech-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(229, 231, 235, 0.5);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        color: #4b5563;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .tech-item:hover {
        background: white;
        border-color: #3b82f6;
        color: #1e3a5f;
        transform: translateY(-2px);
    }

    .tech-item i, .tech-item img {
        font-size: 16px;
        width: 18px;
        height: 18px;
        object-fit: contain;
    }


    .scroll-right {
        animation: scroll-ltr 30s linear infinite;
    }

    .scroll-left {
        animation: scroll-rtl 30s linear infinite;
    }

    @keyframes scroll-ltr {
        from { transform: translateX(-50%); }
        to { transform: translateX(0); }
    }

    @keyframes scroll-rtl {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }

    .tech-marquee:hover .tech-marquee-content {
        animation-play-state: paused;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom mb-4 animate-in">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-2 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center animate-float"
                            style="width: 52px; height: 52px; font-size: 24px; flex-shrink: 0;">
                            <img src="{{ asset('images/metrologi.png') }}" alt="" style="width: 35px;">
                        </div>
                        <div>
                            <div class="typing-badge">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-1" style="font-size: 11px;">
                                    Manajemen persuratan BP SUML
                                </span>
                            </div>
                            <h5 class="fw-bold mb-0 animate-gradient-text">Balai Pengelolaanan SUML</h5>
                            <small class="text-muted">Direktorat Metrologi</small>
                        </div>
                    </div>

                    <p class="text-muted animate-in" style="font-size: 14px; line-height: 1.7; animation-delay: 0.1s;">
                        Direktorat Metrologi merupakan unit pelaksana teknis di bawah Kementerian Perdagangan RI yang
                        bertugas melaksanakan standardisasi dan pengawasan di bidang BP SUML legal — memastikan
                        kebenaran pengukuran dalam transaksi perdagangan demi perlindungan konsumen dan kepastian usaha.
                    </p>

                    <p class="text-muted animate-in" style="font-size: 14px; line-height: 1.7; animation-delay: 0.2s;">
                        Aplikasi <strong class="text-primary">Surat Balai Pengelolaan SUML</strong> hadir sebagai solusi digital untuk pengelolaan
                        korespondensi kedinasan di lingkungan BP SUML Legal, mempermudah proses administrasi
                        surat-menyurat secara transparan dan efisien.
                    </p>
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div class="row g-3 mt-1 mb-4 animate-in" style="animation-delay: 0.25s;">
                <div class="col-lg-12">
                    <div class="card card-custom overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ asset('images/about/building.png') }}" class="img-fluid h-100 object-fit-cover" alt="Vision" style="min-height: 200px;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h6 class="fw-semibold mb-3" style="color:#111827;">
                                        <i class="bi bi-eye text-info me-1 animate-float"></i> Visi & Misi Kami
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 13.5px;">
                                        Menjadi institusi yang terpercaya dalam menjamin kepastian pengukuran, meningkatkan daya saing produk nasional, serta memberikan perlindungan maksimal bagi konsumen melalui sistem administrasi yang modern dan transparan.
                                    </p>
                                    <div class="d-flex gap-4 mt-4">
                                        <div class="text-center">
                                            <div class="h4 fw-bold text-primary mb-0">100%</div>
                                            <small class="text-muted" style="font-size: 11px;">Transparansi</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-center">
                                            <div class="h4 fw-bold text-success mb-0">24/7</div>
                                            <small class="text-muted" style="font-size: 11px;">Akses Digital</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-center">
                                            <div class="h4 fw-bold text-warning mb-0">Reliable</div>
                                            <small class="text-muted" style="font-size: 11px;">Data Akurat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 animate-in" style="animation-delay: 0.3s;">
                {{-- Informasi Kontak --}}
                <div class="col-lg-6">
                    <div class="card card-custom h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3" style="color:#111827;">
                                <i class="bi bi-geo-alt-fill text-danger me-1 animate-float"></i> Kontak & Informasi
                            </h6>

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                        <small class="text-muted d-block mb-1">Alamat</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">
                                            Jl. Pasteur No.27, RT.02, Pasir Kaliki,<br>
                                            Kec. Cicendo, Kota Bandung, Jawa Barat 40171
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                        <small class="text-muted d-block mb-1">Jam Operasional</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">Senin – Kamis<br>07.30 – 16.00 WIB<br>Jum’at<br>07.30 – 16.30 WIB</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                        <small class="text-muted d-block mb-1">Email</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">tubpsuml@gmail.com</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                        <small class="text-muted d-block mb-1">Website</small>
                                        <a href="https://metrologi.kemendag.go.id" target="_blank"
                                           style="font-size: 13px; font-weight: 500;color:#111827;">
                                            metrologi.kemendag.go.id 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fitur Utama --}}
                <div class="col-lg-6">
                    <div class="card card-custom h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3" style="color:#111827;">
                                <i class="bi bi-stars text-warning me-1 animate-float"></i> Fitur Utama Aplikasi
                            </h6>
                            <ul class="list-unstyled mb-0 list-fitur">
                                @foreach([
                                    ['icon' => 'bi-send', 'text' => 'Pengajuan surat secara daring tanpa perlu datang langsung'],
                                    ['icon' => 'bi-search', 'text' => 'Pelacakan status surat secara real-time'],
                                    ['icon' => 'bi-file-earmark-text', 'text' => 'Manajemen template surat kedinasan'],
                                    ['icon' => 'bi-download', 'text' => 'Download berkas dalam format Word & PDF'],
                                    ['icon' => 'bi-bell', 'text' => 'Notifikasi otomatis perkembangan surat'],
                                    ['icon' => 'bi-qr-code', 'text' => 'QR Code Verifikasi'],
                                    ['icon'=> 'bi-graph-up','text'=> 'Chart perkembangan surat keluar & masuk'],
                                    ['icon' => 'bi-clock-history', 'text' => 'Tracking realtime sebanyak 10 Tahapan'],
                                    ['icon' => 'bi-chat', 'text'=> 'Chat admin via Aplikasi, WhatsApp & Email']

                                ] as $fitur)
                                <li class="d-flex align-items-start gap-2 py-2 border-bottom" style="font-size: 13px;border-color:#e5e7eb;">
                                    <i class="bi {{ $fitur['icon'] }} text-primary mt-1" style="flex-shrink:0;"></i>
                                    <span class="text-muted">{{ $fitur['text'] }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Galeri Visual --}}
            <div class="row g-3 mt-1 animate-in" style="animation-delay: 0.35s;">
                <div class="col-12">
                    <div class="card card-custom">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-4" style="color:#111827;">
                                <i class="bi bi-images text-primary me-1 animate-float"></i> Galeri Visual & Infrastruktur
                            </h6>
                            <div class="gallery-grid">
                                <div class="gallery-item large">
                                    <img src="{{ asset('images/about/office.png') }}" alt="Building">
                                    <div class="gallery-overlay">
                                        <span><i class="bi bi-building-fill me-2"></i>Gedung Direktorat Metrologi</span>
                                    </div>
                                </div>
                                <div class="gallery-item tall">
                                    <img src="{{ asset('images/about/team.png') }}" alt="Team">
                                    <div class="gallery-overlay">
                                        <span><i class="bi bi-people-fill me-2"></i>Kolaborasi Tim</span>
                                    </div>
                                </div>
                                <div class="gallery-item">
                                    <img src="{{ asset('images/about/office.png') }}" alt="Office">
                                    <div class="gallery-overlay">
                                        <span><i class="bi bi-laptop me-2"></i>Ruang Kerja Modern</span>
                                    </div>
                                </div>
                                <div class="gallery-item">
                                    <img src="{{ asset('images/about/equipment.png') }}" alt="Equipment">
                                    <div class="gallery-overlay">
                                        <span><i class="bi bi-tools me-2"></i>Peralatan Presisi</span>
                                    </div>
                                </div>
                                <div class="gallery-item">
                                    <img src="{{ asset('images/about/digital.png') }}" alt="Digital">
                                    <div class="gallery-overlay">
                                        <span><i class="bi bi-cpu me-2"></i>Digitalisasi Sistem</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="card card-custom mt-3 animate-in" style="animation-delay: 0.4s;">

                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3" style="color:#111827;">
                        <i class="bi bi-map text-success me-1 animate-float"></i> Lokasi Kami
                    </h6>
                    <div class="rounded-3 overflow-hidden border" style="height: 320px;border-color:#e5e7eb;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.4!2d107.5897!3d-6.8985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6e3f63d8df9%3A0x3c1a0ef3aea8df32!2sJl.%20Pasteur%20No.27%2C%20Pasir%20Kaliki%2C%20Kec.%20Cicendo%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040171!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
                            width="100%"
                            height="320"
                            style="border: 0; display: block;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <p class="text-muted mt-2 mb-0" style="font-size: 12px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Jl. Pasteur No.27, RT.02, Pasir Kaliki, Kec. Cicendo, Kota Bandung, Jawa Barat 40171
                    </p>
                </div>
            </div>

            {{-- Developer Info --}}
            <div class="card card-custom mt-3 mb-2 animate-in" style="animation-delay: 0.5s;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3" style="color:#111827;">
                        <i class="bi bi-code-slash text-primary me-1 animate-float"></i> Tentang Pengembang
                    </h6>
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; font-size: 24px; color: #2563eb;">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted" style="font-size: 13.5px; line-height: 1.6;">
                                Aplikasi Tugas PKL <strong>Manajemen persuratan BP SUML</strong> ini dirancang dan dikembangkan oleh 1 Developer dan dibantu oleh AI agar pengerjaan lebih cepat, efisien, dan rapi. Dibuat dengan dedikasi tinggi menggunakan framework modern dan standar UI/UX estetis (berbasis prinsip Glassmorphism) demi memastikan pengalaman pengguna yang cepat, intuitif, dan seamless.
                            </p>
                            <span style="font-size: 12px; font-weight: 500; color: #3b82f6;">
                                <i class="bi bi-github me-1"></i> <a href="https://github.com/Ye-Shaiyoe/Surat-Laravel" target="_blank">GitHub Repository - Ye-Shaiyoe </a> <br>
                            </span>
                            <span style="font-size: 12px; font-weight: 500; color: #3b82f6;">
                                <i class="bi bi-laptop me-1"></i> Developed for Balai Pengelolaan SUML
                            </span>
                        </div>
                    </div>

                {{-- Tech Stack Marquee --}}
                <style>
                .tm-wrap { display: flex; flex-direction: column; gap: 10px; margin-top: 1.25rem; }
                .tm-label { font-size: 10.5px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #9ca3af; margin-bottom: 3px; }
                .tm-track {
                    overflow: hidden;
                    mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
                    -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
                    user-select: none;
                }
                .tm-row { display: flex; gap: 10px; width: max-content; }
                .tm-row.go-right  { animation: scrollR 30s linear infinite; }
                .tm-row.go-left   { animation: scrollL 34s linear infinite; }
                .tm-row.go-right2 { animation: scrollR 26s linear infinite; }
                @keyframes scrollR { from { transform: translateX(-50%); } to { transform: translateX(0); } }
                @keyframes scrollL { from { transform: translateX(0); } to { transform: translateX(-50%); } }
                .tm-track:hover .tm-row { animation-play-state: paused; }

                .tech-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    padding: 5px 13px 5px 8px;
                    border-radius: 999px;
                    border: 1px solid rgba(229, 231, 235, 0.7);
                    background: rgba(255, 255, 255, 0.8);
                    backdrop-filter: blur(4px);
                    font-size: 12px;
                    font-weight: 600;
                    color: #4b5563;
                    white-space: nowrap;
                    cursor: default;
                    transition: border-color 0.25s, color 0.25s, transform 0.25s, background 0.25s;
                }
                .tech-chip:hover {
                    border-color: #3b82f6;
                    color: #1e3a5f;
                    background: #fff;
                    transform: translateY(-2px);
                }
                .tech-chip img {
                    width: 16px;
                    height: 16px;
                    object-fit: contain;
                    flex-shrink: 0;
                }
                .tech-chip .chip-dot {
                    width: 8px; height: 8px;
                    border-radius: 50%;
                    flex-shrink: 0;
                    display: inline-block;
                }
                .tm-section-head {
                    font-size: 12.5px;
                    font-weight: 600;
                    color: #111827;
                    margin-bottom: 10px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .tm-section-head::after {
                    content: '';
                    flex: 1;
                    height: 1px;
                    background: #e5e7eb;
                }
                </style>

                <div class="mt-4">
                    <div class="tm-section-head">
                        <i class="bi bi-stack text-primary" style="font-size:14px;"></i> Tech Stack & Tools
                    </div>

                    <div class="tm-wrap">
                        <div class="tm-label">⚙ Backend & Database</div>
                        <div class="tm-track">
                            <div class="tm-row go-right" id="tm-row1"></div>
                        </div>

                        <div class="tm-label">🎨 Frontend & UI</div>
                        <div class="tm-track">
                            <div class="tm-row go-left" id="tm-row2"></div>
                        </div>

                        <div class="tm-label">🚀 DevOps, AI & Tooling</div>
                        <div class="tm-track">
                            <div class="tm-row go-right2" id="tm-row3"></div>
                        </div>
                    </div>
                </div>

                <script>
                (function() {
                    const stacks = {
                        'tm-row1': [
                            { name: 'Laravel 11/12', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                            { name: 'PHP 8.3',       img: 'https://cdn.simpleicons.org/php/777BB4' },
                            { name: 'MySQL 8',       img: 'https://cdn.simpleicons.org/mysql/4479A1' },
                            { name: 'Redis',         img: 'https://cdn.simpleicons.org/redis/DC382D' },
                            { name: 'MongoDB',       img: 'https://cdn.simpleicons.org/mongodb/47A248' },
                            { name: 'Eloquent ORM',  dot: '#e11d48' },
                            { name: 'REST API',      dot: '#0ea5e9' },
                            { name: 'Sanctum Auth',  img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                            { name: 'Composer',      img: 'https://cdn.simpleicons.org/composer/885630' },
                            { name: 'Breeze',        img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                            { name: 'PHPOffice',     img: 'https://cdn.simpleicons.org/php/777BB4' },
                            { name: 'JQuery',        img: 'https://cdn.simpleicons.org/jquery/1621A5' },
                            { name: 'Python',        img: 'https://cdn.simpleicons.org/python/3776AB' },
                        ],
                        'tm-row2': [
                            { name: 'Bootstrap 5',    img: 'https://cdn.simpleicons.org/bootstrap/7952B3' },
                            { name: 'TailwindCSS',    img: 'https://cdn.simpleicons.org/tailwindcss/38B2AC' },
                            { name: 'Alpine.js',      img: 'https://cdn.simpleicons.org/alpinedotjs/8BC0D0' },
                            { name: 'JavaScript',     img: 'https://cdn.simpleicons.org/javascript/F7DF1E' },
                            { name: 'Chart.js',       img: 'https://cdn.simpleicons.org/chartdotjs/FF6384' },
                            { name: 'Three.js',       img: 'https://cdn.simpleicons.org/threedotjs/000000' },
                            { name: 'Vite',           img: 'https://cdn.simpleicons.org/vite/646CFF' },
                            { name: 'CSS3',           img: 'https://cdn.simpleicons.org/css3/1572B6' },
                            { name: 'Axios',          img: 'https://cdn.simpleicons.org/axios/5A29E4' },
                            { name: 'SortableJS',     dot: '#6366f1' },
                            { name: 'Blade Template', dot: '#f97316' },
                            { name: 'Open UI',        img: 'https://cdn.simpleicons.org/codecrafters/FF6384' },
                            { name: 'Framework7',     img: 'https://cdn.simpleicons.org/framework7/FF2D20' },
                            { name: 'Anime.js',       img: 'https://cdn.simpleicons.org/anime.js/FF2D20' },

                        ],
                        'tm-row3': [
                            { name: 'Docker',       img: 'https://cdn.simpleicons.org/docker/2496ED' },
                            { name: 'Git',          img: 'https://cdn.simpleicons.org/git/F05032' },
                            { name: 'GitHub',       img: 'https://cdn.simpleicons.org/github/181717' },
                            { name: 'Gemini AI',    img: 'https://cdn.simpleicons.org/googlegemini/4285F4' },
                            { name: 'Google API',   img: 'https://cdn.simpleicons.org/google/4285F4' },
                            { name: 'reCAPTCHA v3', dot: '#4285F4' },
                            { name: 'Mailtrap',     img: 'https://cdn.simpleicons.org/mailtrap/00B9FE' },
                            { name: 'Postman',      img: 'https://cdn.simpleicons.org/postman/FF6C37' },
                            { name: 'Antigravity',  img: 'https://cdn.simpleicons.org/antigravity/000000' },
                            { name: 'npm',          img: 'https://cdn.simpleicons.org/npm/CB3837' },
                            { name: 'Nginx',        img: 'https://cdn.simpleicons.org/nginx/181717' },
                            { name: 'GitHub',       img: 'https://cdn.simpleicons.org/github/181717' },
                            { name: 'Linux',        img: 'https://cdn.simpleicons.org/linux/181717' },
                            { name: 'Ubuntu Server',img: 'https://cdn.simpleicons.org/ubuntu/181717' },
                        ]
                    };

                    function makeChip(item) {
                        const chip = document.createElement('div');
                        chip.className = 'tech-chip';
                        if (item.img) {
                            const img = document.createElement('img');
                            img.src = item.img;
                            img.alt = item.name;
                            img.onerror = function() { this.style.display = 'none'; };
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
                        // duplicate for seamless infinite loop
                        [...items, ...items].forEach(item => row.appendChild(makeChip(item)));
                    });
                })();
                </script>


                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection