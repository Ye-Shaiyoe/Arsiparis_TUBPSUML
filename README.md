<div align="center">

# 📬 Sistem Informasi Persuratan Digital
### BALAI PENGELOLAAN SUML

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16+-336791?style=flat-square&logo=postgresql&logoColor=white)](https://postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-Cache%20%26%20Queue-DC382D?style=flat-square&logo=redis&logoColor=white)](https://redis.io)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

Aplikasi pengelolaan surat-menyurat digital berbasis web untuk Balai Pengelolaan Standar Ukuran Metrologi Legal. Menggantikan alur kerja manual dengan sistem tracking 10 tahap yang terintegrasi, SLA monitoring otomatis, tanda tangan elektronik, audit trail lengkap, dan dashboard analitik real-time.

</div>

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Arsitektur Multi-Role](#-arsitektur-multi-role)
- [Alur Kerja Surat](#-alur-kerja-surat-10-tahap)
- [Halaman & Modul](#-halaman--modul)
- [Struktur Database](#-struktur-database)
- [Keamanan](#-keamanan)
- [Sistem Otomasi](#-sistem-otomasi-cron-jobs)
- [Instalasi Lokal](#-instalasi-lokal)
- [Deployment](#-deployment)
- [Environment Variables](#-environment-variables)
- [Kontribusi](#-kontribusi)

---

## ✨ Fitur Utama

| Kategori | Fitur |
|---|---|
| **Alur Surat** | Tracking 10 tahap dari pengajuan hingga pengarsipan dengan status real-time |
| **SLA Monitoring** | Batas waktu kerja otomatis, skip weekend, alarm tiap 30 menit, laporan kepatuhan |
| **Multi-Role RBAC** | 5 role dengan kewenangan terisolasi per tahap — tidak bisa lintas tahap |
| **Realtime** | Server-Sent Events (SSE) untuk notifikasi push tanpa polling |
| **Tanda Tangan Elektronik** | Gambar di canvas atau upload PNG, PIN bcrypt untuk verifikasi ganda |
| **Audit Trail** | Log semua aktivitas dengan JSON diff (before/after), IP address, user agent |
| **Kalender Agenda** | Kalender bulanan dengan event pengajuan, deadline SLA, selesai, tanggal surat |
| **Direktori Pegawai** | Profil statistik per pegawai: chart, heatmap, SLA rate, sparkline bulanan |
| **Activity Heatmap** | Visualisasi aktivitas 365 hari ala GitHub untuk user dan admin |
| **Switch Account** | Token-based multi-account switching tanpa logout, expiry 30 hari |
| **Draft Surat** | Simpan draf kapan saja, submit hanya di jam operasional |
| **DOCX Preview** | Konversi Word ke HTML natively (tanpa LibreOffice), cache 7 hari di Redis |
| **Aspirasi & IT Tiket** | Modul feedback dua arah dan pelaporan bug/fitur ke IT Support |
| **Ekspor Multi-Format** | Excel (.xlsx) dan CSV di semua modul laporan, statistik, riwayat, activity log |
| **Broadcast Notifikasi** | IT Support kirim notifikasi massal ke semua/admin/user |
| **Delete Request Flow** | Hapus surat yang sedang diproses butuh persetujuan admin |

---

## 🛠 Tech Stack

**Backend**
- [Laravel 12](https://laravel.com) — PHP framework utama
- [PHP 8.2+](https://php.net) — Runtime
- [PostgreSQL 16+](https://postgresql.org) — Database production
- [Redis](https://redis.io) — Cache (konversi DOCX, stats landing page) + Queue
- [Laravel Queue](https://laravel.com/docs/queues) — Async job konversi DOCX
- [Laravel Task Scheduling](https://laravel.com/docs/scheduling) — 5 cron job otomatis
- [maatwebsite/excel](https://laravel-excel.com) — Ekspor Excel & CSV
- PHP `ZipArchive` + `DOMDocument` — Konversi DOCX ke HTML (native, tanpa LibreOffice)

**Frontend**
- [Bootstrap 5](https://getbootstrap.com) — Layout & komponen dasar
- [Tailwind CSS](https://tailwindcss.com) — Utility classes
- [Alpine.js](https://alpinejs.dev) — State management & interaktivitas ringan
- [Hotwire Turbo](https://turbo.hotwired.dev) — SPA-like navigation tanpa reload penuh
- [Chart.js](https://chartjs.org) — Visualisasi data (16+ dataset)
- [GSAP](https://gsap.com) & [Anime.js](https://animejs.com) — Animasi landing page & profil
- Desain Glassmorphism — tema *Midnight Indigo* (`#1e293b`) + *Professional Blue* (`#4361ee`)
- Tipografi: **Sora** (heading) + **Plus Jakarta Sans** (body)

**Infrastruktur & Keamanan**
- [Cloudflare](https://cloudflare.com) — WAF, DDoS protection, CDN, SSL/TLS
- Google reCAPTCHA v2 — proteksi bot di registrasi
- [Nginx](https://nginx.org) — Web server production
- Docker — containerization

---

## 👥 Arsitektur Multi-Role

```
┌──────────────────────┬────────────────────────────────────────────┐
│ Role                 │ Kewenangan                                 │
├──────────────────────┼────────────────────────────────────────────┤
│ user                 │ Ajukan & revisi surat (tahap 1)            │
│ admin_aspirasi       │ Verifikasi, nomor, DS, kirim, arsip (2,5–9)│
│ admin_kasubbag_tu    │ Verifikasi Kasubbag TU (tahap 3)           │
│ admin_kepala_balai   │ Verifikasi Kepala Balai (tahap 4)          │
│ it_support           │ Broadcast notif, balas aspirasi, lihat data│
└──────────────────────┴────────────────────────────────────────────┘
```

- **Admin Role Selection** — admin wajib pilih role aktif sebelum masuk dasbor jika memiliki lebih dari satu tugas
- **Middleware isolasi**: `AdminMiddleware` + `RedirectIfAdminRoleNotSelected` + `EnsureIsITSupport`
- **`canApproveTahap(int $tahap)`** — method di model `User` sebagai single source of truth untuk authorization

---

## 📄 Alur Kerja Surat (10 Tahap)

```
[User] ──Ajukan──▶ [1. Pengajuan]
                        │
                        ▼
             [2. Verifikasi Arsiparis]  ◀── admin_aspirasi
                        │
                        ▼
           [3. Verifikasi Kasubbag TU] ◀── admin_kasubbag_tu
                        │
                        ▼
          [4. Verifikasi Kepala Balai] ◀── admin_kepala_balai
                        │
                        ▼
              [5. Penomoran Surat]  ◀──┐
                        │             │
                        ▼             │
           [6. Tanda Tangan Digital]  │ admin_aspirasi
                        │             │ (tahap 5–9)
                        ▼             │
           [7. Pengiriman via TNDe]  ─┤
                        │             │
                        ▼             │
        [8. Pengiriman via Srikandi] ─┤
                        │             │
                        ▼             │
               [9. Pengarsipan]  ─────┘
                        │
                        ▼
               [10. ✅ Selesai]

  Ditolak → Revisi ke User  (kembali ke tahap 1)
         → Revisi ke Admin Aspirasi  (kembali ke tahap 2, internal)
```

**Status surat:** `draft` · `proses` · `revisi` · `revisi_admin` · `ditolak` · `selesai`

**Edit window:** 15 menit setelah diajukan user masih bisa ubah judul, jenis, sifat, tujuan.

---

## 📱 Halaman & Modul

### Publik (tanpa login)
| URL | Deskripsi |
|---|---|
| `/` | Landing page dengan statistik real-time, chart 12 bulan, SLA per jenis surat |
| `/panduan` | Panduan penggunaan sistem |
| `/v/{uuid}` | Verifikasi keaslian surat (UUID-based, tanpa login) |

### User
| URL | Deskripsi |
|---|---|
| `/dashboard` | Ringkasan surat, SLA aktif, notifikasi, template |
| `/surat` | Daftar surat (card/tabel), filter, export |
| `/surat/ajukan` | Form pengajuan surat baru |
| `/surat/{uuid}` | Detail surat, tracking tahap, preview DOCX, rating |
| `/statistik` | Chart personal + GitHub-style contribution heatmap |
| `/sla` | SLA monitoring dengan progress bar dinamis |
| `/agenda` | Kalender bulanan event surat (pengajuan/deadline/selesai) |
| `/activity-log` | Riwayat semua aksi user, export CSV |
| `/pegawai` | Direktori pegawai + profil statistik per individu |
| `/notifikasi` | Semua notifikasi, tandai baca, hapus |
| `/aspirasi` | Kirim aspirasi/saran/keluhan ke admin atau IT Support |
| `/faq` | FAQ dengan search, filter kategori, accordion |
| `/about` | Info tim dan kontak resmi |

### Admin
| URL | Deskripsi |
|---|---|
| `/Admin/Dashboard` | Dasbor antrian, chart mixed, heatmap admin |
| `/Admin/Surat` | Antrian surat sesuai role, filter lengkap |
| `/Admin/Surat/{uuid}` | Setujui/tolak surat, catatan, rating user |
| `/Admin/Surat-Masuk|Proses|Selesai|Ditolak` | Tabel surat per status |
| `/Admin/Laporan` | Rekap bulanan, export Excel/CSV (13 kolom) |
| `/Admin/Riwayat` | Histori pemrosesan siapa-apa-kapan |
| `/Admin/Chart` | 16 dataset chart statistik real-time |
| `/Admin/Analytics/SLA` | Trend kecepatan respon per divisi (line chart) |
| `/Admin/Aspirasi` | Balas aspirasi user |
| `/Admin/Template` | Upload/kelola template surat |
| `/Admin/Settings/Users` | Manajemen data pegawai |
| `/Admin/Settings/File-Surat` | Manajemen file fisik surat |
| `/Admin/Settings/Logs` | System logs dengan filter lengkap |
| `/Admin/Bantuan-IT-Support` | Laporan bug/fitur ke IT Support |

### IT Support
| URL | Deskripsi |
|---|---|
| `/become-it-support` | Naik role via kode rahasia (`.env`) |
| `/IT-Support/Dashboard` | Dashboard + list surat selesai |
| `/IT-Support/Notification/Create` | Broadcast notifikasi massal |

---

## 🗄 Struktur Database

**Tabel utama:**

```
users                 → Semua pengguna (multi-role, NIP dienkripsi AES-256)
surats                → Data surat + SLA + status + rating
surat_tahapans        → Riwayat per tahap (10 tahap)
surat_delete_requests → Permintaan hapus butuh approval admin
aspirasis             → Feedback & aspirasi pengguna
it_support_tickets    → Tiket teknis ke IT Support
activity_logs         → Audit trail (JSON diff, IP, user agent)
notifications         → Notifikasi database Laravel (semua channel)
```

**Field penting di `surats`:**
- `uuid` — primary key publik, anti-enumeration
- `deadline_sla`, `alasan_keterlambatan` — SLA tracking
- `revisi_count`, `revisi_uploaded_at` — tracking revisi
- `file_expires_at`, `file_dihapus_pada` — lifecycle file fisik
- `rating` — feedback bintang 1–5 dari user
- `perlu_follow_up`, `catatan_follow_up` — flag tindak lanjut

---

## 🔒 Keamanan

| Lapisan | Implementasi |
|---|---|
| **Anti-Enumeration** | UUID di semua public URL (`/v/{uuid}`, `/surat/{uuid}`) |
| **File Private** | Semua file surat di disk `private`, akses via controller + auth |
| **NIP Encryption** | Laravel `encrypted` cast — AES-256-CBC di database |
| **Password** | bcrypt via Laravel Auth |
| **Signature PIN** | bcrypt hash, verifikasi PIN lama sebelum update |
| **Switch Token** | 64-char random, bcrypt stored, plaintext hanya di response JSON |
| **Magic Bytes Validation** | Cek struktur byte file upload untuk cegah RCE |
| **Security Headers** | `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy`, `Permissions-Policy`, HSTS, CSP — via middleware global |
| **Rate Limiting** | Login 5/menit, registrasi, aspirasi, switch account, IT support code |
| **CSRF** | Aktif di semua form mutation |
| **reCAPTCHA v2** | Di form registrasi |
| **Session Revocation** | Logout per perangkat atau semua perangkat lain |
| **Cloudflare WAF** | DDoS protection + Web Application Firewall di production |

---

## ⚙️ Sistem Otomasi (Cron Jobs)

5 artisan commands berjalan otomatis via Laravel Task Scheduling:

| Command | Jadwal | Fungsi |
|---|---|---|
| `files:cleanup-expired` | Setiap hari 01.00 | Hapus file Word & lampiran 3 hari setelah selesai |
| `notifications:cleanup` | Setiap hari 01.00 | Hapus notifikasi > 7 hari |
| `surat:cleanup-rejected` | Setiap hari 01.15 | Hapus surat ditolak tidak direvisi > 5 hari |
| `surat:cleanup-orphaned-references` | Mingguan | Bersihkan referensi DB ke file yang sudah tidak ada |
| `surat:remind-sla` | Tiap 30 menit (Senin–Jumat 07–17) | Kirim notifikasi SLA mendekati (≤6 jam) dan terlewat |

Aktifkan di production:

```bash
# Tambahkan ke crontab server
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚀 Instalasi Lokal

### Prasyarat

- PHP 8.2+ dengan ekstensi: `pdo_pgsql`, `pgsql`, `gd`, `zip`, `fileinfo`
- PostgreSQL 14+
- Composer 2.x
- Node.js 18+ & NPM
- Redis (opsional untuk cache & queue, bisa fallback ke `file`)

### Langkah Instalasi

**1. Clone dan install dependencies**

```bash
git clone https://github.com/Ye-Shaiyoe/Surat-Laravel.git
cd persuratan-bpsuml
composer install
npm install
```

**2. Konfigurasi environment**

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_ENV=local
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_persuratan_bpsuml
DB_USERNAME=postgres
DB_PASSWORD=your_password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
ADMIN_SECRET_CODE=your_admin_code
IT_SUPPORT_CODE=your_it_support_code

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="Persuratan BP Suml"
```

**3. Buat database di PostgreSQL**

```bash
psql -U postgres -c "CREATE DATABASE db_persuratan_bpsuml;"
```

**4. Migrasi dan seeder**

```bash
php artisan migrate --seed
```

**5. Buat symlink storage dan build assets**

```bash
php artisan storage:link
npm run build
```

**6. Jalankan server**

```bash
php artisan serve
```

Akses di `http://127.0.0.1:8000`

> **Catatan lokal:** `APP_ENV=local` otomatis menonaktifkan pembatasan jam kerja dan membypass SSL verify untuk reCAPTCHA.

---

## 🐳 Deployment

### Docker

```bash
docker build -t persuratan-bpsuml .
docker run -p 8000:80 --env-file .env persuratan-bpsuml
```

### Manual (Nginx + PHP-FPM)

Konfigurasi Nginx yang diperlukan untuk SSE agar tidak di-buffer:

```nginx
location /notif/stream {
    proxy_pass http://127.0.0.1:8000;
    proxy_read_timeout 1200s;
    proxy_buffering off;
    proxy_cache off;
    proxy_set_header X-Accel-Buffering no;
}
```

Untuk production dengan banyak concurrent user, naikkan `pm.max_children` di PHP-FPM:

```ini
# /etc/php-fpm.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
```

### Railway

1. Fork repo ini ke GitHub
2. Buat project baru di [Railway](https://railway.app)
3. Tambahkan service **PostgreSQL** dan **Redis** dari Railway
4. Set environment variables
5. Deploy otomatis dari branch `main`

---

## ⚙️ Environment Variables

| Variable | Wajib | Deskripsi |
|---|---|---|
| `APP_KEY` | ✅ | Application key (`php artisan key:generate`) |
| `APP_ENV` | ✅ | `local` atau `production` |
| `APP_URL` | ✅ | URL lengkap aplikasi |
| `DB_CONNECTION` | ✅ | `pgsql` |
| `DB_HOST` | ✅ | Host PostgreSQL |
| `DB_DATABASE` | ✅ | Nama database |
| `DB_USERNAME` | ✅ | Username PostgreSQL |
| `DB_PASSWORD` | ✅ | Password PostgreSQL |
| `RECAPTCHA_SITE_KEY` | ✅ | Google reCAPTCHA v2 site key |
| `RECAPTCHA_SECRET_KEY` | ✅ | Google reCAPTCHA v2 secret key |
| `ADMIN_SECRET_CODE` | ✅ | Kode rahasia registrasi akun admin |
| `IT_SUPPORT_CODE` | ✅ | Kode rahasia naik role IT Support |
| `MAIL_*` | ✅ | Konfigurasi SMTP (reset password, verifikasi email) |
| `CACHE_DRIVER` | ✅ | `redis` (production) atau `file` (lokal) |
| `QUEUE_CONNECTION` | ✅ | `redis` (production) atau `sync` (lokal) |
| `SESSION_ENCRYPT` | ⚠️ | `true` di production |
| `GEMINI_API_KEY` | ❌ | API key Google Gemini (fitur AI, opsional) |

---

## 📊 Fitur Monitoring & Analitik

- **SLA Dashboard User** — progress bar dinamis berubah warna (hijau→kuning→merah)
- **SLA Analytics Admin** — trend kecepatan respon per divisi per bulan
- **Activity Heatmap** — kontribusi aktivitas 365 hari ala GitHub (user & admin)
- **Direktori Pegawai** — statistik per individu: monthly chart, sparkline, SLA rate, jenis surat
- **Kalender Agenda** — event surat pada kalender bulanan, navigasi AJAX
- **16 Dataset Chart** — surat per bulan, per jenis, per status, SLA compliance, dll
- **Audit Log** — setiap aksi tercatat dengan IP, user agent, dan diff data
- **Landing Page Stats** — statistik publik ter-cache 5 menit: masuk, keluar, arsip, rating rata-rata

---

## 🤝 Kontribusi

Project ini dikembangkan dalam rangka PKL di BP Suml — Balai Pengelolaan Standar Ukuran Metrologi Legal.

```bash
# 1. Fork repo
# 2. Buat branch fitur
git checkout -b feat/nama-fitur

# 3. Commit dengan format konvensional
git commit -m "feat: deskripsi singkat"

# 4. Push dan buat Pull Request
git push origin feat/nama-fitur
```

---

## 📄 Lisensi

Didistribusikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Made with ❤️ by <strong>Muhammad Yusuf Akram</strong><br>
  PKL — SMK Al-Falah &nbsp;|&nbsp; BP Suml &nbsp;|&nbsp; 2025–2026
</p>
