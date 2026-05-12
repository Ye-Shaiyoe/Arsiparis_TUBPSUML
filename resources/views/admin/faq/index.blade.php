@extends('layouts.admin')
@section('title', 'FAQ & Panduan')

@section('content')
<style>
    .faq-container {
        max-width: 900px;
        margin: 0 auto;
        color: var(--text-primary);
    }

    .faq-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .faq-header h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .faq-header p {
        font-size: 14px;
        color: var(--text-secondary);
    }

    /* Category Section Header */
    .faq-category-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-secondary);
        margin: 28px 0 10px 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .faq-category-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }

    .faq-item {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .faq-question {
        padding: 16px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        font-size: 15px;
        background: var(--bg-secondary);
        transition: background 0.3s;
        gap: 12px;
    }

    .faq-question:hover {
        background: var(--bg-tertiary);
    }

    .faq-question .icon {
        transition: transform 0.3s ease;
        color: var(--text-secondary);
        flex-shrink: 0;
    }

    .faq-item.active .faq-question .icon {
        transform: rotate(180deg);
        color: #3b82f6;
    }

    .faq-item.active .faq-question {
        color: #3b82f6;
    }

    html.dark-mode .faq-item.active .faq-question {
        color: #60a5fa;
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease, padding 0.3s ease;
        background: var(--bg-secondary);
        color: var(--text-secondary);
        font-size: 14px;
        line-height: 1.7;
    }

    .faq-item.active .faq-answer {
        padding: 0 20px 20px 20px;
        max-height: 2000px;
    }

    .faq-answer ul, .faq-answer ol {
        padding-left: 20px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .faq-answer li {
        margin-bottom: 6px;
    }

    .faq-answer p {
        margin-bottom: 8px;
    }

    .faq-answer p:last-child {
        margin-bottom: 0;
    }

    .badge-info-inline {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
        font-weight: 500;
    }

    .badge-warning-inline {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        background: rgba(217, 119, 6, 0.1);
        color: #d97706;
        border: 1px solid rgba(217, 119, 6, 0.2);
        font-weight: 500;
    }

    .badge-success-inline {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
        font-weight: 500;
    }

    .badge-danger-inline {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        font-weight: 500;
    }

    .badge-purple-inline {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
        border: 1px solid rgba(139, 92, 246, 0.2);
        font-weight: 500;
    }

    .role-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 13px;
    }

    .role-table th {
        background: var(--bg-tertiary);
        padding: 8px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .role-table td {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        vertical-align: top;
    }

    .role-table tr:nth-child(even) td {
        background: var(--bg-tertiary);
    }

    .info-box {
        background: rgba(59, 130, 246, 0.06);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 8px;
        padding: 12px 14px;
        margin-top: 10px;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .info-box i {
        color: #3b82f6;
        margin-right: 6px;
    }

    .warning-box {
        background: rgba(217, 119, 6, 0.06);
        border: 1px solid rgba(217, 119, 6, 0.2);
        border-radius: 8px;
        padding: 12px 14px;
        margin-top: 10px;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .warning-box i {
        color: #d97706;
        margin-right: 6px;
    }

    /* Search bar */
    .faq-search-wrap {
        position: relative;
        margin-bottom: 24px;
    }

    .faq-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 15px;
    }

    #faqSearch {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    #faqSearch:focus {
        border-color: rgba(59, 130, 246, 0.5);
    }

    #faqSearch::placeholder {
        color: var(--text-secondary);
    }

    .faq-no-result {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
        font-size: 14px;
        display: none;
    }
</style>

<div class="faq-container">
    <div class="faq-header">
        <h1>Panduan & FAQ Admin</h1>
        <p>Pertanyaan umum seputar fitur dan alur pemrosesan Surat Metrologi</p>
    </div>

    {{-- Search --}}
    <div class="faq-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="faqSearch" placeholder="Cari pertanyaan atau kata kunci...">
    </div>

    <div id="faqList">

        {{-- ===================== KATEGORI: ALUR & TAHAPAN ===================== --}}
        <div class="faq-category-title"><i class="bi bi-arrow-right-circle-fill" style="color:#3b82f6"></i> Alur & Tahapan Surat</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana 10 Tahapan Pemrosesan Surat?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Setiap surat yang diajukan oleh user akan melewati maksimal 10 tahap pemrosesan secara berurutan:</p>
                <ol>
                    <li><strong>Diajukan</strong> – User mengunggah dokumen & mengisi form</li>
                    <li><strong>Verifikasi Admin Aspirasi</strong> – <span class="badge-info-inline">admin_aspirasi</span></li>
                    <li><strong>Verifikasi Kasubbag TU</strong> – <span class="badge-info-inline">admin_tu</span></li>
                    <li><strong>Verifikasi Kepala Balai</strong> – <span class="badge-info-inline">admin_kabag</span></li>
                    <li><strong>Penomoran oleh Aspirasi</strong> – <span class="badge-info-inline">admin_aspirasi</span></li>
                    <li><strong>Tanda Tangan DS (Digital Signature)</strong> – <span class="badge-info-inline">admin_aspirasi</span></li>
                    <li><strong>Pengiriman Via TNDe</strong> – <span class="badge-info-inline">admin_aspirasi</span></li>
                    <li><strong>Pengiriman Via Srikandi</strong> – <span class="badge-info-inline">admin_aspirasi</span></li>
                    <li><strong>Pengarsipan</strong> – <span class="badge-info-inline">admin_aspirasi</span></li>
                    <li><strong>Selesai</strong></li>
                </ol>
                <p>Admin hanya dapat memproses tahap yang sesuai dengan rolenya (<em>role-based access</em>). Tahap 0 (Draft) dan Tahap 1 (Ajukan) tidak dapat diproses oleh admin.</p>
                <div class="info-box"><i class="bi bi-info-circle-fill"></i>Surat yang berstatus <strong>Draft</strong> artinya belum diajukan oleh user dan tidak akan muncul di antrian admin.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa yang terjadi dengan surat setelah Selesai atau Ditolak?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Sistem memiliki mekanisme <strong>auto-delete otomatis</strong> untuk menjaga kebersihan data:</p>
                <ul>
                    <li>Surat berstatus <span class="badge-success-inline">Selesai</span>: file fisik (Word & lampiran) akan dihapus otomatis setelah <strong>3 hari</strong>. Tracking dan riwayat tetap tersimpan.</li>
                    <li>Surat berstatus <span class="badge-danger-inline">Ditolak</span> yang tidak direvisi oleh user dalam <strong>5 hari</strong> akan dihapus otomatis dari sistem.</li>
                </ul>
                <p>Jika diperlukan, user juga dapat menghapus file fisik secara manual dari halaman detail suratnya setelah surat selesai, tanpa menghapus riwayat tracking.</p>
            </div>
        </div>

        {{-- ===================== KATEGORI: ROLE & KEWENANGAN ===================== --}}
        <div class="faq-category-title"><i class="bi bi-person-badge-fill" style="color:#8b5cf6"></i> Role & Kewenangan Admin</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa saja perbedaan Role Admin dan kewenangannya?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Sistem menggunakan <strong>Role-Based Access Control (RBAC)</strong>. Setiap admin hanya dapat memproses tahap yang sesuai dengan rolenya dan <strong>tidak dapat mengubah rolenya sendiri</strong>.</p>
                <table class="role-table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Kewenangan Tahap</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge-info-inline">admin_aspirasi</span></td>
                            <td>Verifikasi Aspirasi (2), Penomoran (5), Tanda Tangan DS (6), Pengiriman TNDe (7), Pengiriman Srikandi (8), Pengarsipan (9) — dan dapat <strong>upload ulang file</strong> di Tahap 2</td>
                        </tr>
                        <tr>
                            <td><span class="badge-purple-inline">admin_tu</span></td>
                            <td>Verifikasi Kasubbag TU (3)</td>
                        </tr>
                        <tr>
                            <td><span class="badge-warning-inline">admin_kabag</span></td>
                            <td>Verifikasi Kepala Balai (4)</td>
                        </tr>
                    </tbody>
                </table>
                <div class="warning-box"><i class="bi bi-exclamation-triangle-fill"></i>Admin tidak dapat memproses tahap yang bukan kewenangannya. Tombol aksi hanya aktif pada tahap yang relevan dengan role Anda.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apakah Admin Aspirasi bisa mengubah file dokumen user?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Ya. Khusus di <strong>Tahap 2 (Verifikasi Aspirasi)</strong>, <span class="badge-info-inline">admin_aspirasi</span> memiliki kemampuan untuk <strong>mengupload ulang file Word dan lampiran</strong> milik user jika diperlukan perbaikan minor tanpa harus mengembalikan ke user.</p>
                <p>Semua perubahan file ini akan tercatat di <strong>riwayat revisi</strong> dan <strong>timeline/comment</strong> pada halaman detail surat.</p>
            </div>
        </div>

        {{-- ===================== KATEGORI: MANAJEMEN SURAT ===================== --}}
        <div class="faq-category-title"><i class="bi bi-envelope-fill" style="color:#10b981"></i> Manajemen & Pencarian Surat</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana cara mengelola dan mencari data surat?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Terdapat fitur <strong>Tabel Data Surat</strong> lengkap yang dapat diakses dari menu <strong>Admin &gt; Surat</strong>. Fitur ini meliputi:</p>
                <ul>
                    <li><strong>Filter Status:</strong> Saring berdasarkan <em>Surat Masuk, Diproses, Selesai,</em> atau <em>Perlu Revisi</em>.</li>
                    <li><strong>Pencarian:</strong> Cari cepat berdasarkan nomor surat, judul, nama pengusul, NIP, dan lainnya.</li>
                    <li><strong>Sorting:</strong> Urutkan berdasarkan tanggal masuk untuk memprioritaskan dokumen yang paling lama menunggu.</li>
                    <li><strong>Filter Jenis & Sifat Surat:</strong> Saring berdasarkan jenis (Nota Dinas, Surat Dinas, SK, dll) dan sifat (Biasa, Segera, Rahasia).</li>
                </ul>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa saja yang bisa dilakukan di halaman Detail Surat (/Admin/Surat/{id})?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Halaman detail surat adalah pusat pemrosesan utama. Di sini admin dapat:</p>
                <ul>
                    <li><strong>Preview dokumen</strong> Word dan lampiran langsung di browser</li>
                    <li><strong>Menyetujui atau menolak</strong> surat beserta catatan/komentar di tiap proses</li>
                    <li>Melihat <strong>timeline & riwayat komentar</strong> — semua tindakan admin tercatat lengkap</li>
                    <li>Melihat <strong>riwayat revisi</strong> dan <strong>riwayat hapus file</strong></li>
                    <li>Melihat <strong>QR Code Verifikasi</strong> untuk autentikasi keaslian surat keluar</li>
                    <li><strong>Export surat ke PDF</strong> untuk keperluan cetak atau arsip fisik</li>
                    <li>Melihat <strong>riwayat penomoran</strong> surat</li>
                </ul>
            </div>
        </div>

        {{-- ===================== KATEGORI: REVISI & PENOLAKAN ===================== --}}
        <div class="faq-category-title"><i class="bi bi-arrow-counterclockwise" style="color:#d97706"></i> Revisi & Penolakan</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa perbedaan "Revisi User" dan "Revisi Admin"?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Jika dokumen tidak memenuhi standar, admin memiliki <strong>dua opsi penolakan</strong>:</p>
                <ul>
                    <li><strong>Kembalikan ke User (Revisi Normal):</strong> Surat berstatus <span class="badge-warning-inline">Revisi User</span>. User wajib mengunggah ulang file perbaikan dan tahap di-reset ke Tahap 2 (Aspirasi).</li>
                    <li><strong>Kembalikan ke Admin Aspirasi (Revisi Admin):</strong> Surat berstatus <span class="badge-warning-inline">Admin Revisi</span>. Perbaikan dilakukan oleh tim internal tanpa membebankan ke user. User menerima notifikasi bahwa dokumennya sedang ditinjau ulang oleh Admin Aspirasi.</li>
                </ul>
                <p>User yang dokumennya ditolak total dapat memilih <strong>Mengajukan Ulang</strong> atau <strong>Menghapus</strong> suratnya secara permanen.</p>
            </div>
        </div>

        {{-- ===================== KATEGORI: NOTIFIKASI & SLA ===================== --}}
        <div class="faq-category-title"><i class="bi bi-bell-fill" style="color:#ef4444"></i> Notifikasi & SLA</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana sistem Notifikasi bekerja?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Sistem dilengkapi <strong>Real-time Notifikasi</strong> (icon lonceng merah di navbar). Notifikasi admin mencakup:</p>
                <ul>
                    <li>Surat baru masuk di tahap yang menjadi tanggung jawab role Anda</li>
                    <li>Surat masuk, keluar, direvisi, ditolak, dihapus, diproses, dan selesai</li>
                </ul>
                <p>Notifikasi user mencakup: perpindahan tahap, penyelesaian, penolakan, dan permintaan revisi — termasuk <em>nama/role admin</em> yang memproses.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana perhitungan waktu pelayanan (SLA)?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Sistem mengawasi standar layanan melalui fitur <strong>SLA (Service Level Agreement)</strong>:</p>
                <ul>
                    <li>Batas maksimal proses SLA normal adalah <strong>24 jam kerja</strong> per tahap.</li>
                    <li>Jika dokumen tidak disetujui lebih dari 24 jam di satu tahap, sistem akan mengindikasikan keterlambatan (<span class="badge-warning-inline">-0.1 jam</span>, dll).</li>
                    <li>Kepatuhan SLA dicatat dan ditampilkan dalam <strong>laporan grafik (Chart)</strong>.</li>
                </ul>
                <div class="info-box"><i class="bi bi-info-circle-fill"></i>Perhitungan SLA hanya berlaku pada <strong>jam operasional kerja</strong>: Senin–Kamis 07:30–16:00, Jumat 07:30–16:30. Upload di luar jam kerja akan ditandai sebagai <em>di luar jam operasional</em>.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa yang terjadi jika user mengajukan surat di luar jam operasional?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Jam operasional resmi sistem adalah:</p>
                <ul>
                    <li><strong>Senin – Kamis:</strong> 07:30 – 16:00 WIB</li>
                    <li><strong>Jumat:</strong> 07:30 – 16:30 WIB</li>
                    <li><strong>Sabtu & Minggu:</strong> Libur</li>
                </ul>
                <p>Surat yang diajukan di luar jam kerja akan mendapat <strong>indikator "di luar jam operasional"</strong>. Surat tetap masuk ke sistem namun akan diproses pada hari kerja berikutnya, dan hitungan SLA dimulai dari jam operasional selanjutnya.</p>
            </div>
        </div>

        {{-- ===================== KATEGORI: LAPORAN & STATISTIK ===================== --}}
        <div class="faq-category-title"><i class="bi bi-bar-chart-fill" style="color:#3b82f6"></i> Laporan, Statistik & Export</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana cara melihat laporan dan mengexport data surat?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Fitur laporan tersedia di menu <strong>Admin &gt; Laporan</strong>. Admin dapat:</p>
                <ul>
                    <li>Melihat <strong>rekap per jenis surat</strong> (Nota Dinas, Surat Dinas, SK, dll)</li>
                    <li>Melihat rekapan berdasarkan <strong>bulan dan tahun</strong></li>
                    <li><strong>Export data surat</strong> ke format yang tersedia untuk keperluan pelaporan</li>
                    <li>Melihat tabel monitoring surat berdasarkan periode waktu</li>
                </ul>
                <p>Untuk visualisasi, buka menu <strong>Admin &gt; Chart</strong> yang menampilkan statistik grafis: rekap per bulan, per tahun, per jenis surat, dan per sifat surat.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa itu fitur Riwayat (/Admin/Riwayat) dan apa kegunaannya?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Menu <strong>Riwayat</strong> menampilkan seluruh riwayat surat yang pernah diajukan. Fitur ini penting untuk:</p>
                <ul>
                    <li>Melihat surat mana yang sudah atau belum diproses</li>
                    <li><strong>Penilaian kinerja</strong> dan audit internal layanan</li>
                    <li>Melihat siapa saja admin yang mengelola surat tertentu (<em>Admin Pengelolaan</em>)</li>
                    <li>Filter berdasarkan status, periode, jenis, maupun admin pemroses</li>
                </ul>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa itu Activity Heatmap (Kotak Hijau) di Dashboard?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p><strong>Activity Heatmap</strong> adalah visualisasi produktivitas harian Anda dalam memproses surat. Semakin gelap warna hijau pada kotak, semakin banyak aktivitas (verifikasi/penomoran/dll) yang Anda lakukan pada hari tersebut.</p>
                <ul>
                    <li><strong>Warna Terang</strong>: Aktivitas rendah (1-2 tindakan).</li>
                    <li><strong>Warna Gelap</strong>: Aktivitas tinggi (10+ tindakan).</li>
                    <li><strong>Archive Tahunan</strong>: Anda dapat melihat histori aktivitas tahun-tahun sebelumnya dengan mengklik angka tahun (misal: 2026) di bagian header heatmap.</li>
                </ul>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana jika saya terlambat memproses surat melebihi SLA?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Jika surat melewati batas <strong>24 jam kerja</strong> di tahap Anda tanpa diproses, indikator SLA akan berwarna merah. Saat Anda melakukan verifikasi pada surat yang sudah terlambat, sistem akan meminta Anda mengisi <strong>"Alasan Keterlambatan"</strong>.</p>
                <div class="info-box"><i class="bi bi-info-circle-fill"></i>Alasan ini akan tersimpan dalam riwayat proses dan dapat dilihat oleh user sebagai bentuk transparansi layanan.</div>
            </div>
        </div>

        {{-- ===================== KATEGORI: FITUR LAINNYA ===================== --}}
        <div class="faq-category-title"><i class="bi bi-grid-fill" style="color:#6b7280"></i> Fitur Lainnya</div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana cara menambah atau mengelola Template Surat?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Admin dapat mengelola <strong>Template Surat</strong> resmi melalui menu <strong>Admin &gt; Template</strong>:</p>
                <ul>
                    <li>Unggah file template (Word/PDF) dan beri penamaan (misal: <em>"Template Nota Dinas 2026"</em>)</li>
                    <li>Template yang diunggah otomatis tampil di <strong>Dashboard User</strong> untuk diunduh sebelum mereka membuat permohonan</li>
                    <li>Template lama dapat dihapus atau diganti kapan saja oleh admin</li>
                </ul>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa kegunaan QR Code di surat keluar?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Setiap surat keluar dilengkapi <strong>QR Code Verifikasi</strong> yang dapat dipindai untuk mengonfirmasi keaslian dokumen.</p>
                <ul>
                    <li>QR Code dapat dilihat di halaman detail surat oleh admin maupun user</li>
                    <li>Berguna untuk keperluan <strong>audit, verifikasi fisik</strong>, maupun pemeriksaan eksternal</li>
                    <li>QR Code unik per surat dan terhubung ke data tracking sistem</li>
                </ul>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana melihat data pengguna di /Admin/Users?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Menu <strong>Admin &gt; Users</strong> menampilkan data seluruh user dan admin. Di sini admin dapat melihat:</p>
                <ul>
                    <li>Data profil user (username, email, NIP)</li>
                    <li>Statistik per user: jumlah surat diajukan, selesai, ditolak, dan sedang diproses</li>
                    <li>Role masing-masing akun admin (<span class="badge-info-inline">admin_aspirasi</span>, <span class="badge-purple-inline">admin_tu</span>, <span class="badge-warning-inline">admin_kabag</span>)</li>
                </ul>
                <div class="warning-box"><i class="bi bi-exclamation-triangle-fill"></i>Admin <strong>tidak dapat mengubah role</strong> sendiri maupun role admin lain melalui antarmuka ini.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa itu Logs Aplikasi (/Admin/Logs) dan siapa yang bisa mengaksesnya?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Menu <strong>Admin &gt; Logs</strong> menampilkan catatan aktivitas sistem secara menyeluruh, meliputi:</p>
                <ul>
                    <li><strong>Logs Aplikasi:</strong> Error, peringatan sistem, dan event teknis</li>
                    <li><strong>Logs User:</strong> Aktivitas login, logout, upload, dan perubahan data profil</li>
                    <li>Audit trail lengkap untuk keperluan keamanan dan penelusuran masalah</li>
                </ul>
                <div class="info-box"><i class="bi bi-info-circle-fill"></i>Logs berguna untuk investigasi jika ada aktivitas mencurigakan atau error yang perlu ditindaklanjuti oleh tim teknis.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Mengapa website otomatis refresh setiap jam 8 pagi dan jam 4 sore?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Sistem dikonfigurasi untuk melakukan <strong>auto-refresh otomatis</strong> pada pukul <strong>08:00</strong> (awal jam kerja) dan <strong>16:00</strong> (akhir jam kerja). Tujuannya:</p>
                <ul>
                    <li>Memastikan data antrian surat selalu <strong>sinkron dan up-to-date</strong> di awal dan akhir jam operasional</li>
                    <li>Mereset sesi browser yang mungkin sudah terlalu lama terbuka</li>
                    <li>Memastikan indikator jam operasional dan SLA berjalan akurat</li>
                </ul>
                <p>Jika Anda sedang mengisi form saat refresh terjadi, simpan pekerjaan Anda terlebih dahulu.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana cara mengelola Aspirasi dari user?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Menu <strong>Admin &gt; Aspirasi</strong> berfungsi untuk menerima dan mengelola masukan dari user. Cara mengelola:</p>
                <ul>
                    <li>Buka menu <strong>Aspirasi</strong> untuk melihat semua aspirasi yang masuk.</li>
                    <li>Pilih kategori: <strong>Saran</strong>, <strong>Keluhan</strong>, atau <strong>Pertanyaan</strong>.</li>
                    <li>Klik <strong>"Balas"</strong> untuk memberikan respons terhadap aspirasi user.</li>
                    <li>Klik <strong>"Hapus"</strong> untuk menghapus aspirasi yang sudah ditindaklanjuti.</li>
                </ul>
                <div class="info-box"><i class="bi bi-info-circle-fill"></i>Aspirasi yang belum dibalas ditandai dengan status <em>Belum Dibalas</em>. Prioritaskan untuk merespons keluhan user.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Apa itu menu File-Surat dan apa fungsinya?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Menu <strong>Admin &gt; Settings &gt; File-Surat</strong> berfungsi untuk mengelola file fisik surat di server:</p>
                <ul>
                    <li><strong>Hapus File Fisik:</strong> Menghapus file Word/lampiran surat dari server tanpa menghapus data tracking di database.</li>
                    <li><strong>Cetak Ulang:</strong> Jika file sudah dihapus, user tidak bisa preview atau download, tapi tracking tetap ada di sistem.</li>
                    <li>Menu ini berguna untuk <strong>mengurangi penggunaan storage server</strong> pada surat-surat yang sudah lama.</li>
                </ul>
                <div class="warning-box"><i class="bi bi-exclamation-triangle-fill"></i>Surat yang sedang dalam tahap proses (belum selesai) <strong>tidak bisa</strong> dihapus file fisiknya untuk menjaga integritas dokumen.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Berapa lama DashboardAdmin diperbarui secara otomatis?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Dashboard Admin memiliki fitur <strong>realtime update</strong> dengan interval:</p>
                <ul>
                    <li>Data dashboard akan <strong>diperbarui otomatis setiap 30 detik</strong>.</li>
                    <li>Data antrian, statistik, dan notifikasi akan selalu terbaru.</li>
                    <li>Indikator <span class="badge-success-inline">LIVE</span> di header menandakan dashboard sedang aktif.</li>
                </ul>
                <div class="info-box"><i class="bi bi-info-circle-fill"></i>Fitur polling ini menggunakan AJAX agar tidak perlu reload halaman penuh, lebih hemat resources.</div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Kapan notifikasi lama dihapus otomatis?</span>
                <span class="icon"><i class="bi bi-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Sistem memiliki penjadwalan <strong>auto-cleanup</strong> untuk notifikasi:</p>
                <ul>
                    <li>Notifikasi yang berumur lebih dari <strong>1 minggu</strong> akan dihapus otomatis.</li>
                    <li>Proses pembersihan berjalan setiap <strong>Senin pukul 01.00</strong> ( dini hari ).</li>
                    <li>Hanya notifikasi yang sudah dibaca yang dihapus, notifikasi belum dibaca tetap aman.</li>
                </ul>
                <p>Ini berfungsi untuk menjaga performa database agar tidak penuh dengan notifikasi lama.</p>
            </div>
        </div>

    </div>{{-- end #faqList --}}

    <div class="faq-no-result" id="noResult">
        <i class="bi bi-search" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.4;"></i>
        Tidak ditemukan pertanyaan yang cocok dengan "<span id="searchKeyword"></span>"
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Accordion toggle
        document.querySelectorAll('.faq-item').forEach(item => {
            item.querySelector('.faq-question').addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });

        // Search/filter
        const input = document.getElementById('faqSearch');
        const noResult = document.getElementById('noResult');
        const keyword = document.getElementById('searchKeyword');

        input.addEventListener('input', function () {
            const val = this.value.trim().toLowerCase();
            let found = 0;

            document.querySelectorAll('.faq-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                const show = !val || text.includes(val);
                item.style.display = show ? '' : 'none';
                if (show) found++;
            });

            // Hide/show category titles based on visible items
            document.querySelectorAll('.faq-category-title').forEach(cat => {
                let next = cat.nextElementSibling;
                let hasVisible = false;
                while (next && !next.classList.contains('faq-category-title')) {
                    if (next.classList.contains('faq-item') && next.style.display !== 'none') {
                        hasVisible = true;
                    }
                    next = next.nextElementSibling;
                }
                cat.style.display = hasVisible ? '' : 'none';
            });

            noResult.style.display = found === 0 && val ? 'block' : 'none';
            keyword.textContent = val;
        });
    });
</script>
@endsection