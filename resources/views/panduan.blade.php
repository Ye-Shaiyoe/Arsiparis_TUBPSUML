<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Pengguna — Sistem Persuratan BP SUML</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --navy:#0f2444;--navy-mid:#1e3a5f;--navy-light:#2d5a8e;
            --accent:#06b6d4;--accent-soft:rgba(6,182,212,0.12);
            --gold:#c9933a;--gold-light:#e8b96a;
            --bg:#f5f7fa;--white:#fff;
            --text:#1e293b;--text-muted:#64748b;--text-light:#94a3b8;
            --border:#e2e8f0;--border-light:#f1f5f9;
            --sidebar-w:260px;--toc-w:220px;
            --shadow-sm:0 1px 3px rgba(15,36,68,.06);
            --shadow-md:0 4px 16px rgba(15,36,68,.08);
            --shadow-lg:0 12px 40px rgba(15,36,68,.12);
        }
        html{scroll-behavior:smooth;font-size:15px}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);line-height:1.7;min-height:100vh}
        a{color:var(--accent);text-decoration:none}
        a:hover{text-decoration:underline}
        strong{font-weight:700}
        ul{padding-left:1.4rem}
        li{margin-bottom:.3rem}
        code{background:#f1f5f9;border:1px solid var(--border);border-radius:4px;padding:1px 6px;font-size:.85em;color:#0f172a}

        /* ── TOP NAV ── */
        .top-nav{
            position:sticky;top:0;z-index:100;
            background:rgba(255,255,255,.92);backdrop-filter:blur(14px);
            border-bottom:1px solid var(--border);
            display:flex;align-items:center;gap:1rem;
            padding:.75rem 1.5rem;
            box-shadow:var(--shadow-sm);
        }
        .top-nav .brand{display:flex;align-items:center;gap:.6rem;font-weight:800;font-size:1rem;color:var(--navy);text-decoration:none}
        .top-nav .brand img{height:28px}
        .top-nav .divider{width:1px;height:20px;background:var(--border);margin:0 .5rem}
        .top-nav .page-title{font-size:.85rem;font-weight:600;color:var(--text-muted)}
        .top-nav .ms-auto{margin-left:auto}
        .btn-login{
            display:inline-flex;align-items:center;gap:.4rem;
            background:linear-gradient(135deg,var(--navy-mid),var(--navy-light));
            color:#fff!important;font-size:.8rem;font-weight:700;
            padding:.45rem 1rem;border-radius:8px;text-decoration:none!important;
            box-shadow:0 4px 12px rgba(30,58,95,.25);
            transition:transform .2s,box-shadow .2s;
        }
        .btn-login:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(30,58,95,.35)}

        /* ── LAYOUT ── */
        .docs-layout{display:flex;min-height:calc(100vh - 57px)}

        /* ── LEFT SIDEBAR ── */
        .docs-sidebar{
            width:var(--sidebar-w);flex-shrink:0;
            background:var(--white);border-right:1px solid var(--border);
            position:sticky;top:57px;height:calc(100vh - 57px);
            overflow-y:auto;padding:1.5rem 0 2rem;
            scrollbar-width:thin;scrollbar-color:var(--border) transparent;
        }
        .docs-sidebar::-webkit-scrollbar{width:4px}
        .docs-sidebar::-webkit-scrollbar-thumb{background:var(--border);border-radius:9px}
        .sidebar-section-label{
            font-size:.68rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
            color:var(--text-light);padding:.8rem 1.25rem .3rem;
        }
        .sidebar-link{
            display:flex;align-items:center;gap:.55rem;
            padding:.45rem 1.25rem;font-size:.82rem;font-weight:600;color:var(--text-muted);
            border-left:3px solid transparent;text-decoration:none;
            transition:color .15s,background .15s,border-color .15s;
        }
        .sidebar-link:hover{color:var(--navy-mid);background:#f8fafc;text-decoration:none}
        .sidebar-link.active{color:var(--accent);border-left-color:var(--accent);background:var(--accent-soft);font-weight:700}
        .sidebar-link i{font-size:.95rem;width:1.1rem;text-align:center;flex-shrink:0}

        /* ── MAIN CONTENT ── */
        .docs-main{flex:1;min-width:0;display:flex;gap:0}
        .docs-content{
            flex:1;min-width:0;max-width:780px;
            padding:2.5rem 2rem 4rem;
        }

        /* ── TOC RIGHT ── */
        .docs-toc{
            width:var(--toc-w);flex-shrink:0;
            position:sticky;top:57px;height:calc(100vh - 57px);
            overflow-y:auto;padding:2rem 1.25rem;
            border-left:1px solid var(--border-light);
        }
        .toc-title{font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--text-light);margin-bottom:.75rem}
        .toc-link{
            display:block;font-size:.78rem;font-weight:500;color:var(--text-muted);
            padding:.28rem .5rem .28rem .75rem;border-left:2px solid transparent;
            text-decoration:none;transition:color .15s,border-color .15s;margin-bottom:1px;
        }
        .toc-link:hover{color:var(--text);text-decoration:none}
        .toc-link.active{color:var(--accent);border-left-color:var(--accent);font-weight:700}
        .toc-link.indent{padding-left:1.4rem;font-size:.74rem}

        /* ── CONTENT TYPOGRAPHY ── */
        .docs-content h1{font-size:1.9rem;font-weight:800;color:var(--navy);line-height:1.2;margin-bottom:.6rem;letter-spacing:-.03em}
        .docs-content .lead{font-size:1rem;color:var(--text-muted);margin-bottom:2rem;line-height:1.75}
        .docs-content h2{
            font-size:1.25rem;font-weight:800;color:var(--navy);
            margin:2.5rem 0 .75rem;padding-bottom:.5rem;
            border-bottom:2px solid var(--border-light);
        }
        .docs-content h3{font-size:1.05rem;font-weight:700;color:var(--navy-mid);margin:1.75rem 0 .5rem}
        .docs-content p{margin-bottom:.9rem;color:#374151}
        .docs-content ul,
        .docs-content ol{margin-bottom:.9rem;color:#374151}

        /* ── HERO STRIP ── */
        .doc-hero{
            background:linear-gradient(135deg,var(--navy) 0%,var(--navy-mid) 60%,#1a4a7a 100%);
            border-radius:16px;padding:2rem 2rem 1.75rem;margin-bottom:2.5rem;
            position:relative;overflow:hidden;
        }
        .doc-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 50% at 110% -10%,rgba(6,182,212,.25) 0%,transparent 60%);pointer-events:none}
        .doc-hero h1{color:#fff;font-size:1.6rem;font-weight:800;margin-bottom:.4rem;position:relative;z-index:1}
        .doc-hero p{color:rgba(255,255,255,.65);font-size:.88rem;margin:0;position:relative;z-index:1}
        .doc-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(201,147,58,.15);border:1px solid rgba(201,147,58,.35);color:var(--gold-light);font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:4px 12px;border-radius:100px;margin-bottom:.85rem;position:relative;z-index:1}

        /* ── CALLOUT / ALERT BOX ── */
        .callout{border-radius:10px;padding:1rem 1.1rem;margin:1.2rem 0;display:flex;gap:.75rem;align-items:flex-start;font-size:.88rem}
        .callout-info{background:#eff6ff;border-left:4px solid #3b82f6;color:#1e40af}
        .callout-warn{background:#fffbeb;border-left:4px solid #f59e0b;color:#92400e}
        .callout-success{background:#f0fdf4;border-left:4px solid #22c55e;color:#15803d}
        .callout-danger{background:#fef2f2;border-left:4px solid #ef4444;color:#b91c1c}
        .callout-icon{font-size:1.1rem;flex-shrink:0;margin-top:1px}
        .callout-body strong{display:block;font-weight:700;margin-bottom:2px}

        /* ── STEP LIST ── */
        .step-list{list-style:none;padding:0;margin:1rem 0}
        .step-list li{display:flex;gap:.75rem;align-items:flex-start;padding:.6rem 0;border-bottom:1px solid var(--border-light)}
        .step-list li:last-child{border:none}
        .step-num{
            width:26px;height:26px;border-radius:50%;flex-shrink:0;
            background:linear-gradient(135deg,var(--navy-mid),var(--navy-light));
            color:#fff;font-size:.72rem;font-weight:800;
            display:flex;align-items:center;justify-content:center;margin-top:1px;
        }
        .step-num.done{background:linear-gradient(135deg,#22c55e,#16a34a)}

        /* ── STAGE TABLE ── */
        .stage-table{width:100%;border-collapse:collapse;font-size:.85rem;margin:1rem 0}
        .stage-table th{background:var(--navy);color:#fff;padding:.55rem .9rem;text-align:left;font-size:.75rem;font-weight:700;letter-spacing:.04em}
        .stage-table td{padding:.5rem .9rem;border-bottom:1px solid var(--border-light);vertical-align:top}
        .stage-table tr:nth-child(even) td{background:#f8fafc}
        .stage-table tr:last-child td{border-bottom:none}
        .stage-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700}
        .sb-done{background:#dcfce7;color:#15803d}
        .sb-active{background:#dbeafe;color:#1d4ed8}
        .sb-last{background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0}

        /* ── FEATURE GRID ── */
        .feature-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.75rem;margin:1rem 0}
        .feature-card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1rem;transition:box-shadow .2s,transform .2s}
        .feature-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px)}
        .feature-card .fc-icon{font-size:1.25rem;margin-bottom:.5rem;color:var(--navy-mid);line-height:1;display:flex;align-items:center}
        .feature-card .fc-title{font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:.25rem}
        .feature-card .fc-desc{font-size:.78rem;color:var(--text-muted);line-height:1.5}

        /* ── DIVIDER ── */
        .doc-divider{border:none;border-top:1px solid var(--border-light);margin:2.5rem 0}

        /* ── TAG INLINE ── */
        .tag{display:inline-flex;align-items:center;gap:3px;padding:1px 7px;border-radius:5px;font-size:.75rem;font-weight:700}
        .tag-blue{background:#dbeafe;color:#1d4ed8}
        .tag-green{background:#dcfce7;color:#15803d}
        .tag-red{background:#fee2e2;color:#b91c1c}
        .tag-yellow{background:#fef3c7;color:#b45309}
        .tag-gray{background:#f1f5f9;color:#475569}

        /* ── MOBILE ── */
        @media(max-width:1100px){.docs-toc{display:none}}
        @media(max-width:768px){
            .docs-sidebar{display:none}
            .docs-content{padding:1.5rem 1rem 3rem}
            .doc-hero h1{font-size:1.3rem}
        }

        /* ── ANCHOR OFFSET ── */
        [id]{scroll-margin-top:80px}
    </style>
</head>
<body>

<!-- TOP NAV -->
<nav class="top-nav">
    <a href="{{ url('/') }}" class="brand">
        <img src="{{ asset('images/metrologi.png') }}" alt="Logo">
        <span>BP SUML</span>
    </a>
    <div class="divider"></div>
    <span class="page-title"><i class="bi bi-book me-1"></i>Panduan Pengguna</span>
    <div class="ms-auto" style="display:flex;align-items:center;gap:.75rem">
        <a href="{{ url('/') }}" style="font-size:.8rem;color:var(--text-muted);font-weight:600"><i class="bi bi-house me-1"></i>Beranda</a>
        @auth
            <a href="{{ route('dashboard') }}" class="btn-login"><i class="bi bi-speedometer2"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
        @endauth
    </div>
</nav>

<div class="docs-layout">

    <!-- LEFT SIDEBAR -->
    <aside class="docs-sidebar" id="docsSidebar">
        <div class="sidebar-section-label">Mulai</div>
        <a href="#pengenalan" class="sidebar-link active"><i class="bi bi-info-circle"></i>Pengenalan Sistem</a>
        <a href="#fitur-utama" class="sidebar-link"><i class="bi bi-grid-1x2"></i>Fitur Utama</a>
        <a href="#cara-login" class="sidebar-link"><i class="bi bi-box-arrow-in-right"></i>Login & Akun</a>

        <div class="sidebar-section-label">Panduan Pengajuan</div>
        <a href="#ajukan-surat" class="sidebar-link"><i class="bi bi-send"></i>Mengajukan Surat</a>
        <a href="#format-file" class="sidebar-link"><i class="bi bi-file-earmark"></i>Format & Ukuran File</a>
        <a href="#jenis-surat" class="sidebar-link"><i class="bi bi-collection"></i>Jenis & Sifat Surat</a>
        <a href="#draft" class="sidebar-link"><i class="bi bi-pencil-square"></i>Draft Surat</a>
        <a href="#edit-surat" class="sidebar-link"><i class="bi bi-pen"></i>Edit & Batas Waktu</a>

        <div class="sidebar-section-label">Alur & Status</div>
        <a href="#alur-10-tahap" class="sidebar-link"><i class="bi bi-diagram-3"></i>Alur 10 Tahap</a>
        <a href="#tracking" class="sidebar-link"><i class="bi bi-geo-alt"></i>Tracking Real-time</a>
        <a href="#sla" class="sidebar-link"><i class="bi bi-speedometer2"></i>SLA & Jam Kerja</a>
        <a href="#revisi" class="sidebar-link"><i class="bi bi-arrow-counterclockwise"></i>Revisi & Tolak</a>
        <a href="#hapus-surat" class="sidebar-link"><i class="bi bi-trash"></i>Hapus Surat</a>

        <div class="sidebar-section-label">Fitur Lainnya</div>
        <a href="#notifikasi" class="sidebar-link"><i class="bi bi-bell"></i>Notifikasi</a>
        <a href="#template" class="sidebar-link"><i class="bi bi-file-earmark-word"></i>Template Surat</a>
        <a href="#statistik" class="sidebar-link"><i class="bi bi-bar-chart"></i>Statistik</a>
        <a href="#aspirasi" class="sidebar-link"><i class="bi bi-chat-dots"></i>Aspirasi</a>
        <a href="#verifikasi" class="sidebar-link"><i class="bi bi-qr-code-scan"></i>Verifikasi QR</a>
        <a href="#pembersihan" class="sidebar-link"><i class="bi bi-recycle"></i>Pembersihan Otomatis</a>
    </aside>

    <!-- MAIN -->
    <div class="docs-main">
        <main class="docs-content">

            <!-- HERO -->
            <div class="doc-hero">
                <div class="doc-badge"><i class="bi bi-book"></i> Dokumentasi Resmi</div>
                <h1>Panduan Pengguna Sistem Persuratan</h1>
                <p>Balai Pengelolaan Sarana dan Utilitas Metrologi Legal (BP SUML) &mdash; Panduan lengkap penggunaan sistem manajemen surat kedinasan.</p>
            </div>

            <!-- PENGENALAN -->
            <section id="pengenalan">
                <h2><i class="bi bi-info-circle me-2 text-primary"></i>Pengenalan Sistem</h2>
                <p>Sistem Persuratan BP SUML adalah platform digital untuk mengelola seluruh alur surat kedinasan secara <strong>terintegrasi, transparan, dan efisien</strong>. Mulai dari pengajuan oleh pegawai, verifikasi berjenjang, penomoran resmi, tanda tangan digital, hingga pengarsipan — semuanya dilakukan dalam satu sistem.</p>
                <p>Sistem ini menggantikan proses manual berbasis kertas dan memungkinkan <strong>tracking real-time</strong> status surat di setiap tahapan.</p>
                <div class="callout callout-info">
                    <span class="callout-icon"><i class="bi bi-info-circle-fill"></i></span>
                    <div class="callout-body"><strong>Akses Sistem</strong>Untuk menggunakan sistem ini Anda memerlukan akun pegawai BP SUML yang telah terdaftar dan diverifikasi oleh Admin.</div>
                </div>
            </section>

            <!-- FITUR UTAMA -->
            <section id="fitur-utama">
                <h2><i class="bi bi-grid-1x2 me-2 text-primary"></i>Fitur Utama</h2>
                <div class="feature-grid">
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-send-fill"></i></div><div class="fc-title">Pengajuan Surat</div><div class="fc-desc">Ajukan surat kedinasan dengan upload file .docx dan lampiran</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-diagram-3-fill"></i></div><div class="fc-title">Tracking 10 Tahap</div><div class="fc-desc">Pantau posisi surat Anda di setiap tahap pemrosesan</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-speedometer2"></i></div><div class="fc-title">Monitoring SLA</div><div class="fc-desc">Pantau batas waktu 30 jam kerja per pengajuan surat</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-bell-fill"></i></div><div class="fc-title">Notifikasi Realtime</div><div class="fc-desc">Pemberitahuan otomatis setiap perubahan status surat</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-bar-chart-fill"></i></div><div class="fc-title">Statistik Personal</div><div class="fc-desc">Grafik tren dan distribusi pengajuan surat Anda</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-file-earmark-word-fill"></i></div><div class="fc-title">Template Surat</div><div class="fc-desc">Unduh template standar surat kedinasan BP SUML</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-shield-check-fill"></i></div><div class="fc-title">Verifikasi QR</div><div class="fc-desc">Cek keaslian surat melalui QR Code atau UUID</div></div>
                    <div class="feature-card"><div class="fc-icon"><i class="bi bi-chat-dots-fill"></i></div><div class="fc-title">Aspirasi & Feedback</div><div class="fc-desc">Sampaikan saran, keluhan, atau kendala ke Admin & IT</div></div>
                </div>
            </section>

            <hr class="doc-divider">

            <!-- LOGIN -->
            <section id="cara-login">
                <h2><i class="bi bi-box-arrow-in-right me-2 text-primary"></i>Login & Akun</h2>
                <p>Akses sistem melalui halaman utama BP SUML, lalu klik tombol <strong>"Masuk / Login"</strong>. Masukkan <strong>email</strong> dan <strong>kata sandi</strong> akun pegawai Anda.</p>
                <ul class="step-list">
                    <li><span class="step-num">1</span><div>Buka halaman <a href="{{ url('/') }}">{{ url('/') }}</a> lalu klik <strong>Masuk</strong></div></li>
                    <li><span class="step-num">2</span><div>Masukkan <strong>email</strong> dan <strong>kata sandi</strong> akun pegawai</div></li>
                    <li><span class="step-num">3</span><div>Klik <strong>"Login"</strong> — sistem akan mengarahkan ke Dashboard</div></li>
                    <li><span class="step-num done"><i class="bi bi-check-lg" style="font-size:.7rem"></i></span><div>Anda sudah bisa menggunakan semua fitur</div></li>
                </ul>
                <div class="callout callout-warn">
                    <span class="callout-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    <div class="callout-body"><strong>Lupa Kata Sandi?</strong>Klik link <em>"Lupa kata sandi?"</em> di halaman login. Instruksi reset akan dikirim ke email terdaftar Anda.</div>
                </div>
                <h3>Profil & Pengaturan Akun</h3>
                <p>Kelola profil Anda melalui menu <strong>Profil & Sesi</strong> di sidebar, atau klik foto profil di pojok kanan atas. Di sini Anda bisa:</p>
                <ul>
                    <li>Mengubah nama, foto profil, dan informasi kontak</li>
                    <li>Mengganti kata sandi</li>
                    <li>Melihat dan mencabut sesi aktif di perangkat lain</li>
                    <li>Mengelola multi-akun (jika memiliki lebih dari satu akun)</li>
                </ul>
            </section>

            <hr class="doc-divider">

            <!-- AJUKAN SURAT -->
            <section id="ajukan-surat">
                <h2><i class="bi bi-send me-2 text-primary"></i>Mengajukan Surat</h2>
                <p>Pengajuan surat dilakukan melalui tombol <strong>"Ajukan Surat"</strong> di sidebar (tombol biru). Ikuti langkah berikut:</p>
                <ul class="step-list">
                    <li><span class="step-num">1</span><div>Klik <strong>"Ajukan Surat"</strong> di sidebar kiri</div></li>
                    <li><span class="step-num">2</span><div>Isi <strong>Judul Surat</strong>, <strong>Jenis</strong>, <strong>Sifat</strong>, dan <strong>Tujuan Surat</strong></div></li>
                    <li><span class="step-num">3</span><div>Unggah <strong>file surat utama</strong> (format <code>.docx</code>, maks. 10MB)</div></li>
                    <li><span class="step-num">4</span><div>Unggah <strong>lampiran</strong> jika ada (PDF, JPG, PNG, .docx, .xlsx — maks. 10MB)</div></li>
                    <li><span class="step-num">5</span><div>Isi <strong>catatan pengusul</strong> (opsional, maks. 100 karakter)</div></li>
                    <li><span class="step-num">6</span><div>Klik <strong>"Ajukan Sekarang"</strong> — surat masuk ke tahap 1</div></li>
                    <li><span class="step-num done"><i class="bi bi-check-lg" style="font-size:.7rem"></i></span><div>Status surat menjadi <span class="tag tag-blue">Diajukan</span> dan mulai diproses Admin</div></li>
                </ul>
                <div class="callout callout-info">
                    <span class="callout-icon"><i class="bi bi-lightbulb-fill"></i></span>
                    <div class="callout-body"><strong>Tips</strong>Gunakan fitur <strong>Simpan Draft</strong> jika belum siap mengajukan. Draft tersimpan dan bisa dilanjutkan kapan saja.</div>
                </div>
            </section>

            <!-- FORMAT FILE -->
            <section id="format-file">
                <h2><i class="bi bi-file-earmark me-2 text-primary"></i>Format & Ukuran File</h2>
                <table class="stage-table">
                    <thead><tr><th>Jenis Upload</th><th>Format Diterima</th><th>Ukuran Maks.</th></tr></thead>
                    <tbody>
                        <tr><td><strong>File Surat Utama</strong></td><td><code>.docx</code> (Microsoft Word)</td><td>10 MB</td></tr>
                        <tr><td><strong>Lampiran Pendukung</strong></td><td><code>.pdf</code>, <code>.jpg</code>, <code>.png</code>, <code>.docx</code>, <code>.xlsx</code></td><td>10 MB</td></tr>
                    </tbody>
                </table>
                <div class="callout callout-warn">
                    <span class="callout-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    <div class="callout-body"><strong>Perhatian</strong>Pastikan file <strong>tidak terpassword/terenkripsi</strong>. File yang terkunci tidak dapat diproses oleh sistem.</div>
                </div>
            </section>

            <!-- JENIS SURAT -->
            <section id="jenis-surat">
                <h2><i class="bi bi-collection me-2 text-primary"></i>Jenis & Sifat Surat</h2>
                <h3>Jenis Surat yang Tersedia</h3>
                <ul>
                    <li>Nota Dinas</li>
                    <li>Surat Dinas</li>
                    <li>Surat Keputusan (SK)</li>
                    <li>Surat Pernyataan</li>
                    <li>Surat Keterangan</li>
                    <li>Surat Undangan</li>
                    <li>Surat Lainnya</li>
                </ul>
                <h3>Tingkat Sifat Surat</h3>
                <ul>
                    <li><span class="tag tag-gray">Biasa</span> — Prosedur standar, tidak ada penanganan khusus</li>
                    <li><span class="tag tag-yellow">Segera</span> — Memerlukan perhatian lebih cepat dari Admin</li>
                    <li><span class="tag tag-red">Rahasia</span> — Dokumen dengan akses sangat terbatas</li>
                </ul>
            </section>

            <!-- DRAFT -->
            <section id="draft">
                <h2><i class="bi bi-pencil-square me-2 text-primary"></i>Draft Surat</h2>
                <p>Draft adalah surat yang <strong>disimpan sementara</strong> namun belum resmi diajukan ke Admin. Surat berstatus Draft bisa diedit, dilengkapi, atau dihapus kapan saja.</p>
                <p>Akses semua draft Anda melalui menu <strong>Surat Saya → Draft</strong> di sidebar.</p>
                <div class="callout callout-success">
                    <span class="callout-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <div class="callout-body"><strong>Keunggulan Draft</strong>Tidak ada batas waktu penyimpanan draft. Simpan kapan saja, lanjutkan kapan saja.</div>
                </div>
            </section>

            <!-- EDIT SURAT -->
            <section id="edit-surat">
                <h2><i class="bi bi-pen me-2 text-primary"></i>Edit & Batas Waktu</h2>
                <p>Setelah surat diajukan, Anda masih bisa mengedit data (Judul, Jenis, Tujuan) dalam waktu <strong>15 menit</strong> sejak pengajuan pertama. Setelah itu, fitur edit akan <strong>terkunci otomatis</strong> demi menjaga integritas data selama proses verifikasi Admin.</p>
                <div class="callout callout-warn">
                    <span class="callout-icon"><i class="bi bi-clock-fill"></i></span>
                    <div class="callout-body"><strong>Batas Catatan Pengusul</strong>Catatan pengusul dibatasi <strong>100 karakter</strong> agar informasi yang disampaikan padat dan langsung ke poin. Jika ada detail panjang, masukkan ke dalam isi dokumen surat atau lampiran.</div>
                </div>
            </section>

            <hr class="doc-divider">

            <!-- ALUR 10 TAHAP -->
            <section id="alur-10-tahap">
                <h2><i class="bi bi-diagram-3 me-2 text-primary"></i>Alur 10 Tahap Pemrosesan</h2>
                <p>Setiap surat yang diajukan akan melewati alur berjenjang berikut:</p>
                <table class="stage-table">
                    <thead><tr><th>Tahap</th><th>Nama</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td><strong>1</strong></td><td>Ajukan</td><td>Surat dikirim oleh user, masuk ke antrian Admin</td></tr>
                        <tr><td><strong>2</strong></td><td>Verifikasi Aspirasi</td><td>Pemeriksaan awal oleh Admin Aspirasi</td></tr>
                        <tr><td><strong>3</strong></td><td>Verifikasi Kasubbag TU</td><td>Validasi oleh Kepala Sub Bagian Tata Usaha</td></tr>
                        <tr><td><strong>4</strong></td><td>Verifikasi Kepala Balai</td><td>Persetujuan akhir oleh pimpinan</td></tr>
                        <tr><td><strong>5</strong></td><td>Penomoran</td><td>Pemberian nomor resmi oleh Admin Aspirasi</td></tr>
                        <tr><td><strong>6</strong></td><td>Tanda Tangan DS</td><td>Proses tanda tangan Digital Signature</td></tr>
                        <tr><td><strong>7</strong></td><td>Pengiriman TNDe</td><td>Proses pengiriman melalui sistem TNDe</td></tr>
                        <tr><td><strong>8</strong></td><td>Pengiriman Srikandi</td><td>Proses melalui sistem Srikandi</td></tr>
                        <tr><td><strong>9</strong></td><td>Pengarsipan</td><td>Pendataan ke dalam arsip fisik/digital</td></tr>
                        <tr><td><strong>10</strong></td><td><span class="stage-badge sb-last"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span></td><td>Seluruh proses selesai dilaksanakan</td></tr>
                    </tbody>
                </table>
                <div class="callout callout-info">
                    <span class="callout-icon"><i class="bi bi-info-circle-fill"></i></span>
                    <div class="callout-body"><strong>Catatan</strong>Pada setiap tahap, Admin dapat <span class="tag tag-green">Menyetujui</span> atau <span class="tag tag-red">Menolak</span> surat. Jika ditolak, Anda akan mendapat notifikasi dan bisa melakukan revisi.</div>
                </div>
            </section>

            <!-- TRACKING -->
            <section id="tracking">
                <h2><i class="bi bi-geo-alt me-2 text-primary"></i>Tracking Real-time</h2>
                <p>Pantau posisi surat Anda secara real-time melalui halaman <strong>Detail Surat</strong>. Klik surat manapun di menu <strong>Surat Saya</strong> untuk melihat timeline tracking lengkap.</p>
                <p>Tampilan tracking menunjukkan:</p>
                <ul>
                    <li>Tahap yang sudah selesai <span class="tag tag-green"><i class="bi bi-check-lg"></i> Done</span></li>
                    <li>Tahap yang sedang aktif <span class="tag tag-blue"><i class="bi bi-hourglass-split"></i> Aktif</span></li>
                    <li>Tahap yang menunggu</li>
                    <li>Catatan dari Admin di setiap tahap</li>
                    <li>Waktu masuk dan selesai tiap tahap</li>
                </ul>
            </section>

            <!-- SLA -->
            <section id="sla">
                <h2><i class="bi bi-speedometer2 me-2 text-primary"></i>SLA & Jam Kerja</h2>
                <p>SLA (Service Level Agreement) adalah batas waktu maksimal pemrosesan surat, yaitu <strong>30 jam kerja</strong>. Waktu hanya dihitung saat jam kerja aktif:</p>
                <ul>
                    <li><strong>Senin – Kamis:</strong> 07.30 – 16.00 WIB</li>
                    <li><strong>Jumat:</strong> 07.30 – 16.30 WIB</li>
                    <li><strong>Sabtu – Minggu:</strong> Libur (tidak dihitung)</li>
                </ul>
                <p>Jika proses melebihi 30 jam kerja, sistem akan menampilkan indikator <span class="tag tag-red">Terlambat</span> beserta selisih waktunya.</p>
                <div class="callout callout-warn">
                    <span class="callout-icon"><i class="bi bi-calendar-x-fill"></i></span>
                    <div class="callout-body"><strong>Pengajuan di Luar Jam Kerja</strong>Jika Anda mengajukan surat setelah jam kerja atau di akhir pekan, sistem menampilkan status <em>TUTUP</em>. Hitungan SLA baru dimulai saat jam kerja berikutnya aktif.</div>
                </div>
                <p>Monitor SLA seluruh surat Anda secara visual melalui menu <strong>Monitoring SLA</strong> di sidebar.</p>
            </section>

            <!-- REVISI -->
            <section id="revisi">
                <h2><i class="bi bi-arrow-counterclockwise me-2 text-primary"></i>Revisi & Penolakan</h2>
                <p>Jika Admin menolak surat Anda, Anda akan mendapat notifikasi otomatis. Ikuti langkah ini:</p>
                <ul class="step-list">
                    <li><span class="step-num">1</span><div>Buka <strong>Detail Surat</strong> dari menu Surat Saya</div></li>
                    <li><span class="step-num">2</span><div>Baca <strong>catatan penolakan</strong> dari Admin di bagian timeline</div></li>
                    <li><span class="step-num">3</span><div>Perbaiki dokumen sesuai catatan revisi</div></li>
                    <li><span class="step-num">4</span><div>Klik <strong>"Upload Ulang File Perbaikan"</strong> dan unggah file baru</div></li>
                    <li><span class="step-num done"><i class="bi bi-check-lg" style="font-size:.7rem"></i></span><div>Surat kembali ke <strong>Tahap 2 (Verifikasi Aspirasi)</strong> dan diproses ulang</div></li>
                </ul>
                <div class="callout callout-danger">
                    <span class="callout-icon"><i class="bi bi-clock-fill"></i></span>
                    <div class="callout-body"><strong>Batas Waktu Revisi</strong>Surat yang ditolak dan tidak direvisi dalam <strong>5 hari</strong> akan dihapus permanen oleh sistem secara otomatis.</div>
                </div>
            </section>

            <!-- HAPUS SURAT -->
            <section id="hapus-surat">
                <h2><i class="bi bi-trash me-2 text-primary"></i>Hapus Surat</h2>
                <p>Ketentuan penghapusan berbeda berdasarkan status surat:</p>
                <ul>
                    <li><span class="tag tag-green">Draft</span> — Dapat dihapus langsung tanpa persetujuan</li>
                    <li><span class="tag tag-red">Ditolak / Selesai</span> — Dapat dihapus langsung</li>
                    <li><span class="tag tag-blue">Diproses (Tahap 1–2)</span> — Dapat dihapus langsung</li>
                    <li><span class="tag tag-yellow">Diproses (Tahap 3+)</span> — Perlu mengajukan <strong>Permintaan Hapus</strong> yang disetujui Admin</li>
                </ul>
                <p>Untuk surat yang butuh persetujuan, klik ikon <i class="bi bi-trash"></i> di halaman detail surat, isi alasan penghapusan, lalu kirim. Admin akan meninjau dan memutuskan.</p>
            </section>

            <hr class="doc-divider">

            <!-- NOTIFIKASI -->
            <section id="notifikasi">
                <h2><i class="bi bi-bell me-2 text-primary"></i>Notifikasi</h2>
                <p>Sistem mengirimkan notifikasi otomatis setiap kali ada perubahan status surat Anda. Notifikasi bisa diakses melalui:</p>
                <ul>
                    <li><strong>Ikon lonceng</strong> di pojok kanan atas navbar — panel slide-in</li>
                    <li>Menu <strong>Notifikasi</strong> di sidebar — halaman lengkap semua notifikasi</li>
                </ul>
                <h3>Mengelola Notifikasi</h3>
                <ul>
                    <li>Klik notifikasi untuk membukanya (otomatis tandai sudah dibaca)</li>
                    <li>Klik <strong>"Tandai semua dibaca"</strong> untuk langsung bersihkan semua badge</li>
                    <li>Klik ikon <i class="bi bi-trash"></i> di samping notifikasi untuk menghapusnya</li>
                    <li>Klik <strong>"Hapus semua"</strong> untuk membersihkan seluruh notifikasi sekaligus</li>
                </ul>
                <div class="callout callout-info">
                    <span class="callout-icon"><i class="bi bi-info-circle-fill"></i></span>
                    <div class="callout-body"><strong>Pembersihan Otomatis</strong>Notifikasi yang berumur lebih dari <strong>1 minggu</strong> dihapus otomatis setiap Senin pukul 01.00 WIB untuk menjaga kebersihan sistem.</div>
                </div>
            </section>

            <!-- TEMPLATE -->
            <section id="template">
                <h2><i class="bi bi-file-earmark-word me-2 text-primary"></i>Template Surat</h2>
                <p>Sistem menyediakan template surat standar kedinasan BP SUML yang bisa diunduh secara gratis. Akses template melalui:</p>
                <ul>
                    <li>Menu <strong>Template</strong> di sidebar</li>
                    <li>Widget <strong>"Template Surat"</strong> di Dashboard</li>
                </ul>
                <p>Unduh template yang sesuai, isi sesuai kebutuhan, lalu upload kembali saat mengajukan surat.</p>
            </section>

            <!-- STATISTIK -->
            <section id="statistik">
                <h2><i class="bi bi-bar-chart me-2 text-primary"></i>Statistik</h2>
                <p>Menu <strong>Statistik</strong> di sidebar menampilkan visualisasi data pengajuan surat Anda secara personal:</p>
                <ul>
                    <li><strong>Grafik Tren Bulanan</strong> — Jumlah pengajuan setiap bulan</li>
                    <li><strong>Distribusi Jenis Surat</strong> — Diagram proporsi jenis surat (Nota Dinas, SK, dll)</li>
                    <li><strong>Ringkasan Status</strong> — Total Draft, Diproses, Selesai, Ditolak</li>
                    <li><strong>Export Excel</strong> — Unduh seluruh data surat ke spreadsheet .xlsx</li>
                </ul>
            </section>

            <!-- ASPIRASI -->
            <section id="aspirasi">
                <h2><i class="bi bi-chat-dots me-2 text-primary"></i>Aspirasi</h2>
                <p>Menu <strong>Aspirasi</strong> adalah saluran resmi untuk menyampaikan saran, keluhan, atau masukan. Tersedia dua tujuan pengiriman:</p>
                <ul>
                    <li><strong>Admin</strong> — Untuk pertanyaan seputar surat, alur, atau kebijakan</li>
                    <li><strong>IT Support</strong> — Untuk kendala teknis, error, atau masalah tampilan</li>
                </ul>
                <h3>Cara Menggunakan</h3>
                <ul class="step-list">
                    <li><span class="step-num">1</span><div>Buka menu <strong>Aspirasi</strong> di sidebar</div></li>
                    <li><span class="step-num">2</span><div>Klik <strong>"Tulis Aspirasi"</strong></div></li>
                    <li><span class="step-num">3</span><div>Pilih tujuan: <strong>Admin</strong> atau <strong>IT Support</strong></div></li>
                    <li><span class="step-num">4</span><div>Pilih kategori: Saran, Keluhan, atau Masukan</div></li>
                    <li><span class="step-num">5</span><div>Isi subjek dan detail aspirasi</div></li>
                    <li><span class="step-num done"><i class="bi bi-check-lg" style="font-size:.7rem"></i></span><div>Klik <strong>Kirim</strong> — aspirasi masuk ke sistem</div></li>
                </ul>
            </section>

            <!-- VERIFIKASI QR -->
            <section id="verifikasi">
                <h2><i class="bi bi-qr-code-scan me-2 text-primary"></i>Verifikasi Keaslian Surat (QR)</h2>
                <p>Setiap surat yang telah selesai dilengkapi dengan <strong>QR Code</strong> untuk verifikasi keaslian dokumen. QR Code tercetak otomatis pada halaman surat.</p>
                <p>Cara verifikasi:</p>
                <ul>
                    <li><strong>Scan QR Code</strong> menggunakan kamera HP — terbuka halaman verifikasi resmi</li>
                    <li>Atau masukkan <strong>UUID surat</strong> melalui tombol verifikasi di halaman utama</li>
                </ul>
                <div class="callout callout-success">
                    <span class="callout-icon"><i class="bi bi-shield-check-fill"></i></span>
                    <div class="callout-body"><strong>Verifikasi Publik</strong>Fitur verifikasi QR dapat diakses oleh siapa saja tanpa perlu login, sehingga pihak penerima surat dapat memverifikasi keaslian dokumen secara mandiri.</div>
                </div>
            </section>

            <!-- PEMBERSIHAN OTOMATIS -->
            <section id="pembersihan">
                <h2><i class="bi bi-recycle me-2 text-primary"></i>Pembersihan Otomatis</h2>
                <p>Untuk menjaga performa server, sistem melakukan pembersihan data secara terjadwal:</p>
                <table class="stage-table">
                    <thead><tr><th>Kondisi</th><th>Tindakan Otomatis</th><th>Waktu</th></tr></thead>
                    <tbody>
                        <tr><td>Surat berstatus <strong>Selesai</strong></td><td>File fisik (word/lampiran) dihapus</td><td>3 hari setelah selesai</td></tr>
                        <tr><td>Surat <strong>Ditolak</strong> tanpa revisi</td><td>Surat dihapus permanen</td><td>5 hari setelah ditolak</td></tr>
                        <tr><td>Notifikasi lama</td><td>Notifikasi dihapus otomatis</td><td>Setiap Senin 01.00 WIB</td></tr>
                    </tbody>
                </table>
                <div class="callout callout-info">
                    <span class="callout-icon"><i class="bi bi-info-circle-fill"></i></span>
                    <div class="callout-body"><strong>Riwayat Tracking Tetap Tersimpan</strong>Meskipun file fisik dihapus, <strong>riwayat tracking</strong> dan data surat tetap tersimpan di sistem dan bisa dilihat kapan saja.</div>
                </div>
            </section>

            <hr class="doc-divider">
            <p style="font-size:.82rem;color:var(--text-muted);text-align:center">
                Butuh bantuan lebih lanjut? Gunakan menu <strong>Aspirasi</strong> setelah login, atau hubungi Admin BP SUML.<br>
                &copy; {{ date('Y') }} Balai Pengelolaan SUML. Semua hak dilindungi.
            </p>

        </main>

        <!-- RIGHT TOC -->
        <aside class="docs-toc" id="docsToc">
            <div class="toc-title">On this page</div>
            <a href="#pengenalan" class="toc-link">Pengenalan Sistem</a>
            <a href="#fitur-utama" class="toc-link">Fitur Utama</a>
            <a href="#cara-login" class="toc-link">Login & Akun</a>
            <a href="#ajukan-surat" class="toc-link">Mengajukan Surat</a>
            <a href="#format-file" class="toc-link indent">Format & Ukuran File</a>
            <a href="#jenis-surat" class="toc-link indent">Jenis & Sifat Surat</a>
            <a href="#draft" class="toc-link indent">Draft Surat</a>
            <a href="#edit-surat" class="toc-link indent">Edit & Batas Waktu</a>
            <a href="#alur-10-tahap" class="toc-link">Alur 10 Tahap</a>
            <a href="#tracking" class="toc-link indent">Tracking Real-time</a>
            <a href="#sla" class="toc-link indent">SLA & Jam Kerja</a>
            <a href="#revisi" class="toc-link indent">Revisi & Penolakan</a>
            <a href="#hapus-surat" class="toc-link indent">Hapus Surat</a>
            <a href="#notifikasi" class="toc-link">Notifikasi</a>
            <a href="#template" class="toc-link">Template Surat</a>
            <a href="#statistik" class="toc-link">Statistik</a>
            <a href="#aspirasi" class="toc-link">Aspirasi</a>
            <a href="#verifikasi" class="toc-link">Verifikasi QR</a>
            <a href="#pembersihan" class="toc-link">Pembersihan Otomatis</a>
        </aside>

    </div><!-- /.docs-main -->
</div><!-- /.docs-layout -->

<script>
(function(){
    // Active link highlight on scroll
    var allSections = document.querySelectorAll('section[id]');
    var sidebarLinks = document.querySelectorAll('.docs-sidebar .sidebar-link');
    var tocLinks = document.querySelectorAll('.docs-toc .toc-link');

    function setActive(id) {
        sidebarLinks.forEach(function(l){
            l.classList.toggle('active', l.getAttribute('href') === '#'+id);
        });
        tocLinks.forEach(function(l){
            l.classList.toggle('active', l.getAttribute('href') === '#'+id);
        });
    }

    var observer = new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
            if(entry.isIntersecting) setActive(entry.target.id);
        });
    }, { rootMargin:'-20% 0px -70% 0px' });

    allSections.forEach(function(s){ observer.observe(s); });

    // Smooth click
    document.querySelectorAll('a[href^="#"]').forEach(function(a){
        a.addEventListener('click', function(e){
            var target = document.querySelector(a.getAttribute('href'));
            if(target){ e.preventDefault(); target.scrollIntoView({behavior:'smooth'}); }
        });
    });
})();
</script>
</body>
</html>
