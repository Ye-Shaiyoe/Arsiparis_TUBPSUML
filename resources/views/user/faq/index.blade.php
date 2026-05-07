@extends('layouts.user')
@section('title', 'FAQ - Pusat Bantuan')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400&display=swap');

    :root {
        --navy: #0f2444;
        --navy-mid: #1e3a5f;
        --navy-light: #2d5a8e;
        --gold: #c9933a;
        --gold-light: #e8b96a;
        --cream: #faf8f4;
        --white: #ffffff;
        --text-dark: #0f1e2e;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --border-light: #f1f5f9;
        --shadow-sm: 0 1px 3px rgba(15,36,68,0.06), 0 1px 2px rgba(15,36,68,0.04);
        --shadow-md: 0 4px 16px rgba(15,36,68,0.08), 0 2px 6px rgba(15,36,68,0.05);
        --shadow-lg: 0 12px 40px rgba(15,36,68,0.12), 0 4px 12px rgba(15,36,68,0.07);
    }

    /* ── Page Wrapper ── */
    .faq-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--cream);
        min-height: 100vh;
        padding-bottom: 80px;
    }

    /* ── Hero Header ── */
    .faq-hero {
        background: linear-gradient(160deg, var(--navy) 0%, var(--navy-mid) 55%, #1a4a7a 100%);
        padding: 64px 24px 100px;
        position: relative;
        overflow: hidden;
    }

    .faq-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(ellipse 80% 50% at 50% -10%, rgba(201,147,58,0.18) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 90% 80%, rgba(45,90,142,0.4) 0%, transparent 60%);
        pointer-events: none;
    }

    .faq-hero::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 60px;
        background: var(--cream);
        clip-path: ellipse(55% 100% at 50% 100%);
    }

    .faq-hero-inner {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
    }

    .faq-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(201,147,58,0.15);
        border: 1px solid rgba(201,147,58,0.35);
        color: var(--gold-light);
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 100px;
        margin-bottom: 20px;
    }

    .faq-hero h1 {
        font-family: 'Lora', serif;
        font-size: clamp(28px, 5vw, 40px);
        font-weight: 600;
        color: #ffffff;
        line-height: 1.25;
        margin-bottom: 14px;
        letter-spacing: -0.01em;
    }

    .faq-hero h1 em {
        font-style: italic;
        color: var(--gold-light);
    }

    .faq-hero p {
        color: rgba(255,255,255,0.65);
        font-size: 14.5px;
        line-height: 1.7;
        margin: 0;
    }

    /* ── Search Bar ── */
    .faq-search-wrap {
        max-width: 580px;
        margin: 32px auto 0;
    }

    .faq-search {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 4px 4px 4px 18px;
        gap: 10px;
        transition: all 0.25s;
    }

    .faq-search:focus-within {
        background: rgba(255,255,255,0.15);
        border-color: rgba(201,147,58,0.5);
        box-shadow: 0 0 0 4px rgba(201,147,58,0.12);
    }

    .faq-search i {
        color: rgba(255,255,255,0.45);
        font-size: 15px;
        flex-shrink: 0;
    }

    .faq-search input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 10px 0;
    }

    .faq-search input::placeholder {
        color: rgba(255,255,255,0.4);
    }

    .faq-search-btn {
        background: var(--gold);
        border: none;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .faq-search-btn:hover {
        background: var(--gold-light);
    }

    /* ── Stats Row ── */
    .faq-stats {
        display: flex;
        justify-content: center;
        gap: 32px;
        margin-top: 28px;
        flex-wrap: wrap;
    }

    .faq-stat {
        text-align: center;
    }

    .faq-stat-num {
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .faq-stat-label {
        font-size: 11px;
        color: rgba(255,255,255,0.45);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .faq-stat-divider {
        width: 1px;
        height: 32px;
        background: rgba(255,255,255,0.12);
        align-self: center;
    }

    /* ── Category Tabs ── */
    .faq-tabs-wrap {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        padding: 0 16px;
        margin-top: -18px;
        margin-bottom: 40px;
        position: relative;
        z-index: 10;
    }

    .faq-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 600;
        border: 1.5px solid var(--border);
        background: #fff;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.22s;
        box-shadow: var(--shadow-sm);
        user-select: none;
    }

    .faq-tab:hover {
        border-color: var(--navy-light);
        color: var(--navy-mid);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .faq-tab.active {
        background: var(--navy-mid);
        border-color: var(--navy-mid);
        color: #fff;
        box-shadow: 0 4px 14px rgba(30,58,95,0.3);
    }

    .faq-tab .tab-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.5;
    }

    .faq-tab.active .tab-dot {
        background: var(--gold-light);
        opacity: 1;
    }

    /* ── Main Content ── */
    .faq-body {
        max-width: 820px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ── Section Label ── */
    .section-label {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        margin-top: 40px;
    }

    .section-label:first-child {
        margin-top: 0;
    }

    .section-label-line {
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .section-label-text {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        white-space: nowrap;
    }

    /* ── Accordion ── */
    .faq-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .faq-item {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        box-shadow: var(--shadow-sm);
    }

    .faq-item:hover {
        border-color: #c7d7eb;
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .faq-item.open {
        border-color: var(--navy-light);
        box-shadow: var(--shadow-md);
    }

    .faq-item.hidden-by-search {
        display: none;
    }

    .faq-question {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        background: none;
        border: none;
        text-align: left;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .faq-q-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        transition: background 0.25s;
    }

    .faq-item.open .faq-q-icon {
        background: rgba(30,58,95,0.08);
    }

    .faq-q-text {
        flex: 1;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.45;
    }

    .faq-q-arrow {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: var(--border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), background 0.25s;
        color: var(--text-muted);
        font-size: 12px;
    }

    .faq-item.open .faq-q-arrow {
        transform: rotate(180deg);
        background: var(--navy-mid);
        color: #fff;
    }

    .faq-answer-wrap {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.38s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .faq-answer {
        padding: 0 20px 20px 68px;
        font-size: 13.5px;
        line-height: 1.8;
        color: var(--text-muted);
        border-top: 1px solid var(--border-light);
        padding-top: 16px;
    }

    .faq-answer strong {
        color: var(--text-dark);
        font-weight: 700;
    }

    .faq-answer ul {
        padding-left: 18px;
        margin: 10px 0 0;
    }

    .faq-answer ul li {
        margin-bottom: 6px;
    }

    .faq-answer .tag-success {
        color: #16a34a;
        font-weight: 700;
    }

    /* ── Stage Steps ── */
    .stage-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 14px;
        padding-left: 0;
        list-style: none;
    }

    @media (max-width: 520px) {
        .stage-list { grid-template-columns: 1fr; }
    }

    .stage-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: var(--border-light);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 12.5px;
        line-height: 1.5;
        color: var(--text-dark);
    }

    .stage-num {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: var(--navy-mid);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stage-item:last-child .stage-num {
        background: #16a34a;
    }

    /* ── Image FAQ ── */
    .faq-img {
        width: 100%;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-bottom: 12px;
    }

    /* ── No Results ── */
    #faq-no-results {
        display: none;
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    #faq-no-results .nr-icon {
        font-size: 40px;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    #faq-no-results p {
        font-size: 14px;
    }

    /* ── Contact Card ── */
    .faq-contact {
        margin-top: 56px;
        background: var(--navy);
        border-radius: 24px;
        padding: 48px 36px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .faq-contact::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(201,147,58,0.15) 0%, transparent 60%);
        pointer-events: none;
    }

    .faq-contact-inner {
        position: relative;
        z-index: 2;
    }

    .faq-contact h4 {
        font-family: 'Lora', serif;
        font-size: 22px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 10px;
    }

    .faq-contact p {
        color: rgba(255,255,255,0.55);
        font-size: 14px;
        max-width: 600px;
        margin: 0 auto 28px;
        line-height: 1.7;
    }

    .faq-contact-btns {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-wa {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #25d366;
        color: #fff;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(37,211,102,0.35);
    }

    .btn-wa:hover {
        background: #20bc5a;
        transform: translateY(-2px);
        color: #fff;
        box-shadow: 0 6px 20px rgba(37,211,102,0.45);
    }

    .btn-email {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.08);
        border: 1.5px solid rgba(255,255,255,0.18);
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.2s;
    }

    .btn-email:hover {
        background: rgba(255,255,255,0.14);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ── Animate In ── */
    .fade-up {
        opacity: 0;
        transform: translateY(18px);
        animation: fadeUp 0.5s ease forwards;
    }

    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-up-1 { animation-delay: 0.05s; }
    .fade-up-2 { animation-delay: 0.12s; }
    .fade-up-3 { animation-delay: 0.2s; }
    .fade-up-4 { animation-delay: 0.28s; }
</style>

<div class="faq-page">

    {{-- ── Hero ── --}}
    <div class="faq-hero fade-up">
        <div class="faq-hero-inner">
            <div class="faq-badge">
                <i class="bi bi-stars"></i>
                Pusat Bantuan
            </div>
            <h1>Ada yang bisa kami <em>bantu?</em></h1>
            <p>Temukan jawaban lengkap seputar pengajuan surat, status proses, template, dan fitur lainnya.</p>

            {{-- Search --}}
            <div class="faq-search-wrap fade-up fade-up-2">
                <div class="faq-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="faqSearch" placeholder="Cari pertanyaan, misal: cara upload, template...">
                    <button class="faq-search-btn" type="button">Cari</button>
                </div>
            </div>

            {{-- Stats --}}
            <div class="faq-stats fade-up fade-up-3">
                <div class="faq-stat">
                    <div class="faq-stat-num">27</div>
                    <div class="faq-stat-label">Artikel FAQ</div>
                </div>
                <div class="faq-stat-divider"></div>
                <div class="faq-stat">
                    <div class="faq-stat-num">10</div>
                    <div class="faq-stat-label">Tahap Proses</div>
                </div>
                <div class="faq-stat-divider"></div>
                <div class="faq-stat">
                    <div class="faq-stat-num">24<span style="font-size:13px;font-weight:600">jam</span></div>
                    <div class="faq-stat-label">Waktu SLA</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Category Tabs ── --}}
    <div class="faq-tabs-wrap fade-up fade-up-4">
        <button class="faq-tab active" data-cat="all">
            <span class="tab-dot"></span> Semua
        </button>
        <button class="faq-tab" data-cat="pengajuan">
            <span class="tab-dot"></span> Pengajuan Surat
        </button>
        <button class="faq-tab" data-cat="status">
            <span class="tab-dot"></span> Status & Proses
        </button>
        <button class="faq-tab" data-cat="akun">
            <span class="tab-dot"></span> Akun & Keamanan
        </button>
        <button class="faq-tab" data-cat="teknis">
            <span class="tab-dot"></span> Teknis & Fitur
        </button>
        <button class="faq-tab" data-cat="tentang">
            <span class="tab-dot"></span> Tentang Website
        </button>
    </div>

    {{-- ── FAQ List ── --}}
    <div class="faq-body">

        <div id="faq-no-results">
            <div class="nr-icon">🔍</div>
            <p>Tidak ditemukan hasil untuk pencarian Anda.<br>Coba kata kunci lain atau hubungi helpdesk kami.</p>
        </div>

        {{-- SECTION: Pengajuan --}}
        <div class="section-label" data-section="pengajuan">
            <div class="section-label-line"></div>
            <span class="section-label-text">Pengajuan Surat</span>
            <div class="section-label-line"></div>
        </div>

        <div class="faq-list">

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📝</div>
                    <span class="faq-q-text">Bagaimana cara mengajukan surat baru?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Klik tombol <strong>"Ajukan Surat Baru"</strong> di dashboard atau menu Surat. Unggah file dokumen dalam format <strong>.docx</strong> (Word) dan lampiran pendukung jika ada (PDF/Gambar). Isi judul, jenis (Nota Dinas, Surat Dinas, dll), dan sifat surat (Biasa, Segera, Rahasia), lalu klik submit. Pastikan pengajuan dilakukan di jam operasional: <strong>Senin–Kamis pukul 07.30–16.00 WIB</strong> atau <strong>Jumat pukul 07.30–16.30 WIB</strong>.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📂</div>
                    <span class="faq-q-text">Format dan ukuran file apa saja yang diterima sistem?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Untuk <strong>file surat utama</strong>, sistem hanya menerima format <strong>.docx</strong> (Microsoft Word). Untuk <strong>lampiran pendukung</strong>, Anda dapat mengunggah file berformat <strong>PDF, JPG, atau PNG</strong>. Ukuran maksimal per file adalah <strong>10 MB</strong>. Pastikan file tidak terpassword/terenkripsi agar dapat diproses sistem.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📄</div>
                    <span class="faq-q-text">Di mana saya bisa mendapatkan template surat?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Kami menyediakan berbagai template surat standar di sidebar menu <strong>"Template Surat"</strong> atau langsung dari widget <strong>"Template Surat"</strong> yang ada di dashboard Anda. Silakan unduh dan sesuaikan isinya sesuai kebutuhan instansi.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📑</div>
                    <span class="faq-q-text">Apa saja jenis dan sifat surat yang tersedia?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Sistem mendukung beberapa <strong>Jenis Surat</strong>:
                        <ul>
                            <li>Nota Dinas, Surat Dinas, Surat Keputusan.</li>
                            <li>Surat Pernyataan, Surat Keterangan, Surat Undangan.</li>
                            <li>Surat Lainnya (untuk kategori di luar daftar).</li>
                        </ul>
                        Serta memiliki 3 tingkat <strong>Sifat Surat</strong>:
                        <ul>
                            <li><strong>Biasa</strong>: Prosedur standar.</li>
                            <li><strong>Segera</strong>: Memerlukan perhatian lebih cepat.</li>
                            <li><strong>Rahasia</strong>: Dokumen dengan akses sangat terbatas.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">❌</div>
                    <span class="faq-q-text">Bisakah saya membatalkan atau menghapus surat yang sudah diajukan?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Jika surat masih berstatus <strong>Draft</strong>, Anda dapat menghapusnya langsung. Namun, jika surat sudah <strong>Diajukan/Diproses</strong>, Anda harus mengirimkan <strong>Permintaan Hapus (Request Delete)</strong> melalui tombol tong sampah di halaman detail surat. Admin akan meninjau alasan pembatalan Anda sebelum menyetujui penghapusan surat tersebut.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🔄</div>
                    <span class="faq-q-text">Bagaimana jika surat saya ditolak / perlu revisi?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Jika surat ditolak, Anda akan menerima notifikasi. Silakan buka detail surat, baca catatan revisi dari admin di bagian timeline/riwayat, perbaiki dokumen Anda, lalu unggah kembali melalui tombol <strong>"Upload Ulang File Perbaikan"</strong>. Proses tracking akan dimulai kembali dari tahap Verifikasi Aspirasi.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">💡</div>
                    <span class="faq-q-text">Apa perbedaan "Draft" dan "Terkirim" pada Menu Aspirasi?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        <strong>Draft</strong>: Aspirasi yang masih disimpan sementara di akun Anda tapi belum diajukan resmi ke Admin. Anda bisa mengedit, menambahkan lampiran, atau menghapusnya kapan saja sebelum dikirim.
                        <br><br>
                        <strong>Terkirim</strong>: Aspirasi yang sudah Anda klik tombol "Kirim" atau "Ajukan". Data sudah masuk ke sistem dan dalam antrian verifikasi Admin. Aspirasi yang sudah "Terkirim" tidak bisa diedit lagi, namun tetap bisa dilampirkan revisi jika ditolak.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">⏳</div>
                    <span class="faq-q-text">Berapa lama batas waktu saya bisa mengedit surat?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Anda dapat mengedit data surat (Judul, Jenis, dan Tujuan) selama <strong>15 menit</strong> terhitung sejak surat pertama kali diajukan. Setelah melewati 15 menit, fitur edit akan terkunci otomatis demi menjaga integritas data selama proses verifikasi oleh Admin.
                    </div>
                </div>
            <div class="faq-item" data-cat="pengajuan">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🔢</div>
                    <span class="faq-q-text">Mengapa catatan pengusul dibatasi hanya 100 karakter?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Pembatasan <strong>100 karakter</strong> pada catatan pengusul bertujuan agar Anda memberikan informasi yang <strong>padat, singkat, dan langsung pada poinnya</strong>. Hal ini membantu Admin mempercepat proses peninjauan tanpa harus membaca penjelasan yang terlalu panjang. Jika ada detail teknis yang panjang, silakan masukkan ke dalam isi dokumen surat atau lampiran.
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION: Status & Proses --}}
        <div class="section-label" data-section="status">
            <div class="section-label-line"></div>
            <span class="section-label-text">Status & Proses</span>
            <div class="section-label-line"></div>
        </div>

        <div class="faq-list">

            <div class="faq-item" data-cat="status">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">⏱️</div>
                    <span class="faq-q-text">Berapa lama waktu proses surat (SLA)?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Waktu proses maksimal adalah <strong>24 jam kerja</strong> (Hanya dihitung hari kerja: Senin–Kamis pukul 07.30–16.00 WIB, Jumat pukul 07.30–16.30 WIB). Jika proses melebihi 24 jam kerja, sistem akan memberikan indikasi <strong>"Terlambat -0.1 jam"</strong> sebagai tanda bahwa surat telah melewati batas waktu SLA yang ditentukan.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="status">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🗺️</div>
                    <span class="faq-q-text">Apa arti masing-masing tahapan status surat (Tahap 1–10)?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Berikut penjelasan alur 10 tahap pemrosesan surat secara berurutan:
                        <ul class="stage-list">
                            <li class="stage-item"><span class="stage-num">1</span> <strong>Ajukan</strong>: Surat telah dikirim oleh user.</li>
                            <li class="stage-item"><span class="stage-num">2</span> <strong>Verifikasi Aspirasi</strong>: Pemeriksaan awal oleh Admin Aspirasi.</li>
                            <li class="stage-item"><span class="stage-num">3</span> <strong>Verifikasi Kasubbag TU</strong>: Validasi oleh Kasubbag Tata Usaha.</li>
                            <li class="stage-item"><span class="stage-num">4</span> <strong>Verifikasi Kepala Balai</strong>: Persetujuan akhir pimpinan.</li>
                            <li class="stage-item"><span class="stage-num">5</span> <strong>Penomoran</strong>: Pemberian nomor resmi oleh Admin Aspirasi.</li>
                            <li class="stage-item"><span class="stage-num">6</span> <strong>Tanda Tangan DS</strong>: Proses tanda tangan Digital Signature.</li>
                            <li class="stage-item"><span class="stage-num">7</span> <strong>Pengiriman TNDe</strong>: Proses melalui sistem TNDe.</li>
                            <li class="stage-item"><span class="stage-num">8</span> <strong>Pengiriman Srikandi</strong>: Proses melalui sistem Srikandi.</li>
                            <li class="stage-item"><span class="stage-num">9</span> <strong>Pengarsipan</strong>: Pendataan ke dalam arsip fisik/digital.</li>
                            <li class="stage-item"><span class="stage-num" style="background:#16a34a">10</span> <span class="tag-success">Selesai</span>: Seluruh proses selesai dilaksanakan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="status">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">✅</div>
                    <span class="faq-q-text">Kapan sistem melakukan pembersihan otomatis pada surat saya?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Untuk menjaga performa server dan keamanan data, sistem melakukan pembersihan otomatis sebagai berikut:
                        <ul>
                            <li><strong>Surat Selesai</strong>: File fisik (word/lampiran) akan dihapus otomatis setelah <strong>3 hari</strong> sejak status berubah menjadi Selesai.</li>
                            <li><strong>Surat Ditolak</strong>: Jika surat ditolak dan tidak dilakukan revisi selama <strong>5 hari</strong>, maka surat akan dihapus permanen oleh sistem.</li>
                        </ul>
                        <em>Catatan: Riwayat tracking tetap tersimpan meski file fisik telah dihapus.</em>
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="status">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📅</div>
                    <span class="faq-q-text">Bagaimana jika saya mengajukan surat di hari Jumat sore atau Weekend?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Jam operasional hari <strong>Jumat adalah 07.30 – 16.30 WIB</strong>. Jika Anda mengajukan lewat dari jam tersebut atau pada hari Sabtu/Minggu, sistem akan menandai layanan sebagai <strong>TUTUP</strong>. Anda baru bisa mengajukan kembali pada hari Senin mulai pukul 07.30 WIB. Hitungan SLA 24 jam juga akan "dibekukan" selama hari libur dan dilanjutkan kembali saat jam kerja aktif.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="status">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🖼️</div>
                    <span class="faq-q-text">Tampilan tracking saat surat ditolak & direvisi?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        <img src="{{ asset('images/tracking.png') }}" alt="Contoh tampilan tracking revisi" class="faq-img">
                        Saat ditolak dan melakukan revisi, tampilan tracking akan terlihat seperti gambar di atas. Saat direvisi, surat otomatis kembali ke tahap Aspirasi. Fokus pada <strong>garis hijau di sebelah centang</strong> (usulan diajukan) — itu berarti revisi berhasil.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="status">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🗑️</div>
                    <span class="faq-q-text">Apakah saya bisa menghapus file fisik secara manual?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Ya. Jika surat sudah mencapai <strong>Tahap 10 (Selesai)</strong>, Anda bisa menekan tombol <strong>"Bersihkan File Fisik"</strong> di halaman detail surat. Ini akan menghapus file word dan lampiran tanpa menghapus riwayat tracking dan data surat lainnya.
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION: Teknis --}}
        <div class="section-label" data-section="teknis">
            <div class="section-label-line"></div>
            <span class="section-label-text">Teknis & Fitur</span>
            <div class="section-label-line"></div>
        </div>

        <div class="faq-list">

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📅</div>
                    <span class="faq-q-text">Kenapa tidak bisa mengajukan surat di hari Sabtu/Minggu?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Layanan administrasi kami hanya beroperasi pada hari kerja: <strong>Senin–Kamis pukul 07.30–16.00 WIB</strong> dan <strong>Jumat pukul 07.30–16.30 WIB</strong>. Sabtu–Minggu libur. Jika Anda mengakses di luar jam tersebut (termasuk hari libur), sistem akan menampilkan indikasi bahwa layanan sedang tidak aktif atau jam kerja berakhir.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🔄</div>
                    <span class="faq-q-text">Kenapa website tiba-tiba memuat ulang (refresh) sendiri?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Sistem kami memiliki fitur <strong>Auto-Refresh</strong> setiap jam <strong>07.30 pagi</strong> (awal jam kerja) dan pada saat jam kerja berakhir, yaitu jam <strong>16.00</strong> (Senin–Kamis) atau jam <strong>16.30</strong> (Jumat). Hal ini dilakukan untuk menyinkronkan status sistem, notifikasi, dan data terbaru agar tetap akurat bagi semua pengguna.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📊</div>
                    <span class="faq-q-text">Dapatkah saya melihat statistik pengajuan surat saya?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Tentu! Anda dapat mengakses menu <strong>Statistik</strong> untuk melihat grafik distribusi jenis surat dan tren bulanan pengajuan Anda. Selain itu, terdapat fitur <strong>Export Statistik</strong> untuk mengunduh laporan aktivitas surat Anda.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🌓</div>
                    <span class="faq-q-text">Bagaimana cara mengubah ke Dark Mode atau Light Mode?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Gunakan tombol <strong>Switch Mode (ikon Bulan/Matahari)</strong> yang terletak di bagian navigasi atas atau sidebar. Sistem akan menyimpan preferensi tampilan Anda secara otomatis.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">📱</div>
                    <span class="faq-q-text">Apa fungsi QR code yang ada di detail surat saya?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Setiap surat keluar memiliki <strong>QR Code Verifikasi</strong> yang unik. Siapapun dapat melakukan pemindaian (scan) QR code tersebut untuk memvalidasi keaslian surat dan melihat status terakhirnya tanpa perlu masuk ke sistem.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">❄️</div>
                    <span class="faq-q-text">Bagaimana perhitungan SLA jika saya mengajukan surat di hari Jumat sore?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Jika Anda mengajukan surat di luar jam kerja (misal: Jumat jam 15.00), maka perhitungan **SLA akan dibekukan (frozen)** selama hari Sabtu dan Minggu. SLA 24 jam Anda akan dilanjutkan kembali pada hari Senin pukul 07.30 pagi. Contoh: Deadline pengajuan Jumat jam 15.00 adalah Senin jam 15.00.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="teknis">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🔗</div>
                    <span class="faq-q-text">Apa itu UUID-Based Verification?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Sistem menggunakan <strong>UUID (Universally Unique Identifier)</strong> untuk setiap surat. Ini adalah kode unik yang sangat panjang dan acak, sehingga tidak mungkin ditebak oleh orang lain. UUID ini memungkinkan verifikasi surat secara publik dengan aman melalui URL khusus.
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION: Akun --}}
        <div class="section-label" data-section="akun">
            <div class="section-label-line"></div>
            <span class="section-label-text">Akun & Keamanan</span>
            <div class="section-label-line"></div>
        </div>

        <div class="faq-list">

            <div class="faq-item" data-cat="akun">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🔑</div>
                    <span class="faq-q-text">Sudah dapat login dengan NIP dan password? Segera lakukan ini!</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Jika Anda didaftarkan secara kolektif oleh admin, segera <strong>ubah password dan email</strong> Anda melalui menu Profil. Password awal biasanya bersifat standar. Gunakan email aktif (disarankan Gmail) untuk kepentingan reset password di masa mendatang.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="akun">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🔒</div>
                    <span class="faq-q-text">Lupa password atau akun tidak bisa login?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Anda bisa menggunakan fitur <strong>Lupa Password</strong> di halaman login. Ikuti langkah-langkah yang tertera untuk reset password Anda. nanti reset password akan dikirim via email sudah terdaftar.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="akun">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🛡️</div>
                    <span class="faq-q-text">Apakah data dan dokumen surat saya aman di sistem ini?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Ya. Sistem dirancang dengan keamanan berlapis — setiap akun hanya dapat mengakses surat miliknya sendiri. Seluruh aksi tercatat dalam riwayat untuk keperluan audit. Dokumen yang Anda unggah hanya dapat diakses oleh pihak yang terlibat dalam alur persetujuan surat tersebut. <strong>Jangan berbagi akun dengan siapapun.</strong>
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="akun">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">👥</div>
                    <span class="faq-q-text">Bagaimana cara beralih akun tanpa harus login ulang?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Anda dapat menggunakan fitur <strong>"Beralih Akun"</strong> pada dropdown profil (klik foto profil Anda). Fitur ini memungkinkan Anda berpindah antar akun yang sudah pernah login di perangkat tersebut secara instan. Keamanan dijamin dengan <em>switch_token</em> yang di-hash di database dan masa berlaku token selama 30 hari.
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION: Tentang Website --}}
        <div class="section-label" data-section="tentang">
            <div class="section-label-line"></div>
            <span class="section-label-text">Tentang Website</span>
            <div class="section-label-line"></div>
        </div>

        <div class="faq-list">

            <div class="faq-item" data-cat="tentang">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🏢</div>
                    <span class="faq-q-text">Apa itu Website Persuratan BP Suml?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        <strong>Website Persuratan BP Suml</strong> adalah sistem berbasis web yang dirancang untuk mengelola seluruh ekosistem persuratan (pengajuan, verifikasi, penomoran, hingga pengarsipan) secara digital. Proyek ini dikembangkan untuk meningkatkan efisiensi, transparansi, dan kecepatan layanan administrasi di Balai Pengelolaan SUML.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="tentang">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">🚀</div>
                    <span class="faq-q-text">Teknologi apa yang digunakan dalam website ini?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Sistem ini dibangun menggunakan teknologi mutakhir:
                        <ul>
                            <li><strong>Backend</strong>: Laravel 12 (PHP Framework) & MySQL Database.</li>
                            <li><strong>Frontend</strong>: Bootstrap & Tailwind CSS untuk tata letak yang responsif.</li>
                            <li><strong>Animasi</strong>: GSAP, Anime.js, dan Three.js untuk pengalaman visual premium.</li>
                            <li><strong>Keamanan</strong>: Cloudflare WAF, Google reCAPTCHA V2, dan Enkripsi Password tingkat tinggi.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="faq-item" data-cat="tentang">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-q-icon">👨‍💻</div>
                    <span class="faq-q-text">Siapa yang mengembangkan sistem ini?</span>
                    <div class="faq-q-arrow"><i class="bi bi-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrap">
                    <div class="faq-answer">
                        Website ini dirancang dan dikembangkan sebagai bagian dari <strong>Proyek PKL (Praktik Kerja Lapangan)</strong> oleh siswa <strong>SMK Alfalah</strong> dengan peran Fullstack Developer. Pengembangan dibantu oleh AI untuk memastikan kode yang bersih, efisien, dan mengikuti standar industri modern.
                    </div>
                </div>
            </div>

        </div>

        {{-- Contact Card --}}
        <div class="faq-contact">
            <div class="faq-contact-inner">
                <h4>Masih ada pertanyaan lain?</h4>
                <p>Jika Anda tidak menemukan jawaban yang dicari, tim IT & Admin kami siap membantu Anda.</p>
                <div class="faq-contact-btns">
                    <div class="dropdown">
                        <button class="btn-wa dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border:none; cursor:pointer;">
                            <i class="bi bi-chat-dots-fill"></i> Chat Admin
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" style="border-radius:12px; font-size:13px;">
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="https://wa.me/{{ config('services.whatsapp.number') }}" target="_blank">
                                    <i class="bi bi-whatsapp text-success"></i> WhatsApp Admin (Web)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="https://t.me/{{ config('services.telegram.admin_username') }}" target="_blank">
                                    <i class="bi bi-telegram text-info"></i> Telegram Admin IT
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="mailto:tubpsuml@gmail.com" class="btn-email">
                        <i class="bi bi-envelope"></i> Email Support
                    </a>
                    <a href="{{ route('user.aspirasi.index') }}" class="btn-email">
                        <i class="bi bi-chat-right-heart"></i> Kotak Aspirasi
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // ── Toggle FAQ ──
    function toggleFaq(btn) {
        const item = btn.closest('.faq-item');
        const answerWrap = item.querySelector('.faq-answer-wrap');
        const isOpen = item.classList.contains('open');

        // close all
        document.querySelectorAll('.faq-item.open').forEach(el => {
            el.classList.remove('open');
            el.querySelector('.faq-answer-wrap').style.maxHeight = '0';
        });

        if (!isOpen) {
            item.classList.add('open');
            answerWrap.style.maxHeight = answerWrap.scrollHeight + 'px';
        }
    }

    // ── Search ──
    const searchInput = document.getElementById('faqSearch');
    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('.faq-item');
        const sections = document.querySelectorAll('[data-section]');
        let anyVisible = false;

        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            const match = !query || text.includes(query);
            item.classList.toggle('hidden-by-search', !match);
            if (match) anyVisible = true;
        });

        // Show/hide section labels
        sections.forEach(sec => {
            const cat = sec.dataset.section;
            const sibling = sec.nextElementSibling;
            if (!sibling) return;
            const visibles = sibling.querySelectorAll('.faq-item:not(.hidden-by-search)');
            sec.style.display = (query && visibles.length === 0) ? 'none' : '';
        });

        document.getElementById('faq-no-results').style.display = anyVisible ? 'none' : 'block';

        // Reset active tab
        if (query) {
            document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
            document.querySelector('.faq-tab[data-cat="all"]').classList.add('active');
        }
    });

    // ── Category Filter ──
    document.querySelectorAll('.faq-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const cat = this.dataset.cat;
            document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            searchInput.value = '';

            const items = document.querySelectorAll('.faq-item');
            const sections = document.querySelectorAll('[data-section]');

            items.forEach(item => {
                const itemCat = item.dataset.cat;
                item.classList.toggle('hidden-by-search', cat !== 'all' && itemCat !== cat);
            });

            sections.forEach(sec => {
                const scat = sec.dataset.section;
                const sibling = sec.nextElementSibling;
                if (!sibling) return;
                const visibles = sibling.querySelectorAll('.faq-item:not(.hidden-by-search)');
                sec.style.display = (cat !== 'all' && visibles.length === 0) ? 'none' : '';
            });

            document.getElementById('faq-no-results').style.display = 'none';
        });
    });
</script>

@endsection