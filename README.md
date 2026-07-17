<div align="center">

# 📬 Sistem Informasi Digital
### BALAI PENGELOLAAN SUML

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-14+-336791?style=flat-square&logo=postgresql&logoColor=white)](https://postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-Cache%20%26%20Queue-DC382D?style=flat-square&logo=redis&logoColor=white)](https://redis.io)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

Aplikasi pengelolaan surat-menyurat digital berbasis web untuk Balai Pengelolaan Standar Ukuran Metrologi Legal.  
Menggantikan alur kerja manual dengan sistem tracking 10 tahap terintegrasi, SLA monitoring otomatis, tanda tangan elektronik, audit trail lengkap, dan dashboard analitik real-time.

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
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Instalasi Lokal (XAMPP)](#-instalasi-lokal-xampp)
- [Instalasi Lokal (Artisan Serve)](#-instalasi-lokal-artisan-serve)
- [Konfigurasi Environment](#-konfigurasi-environment)
- [Troubleshooting](#-troubleshooting)
- [Kontribusi](#-kontribusi)
- [📖 Deployment Server →](READMESERVER.md)

---

## ✨ Fitur Utama

| Kategori | Fitur |
|---|---|
| **Alur Surat** | Tracking 10 tahap dari pengajuan hingga pengarsipan dengan status real-time |
| **SLA Monitoring** | Batas waktu kerja otomatis, skip weekend, alarm tiap 30 menit, laporan kepatuhan |
| **Multi-Role RBAC** | 5 role dengan kewenangan terisolasi per tahap |
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
| **reCAPTCHA** | v2 checkbox di registrasi + v3 invisible di login |

---

## 🛠 Tech Stack

**Backend**
- [Laravel 12](https://laravel.com) — PHP framework utama
- [PHP 8.2+](https://php.net) — Runtime
- [PostgreSQL 14+](https://postgresql.org) — Database production
- [Redis](https://redis.io) — Cache + Queue (opsional, fallback ke `file`/`database`)
- [maatwebsite/excel ^3.1](https://laravel-excel.com) — Ekspor Excel & CSV
- [phpoffice/phpword ^1.1](https://phpword.readthedocs.io) — Konversi DOCX ke HTML

**Frontend**
- [Bootstrap 5](https://getbootstrap.com) — Layout & komponen dasar
- [Tailwind CSS 3.x](https://tailwindcss.com) — Utility classes
- [Alpine.js 3.x](https://alpinejs.dev) — State management ringan
- [Vite 7](https://vite.dev) — Build tool & HMR
- [Chart.js](https://chartjs.org) — Visualisasi data (16+ dataset)

**Keamanan**
- Google reCAPTCHA v2 — proteksi bot di halaman registrasi (checkbox)
- Google reCAPTCHA v3 — proteksi bot di halaman login (invisible, auto-detect)
- Cloudflare — WAF, DDoS protection, CDN, SSL/TLS (production)

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
              [5. Penomoran Surat]  ◀-┐
                        │              │
                        ▼              │ admin_aspirasi
           [6. Tanda Tangan Digital]   │ (tahap 5–9)
                        │              │
                        ▼              │
           [7. Pengiriman via TNDe]   ─┤
                        │              │
                        ▼              │
        [8. Pengiriman via Srikandi]  ─┤
                        │              │
                        ▼              │
               [9. Pengarsipan]   ─────┘
                        │
                        ▼
               [10. ✅ Selesai]

  Ditolak → Revisi ke User  (kembali ke tahap 1)
         → Revisi ke Admin Aspirasi  (kembali ke tahap 2)
```

---

## 🔒 Keamanan

| Lapisan | Implementasi |
|---|---|
| **Anti-Enumeration** | UUID di semua public URL |
| **File Private** | Semua file surat di disk `private`, akses via controller + auth |
| **NIP Encryption** | Laravel `encrypted` cast — AES-256-CBC di database |
| **reCAPTCHA v2** | Di form registrasi (checkbox widget) |
| **reCAPTCHA v3** | Di form login (invisible, score-based) |
| **Rate Limiting** | Login 5/menit, registrasi, aspirasi, switch account |
| **Security Headers** | `X-Frame-Options`, HSTS, CSP, `X-Content-Type-Options` via middleware global |
| **CSRF** | Aktif di semua form mutation |
| **Cloudflare WAF** | DDoS protection + Web Application Firewall di production |

---

## ⚙️ Sistem Otomasi (Cron Jobs)

| Command | Jadwal | Fungsi |
|---|---|---|
| `files:cleanup-expired` | Setiap hari 01.00 | Hapus file Word & lampiran 3 hari setelah selesai |
| `notifications:cleanup` | Setiap hari 01.00 | Hapus notifikasi > 7 hari |
| `surat:cleanup-rejected` | Setiap hari 01.15 | Hapus surat ditolak tidak direvisi > 5 hari |
| `surat:cleanup-orphaned-references` | Mingguan | Bersihkan referensi DB ke file yang sudah tidak ada |
| `surat:remind-sla` | Tiap 30 menit (Senin–Jumat 07–17) | Kirim notifikasi SLA mendekati deadline |

```bash
# Tambahkan ke crontab server (production)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

---

## 💻 Prasyarat Sistem

Pastikan semua software berikut sudah terpasang sebelum instalasi:

| Software | Versi Minimum | Cek Versi |
|---|---|---|
| PHP | 8.2+ | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js | 18+ | `node -v` |
| NPM | 9+ | `npm -v` |
| PostgreSQL | 14+ | `psql --version` |
| Git | 2.x | `git --version` |
| Redis *(opsional)* | 6+ | `redis-cli --version` |

**PHP Extensions yang wajib aktif:**

```
pdo_pgsql    pgsql       gd          zip
fileinfo     mbstring    openssl     tokenizer
xml          ctype       bcmath
```

Cek ekstensi aktif:
```bash
php -m | grep -E "pdo_pgsql|pgsql|gd|zip|fileinfo"
```

---

## 🖥 Instalasi Lokal (XAMPP)

Cocok untuk development di Windows menggunakan XAMPP.

### 1. Persiapan XAMPP

1. Download dan install [XAMPP](https://www.apachefriends.org) versi 8.2+
2. Buka **XAMPP Control Panel**, start **Apache** dan **PostgreSQL** (atau gunakan PostgreSQL terpisah)
3. Pastikan PHP 8.2 aktif — edit `C:\xampp\apache\conf\httpd.conf` jika perlu

> **Catatan:** XAMPP default menggunakan MySQL/MariaDB. Aplikasi ini menggunakan **PostgreSQL** — install terpisah dari [postgresql.org](https://www.postgresql.org/download/windows/) jika belum ada.

### 2. Clone Repository

```bash
cd C:\xampp\htdocs
git clone https://github.com/Ye-Shaiyoe/Arsiparis_TUBPSUML.git TUBPSUML
cd persuratan.bpsuml.com
```

### 3. Install Dependencies

```bash
composer install
npm install
```

### 4. Setup Environment

```bash
copy .env.example .env
php artisan key:generate
```

### 5. Buat Database PostgreSQL

Buka **pgAdmin** atau **psql**:

```sql
CREATE DATABASE db_persuratan_bpsuml
    WITH ENCODING 'UTF8'
    LC_COLLATE = 'en_US.UTF-8'
    LC_CTYPE = 'en_US.UTF-8';
```

Atau via psql command line:
```bash
psql -U postgres -c "CREATE DATABASE db_persuratan_bpsuml;"
```

### 6. Konfigurasi `.env` untuk XAMPP

Edit file `.env`:

```dotenv
APP_NAME="Persuratan BP SUML"
APP_ENV=local
APP_KEY=                          # sudah terisi otomatis dari step 4
APP_DEBUG=true   #Jadikan false
APP_URL=

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_persuratan_bpsuml
DB_USERNAME=postgres
DB_PASSWORD=your_postgres_password

SESSION_DRIVER=database
CACHE_STORE=file
QUEUE_CONNECTION=sync             # sync = tidak butuh Redis untuk lokal

# reCAPTCHA v2 — untuk halaman register
RECAPTCHA_V2_SITE_KEY=your_v2_site_key
RECAPTCHA_V2_SECRET_KEY=your_v2_secret_key

# reCAPTCHA v3 — untuk halaman login (invisible)
RECAPTCHA_V3_SITE_KEY=your_v3_site_key
RECAPTCHA_V3_SECRET_KEY=your_v3_secret_key

ADMIN_SECRET_CODE="kode_rahasia_admin"

MAIL_MAILER=log                   # log = tidak kirim email sungguhan saat dev
```

### 7. Migrasi Database & Seed

```bash
php artisan migrate --seed
```

### 8. Storage Link & Build Assets

```bash
php artisan storage:link
npm run build
```

### 9. Konfigurasi Virtual Host XAMPP (opsional)

Agar bisa akses via `http://persuratan.local` tanpa `/public`:

Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/persuratan.bpsuml.com/public"
    ServerName persuratan.local
    <Directory "C:/xampp/htdocs/persuratan.bpsuml.com/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Tambahkan di `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1   persuratan.local
```

Restart Apache, akses di `http://persuratan.local`

---

## 🚀 Instalasi Lokal (Artisan Serve)

Cara tercepat tanpa perlu konfigurasi web server.

### 1. Clone & Install

```bash
git clone https://github.com/Ye-Shaiyoe/Surat-Laravel.git persuratan-bpsuml
cd persuratan-bpsuml

composer install
npm install
```

Atau gunakan script setup yang sudah tersedia:

```bash
composer run setup
```

> Script ini otomatis: `composer install` → copy `.env` → `key:generate` → `migrate` → `npm install` → `npm run build`

### 2. Buat Database PostgreSQL

```bash
psql -U postgres -c "CREATE DATABASE db_persuratan_bpsuml;"
```

### 3. Setup `.env`

```bash
cp .env.example .env   # Linux/Mac
copy .env.example .env  # Windows
php artisan key:generate
```

Edit `.env` — minimal yang wajib diisi:

```dotenv
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_persuratan_bpsuml
DB_USERNAME=postgres
DB_PASSWORD=your_password

RECAPTCHA_V2_SITE_KEY=your_v2_site_key
RECAPTCHA_V2_SECRET_KEY=your_v2_secret_key
RECAPTCHA_V3_SITE_KEY=your_v3_site_key
RECAPTCHA_V3_SECRET_KEY=your_v3_secret_key

ADMIN_SECRET_CODE=kode_rahasia_admin
```

### 4. Migrate, Storage Link, Build

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

### 5. Jalankan Semua Service Sekaligus

```bash
composer run dev
```

Perintah ini menjalankan secara paralel:
- `php artisan serve` — web server di `http://127.0.0.1:8000`
- `php artisan queue:listen` — memproses job queue (konversi DOCX, dll)
- `php artisan pail` — live log viewer
- `npm run dev` — Vite HMR untuk asset development

Atau jalankan manual satu per satu di terminal terpisah:

```bash
# Terminal 1 — Web server
php artisan serve

# Terminal 2 — Queue worker (untuk konversi DOCX)
php artisan queue:listen --tries=1 --timeout=0

# Terminal 3 — Vite dev server (HMR)
npm run build
npm run dev
```

Akses di: **`http://127.0.0.1:8000`**


---

## 🌐 Instalasi di Server Production

Panduan lengkap deployment ke server production (Ubuntu/Nginx/PostgreSQL/Redis) tersedia di dokumen terpisah:

**[📖 Baca READMESERVER.md →](READMESERVER.md)**

Mencakup: install PHP 8.2, PostgreSQL, Redis & Nginx, SSL Certbot, Queue Worker (Supervisor), Cron Scheduler, Firewall, Docker Compose, Prosedur update, Monitoring & Troubleshooting.

---

## ⚙️ Konfigurasi Environment

### Variabel Wajib

| Variable | Deskripsi | Contoh |
|---|---|---|
| `APP_KEY` | Application key (auto-generate) | `base64:xxx...` |
| `APP_ENV` | Environment aktif | `local` / `production` |
| `APP_URL` | URL lengkap aplikasi | `https://persuratan.bpsuml.com` |
| `DB_CONNECTION` | Driver database | `pgsql` |
| `DB_HOST` | Host PostgreSQL | `127.0.0.1` |
| `DB_PORT` | Port PostgreSQL | `5432` |
| `DB_DATABASE` | Nama database | `db_persuratan_bpsuml` |
| `DB_USERNAME` | Username PostgreSQL | `postgres` |
| `DB_PASSWORD` | Password PostgreSQL | `your_password` |
| `ADMIN_SECRET_CODE` | Kode rahasia registrasi admin | `kode_rahasia_kuat` |

### reCAPTCHA (Wajib)

| Variable | Deskripsi | Halaman |
|---|---|---|
| `RECAPTCHA_V2_SITE_KEY` | Site key reCAPTCHA v2 | Register (checkbox) |
| `RECAPTCHA_V2_SECRET_KEY` | Secret key reCAPTCHA v2 | Register (backend) |
| `RECAPTCHA_V3_SITE_KEY` | Site key reCAPTCHA v3 | Login (invisible) |
| `RECAPTCHA_V3_SECRET_KEY` | Secret key reCAPTCHA v3 | Login (backend) |
| `RECAPTCHA_MIN_SCORE` | Skor minimum v3 (0.0–1.0) | Default: `0.5` |

> **Cara mendapatkan key:** [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin)
> - v2: pilih **"I'm not a robot" Checkbox**
> - v3: pilih **Score based (v3)**
> - Daftarkan domain yang sesuai (lokal bisa pakai `localhost` atau `127.0.0.1`)

### Mail / SMTP

| Variable | Deskripsi |
|---|---|
| `MAIL_MAILER` | `smtp` (production) atau `log` (dev) |
| `MAIL_HOST` | SMTP host (`smtp.gmail.com`) |
| `MAIL_PORT` | `465` (SSL) atau `587` (TLS) |
| `MAIL_USERNAME` | Alamat email pengirim |
| `MAIL_PASSWORD` | App password Gmail (bukan password login) |
| `MAIL_ENCRYPTION` | `ssl` atau `tls` |

> Gmail: aktifkan 2FA → buat App Password di [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)

### Cache & Queue

| Variable | Lokal | Production |
|---|---|---|
| `CACHE_STORE` | `file` | `redis` |
| `QUEUE_CONNECTION` | `sync` | `redis` |
| `SESSION_DRIVER` | `database` | `database` |

### Opsional

| Variable | Deskripsi |
|---|---|
| `GEMINI_API_KEY` | Google Gemini API (fitur AI, opsional) |
| `GOOGLE_CLIENT_ID` | OAuth Google login |
| `GOOGLE_CLIENT_SECRET` | OAuth Google login |
| `WA_NUMBER` | Nomor WhatsApp kontak |
| `TELEGRAM_ADMIN_USERNAME` | Username Telegram admin |
| `RECAPTCHA_MIN_SCORE` | Skor minimum reCAPTCHA v3 (default: `0.5`) |

---

## 🔧 Troubleshooting

### `php artisan migrate` gagal — "could not connect to server"

```bash
# Pastikan PostgreSQL berjalan
sudo systemctl status postgresql

# Atau di Windows/XAMPP — cek service PostgreSQL di task manager
# Verifikasi koneksi
psql -h 127.0.0.1 -U postgres -d db_persuratan_bpsuml
```

Cek juga `.env` — pastikan `DB_PORT=5432` dan `DB_CONNECTION=pgsql`.

---

### Error `Class "PDO" not found` atau `pdo_pgsql`

```bash
# Linux
sudo apt install php8.2-pgsql
sudo systemctl restart php8.2-fpm

# Windows XAMPP — edit php.ini, uncomment baris:
# extension=pdo_pgsql
# extension=pgsql
# Lalu restart Apache
```

---

### `storage:link` gagal di Windows

```bash
# Jalankan Command Prompt sebagai Administrator
php artisan storage:link
```

---

### reCAPTCHA selalu gagal di lokal

Karena `APP_ENV=local`, validasi reCAPTCHA v3 otomatis di-skip di `LoginRequest.php`. Tapi untuk v2 di register, Google butuh domain terdaftar. Solusi:

1. Di [console reCAPTCHA](https://www.google.com/recaptcha/admin), tambahkan `localhost` dan `127.0.0.1` ke daftar domain
2. Atau sementara gunakan key "test" dari Google (selalu lolos):
   - v2 test site key: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
   - v2 test secret: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

---

### `npm run build` error — Vite tidak ditemukan

```bash
# Hapus node_modules dan install ulang
rm -rf node_modules package-lock.json
npm install
npm run build
```

---

### Queue job tidak berjalan (konversi DOCX lambat/tidak jalan)

```bash
# Pastikan queue worker aktif
php artisan queue:listen --tries=1

# Atau di production, restart supervisor
sudo supervisorctl restart persuratan-worker:*

# Cek failed jobs
php artisan queue:failed
php artisan queue:retry all
```

---

### `php artisan config:cache` error setelah update `.env`

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

### Permission error di storage (Linux/production)

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan storage:link
```

---

## 📱 Halaman & Modul

### Publik (tanpa login)

| URL | Deskripsi |
|---|---|
| `/` | Landing page dengan statistik real-time |
| `/panduan` | Panduan penggunaan sistem |
| `/v/{uuid}` | Verifikasi keaslian surat (UUID-based) |

### User

| URL | Deskripsi |
|---|---|
| `/dashboard` | Ringkasan surat, SLA aktif, notifikasi |
| `/surat` | Daftar surat dengan filter dan export |
| `/surat/ajukan` | Form pengajuan surat baru |
| `/statistik` | Chart personal + heatmap aktivitas |
| `/agenda` | Kalender bulanan event surat |
| `/sla` | SLA monitoring real-time |

### Admin

| URL | Deskripsi |
|---|---|
| `/Admin/Dashboard` | Dasbor antrian + chart analitik |
| `/Admin/Surat` | Antrian surat sesuai role |
| `/Admin/Laporan` | Rekap bulanan, export Excel/CSV |
| `/Admin/Analytics/SLA` | Trend kecepatan respon per divisi |

---

## 🤝 Kontribusi

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
