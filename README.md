<div align="center">

# 📬 Sistem Informasi Persuratan Digital
### Balai Perhubungan Sumatera Utara (BP Suml)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16+-336791?style=flat-square&logo=postgresql&logoColor=white)](https://postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

Aplikasi pengelolaan surat-menyurat digital berbasis web untuk Balai Perhubungan Sumatera Utara. Menggantikan alur kerja manual dengan sistem tracking 10 tahap yang terintegrasi, SLA monitoring, tanda tangan digital, dan audit trail lengkap.

</div>

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Arsitektur Multi-Role](#-arsitektur-multi-role)
- [Alur Kerja Surat](#-alur-kerja-surat-10-tahap)
- [Struktur Database](#-struktur-database)
- [Instalasi Lokal](#-instalasi-lokal)
- [Deployment](#-deployment-docker--railway)
- [Environment Variables](#-environment-variables)
- [Keamanan](#-keamanan)
- [Kontribusi](#-kontribusi)

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| **Tracking 10 Tahap** | Surat mengikuti alur kerja dari pengajuan hingga pengarsipan dengan status real-time |
| **SLA Monitoring** | Batas waktu 1 hari kerja otomatis, alarm keterlambatan, dan laporan kepatuhan |
| **Multi-Role RBAC** | 6 role dengan kewenangan terisolasi per tahap — tidak bisa lintas tahap |
| **Tanda Tangan Digital** | Upload tanda tangan + PIN terenkripsi untuk otentikasi dokumen |
| **Audit Trail** | Log seluruh aktivitas dengan JSON diff (before/after) per aksi |
| **Notifikasi Real-time** | Server-Sent Events (SSE) + database notification untuk semua event penting |
| **Aspirasi & Tiket IT** | Modul feedback dan pelaporan teknis terintegrasi |
| **Ekspor Data** | Laporan Excel dan statistik per periode |
| **Statistik & Grafik** | Dashboard analitik dengan 15+ jenis chart (Chart.js) |
| **Draft Surat** | User bisa simpan draf kapan saja, submit hanya saat jam kerja (bypass di `local`) |
| **Switch Account** | Token-based multi-account dengan expiry 30 hari |
| **Docker Ready** | Dockerfile + Nginx config siap deploy |

---

## 🛠 Tech Stack

**Backend**
- [Laravel 12](https://laravel.com) — PHP framework
- [PHP 8.2+](https://php.net) — Runtime
- [PostgreSQL 16+](https://postgresql.org) — Database utama
- [maatwebsite/excel](https://laravel-excel.com) — Ekspor Excel
- [phpoffice/phpword](https://phpword.readthedocs.io) — Konversi dokumen Word

**Frontend**
- [Bootstrap 5](https://getbootstrap.com) — Layout & komponen dasar
- [Tailwind CSS](https://tailwindcss.com) — Utility classes
- [Alpine.js](https://alpinejs.dev) — State management & interaktivitas ringan
- [Hotwire Turbo](https://turbo.hotwired.dev) — SPA-like navigation
- [Chart.js](https://chartjs.org) — Visualisasi data
- [GSAP](https://gsap.com) & [Anime.js](https://animejs.com) — Animasi halaman depan
- Glassmorphism design — tema *Midnight Indigo* (`#1e293b`) + *Professional Blue* (`#4361ee`)

**Keamanan**
- Google reCAPTCHA v2
- CSRF protection
- UUID anti-enumeration di semua public URL
- File akses melalui controller (disk `private`, bukan `public`)
- PIN tanda tangan di-hash dengan bcrypt

---

## 👥 Arsitektur Multi-Role

```
┌─────────────────────────────────────────────────────┐
│                       ROLES                         │
├──────────────────┬──────────────────────────────────┤
│ user             │ Mengajukan & merevisi surat       │
│ admin_aspirasi   │ Tahap 2, 5–10 + re-upload dokumen │
│ admin_kasubbag_tu│ Tahap 3 — Verifikasi Kasubbag TU  │
│ admin_kepala_balai│ Tahap 4 — Verifikasi Kepala Balai│
│ it_support       │ Tiket teknis & broadcast notif    │
│ admin            │ Legacy (tidak aktif)              │
└──────────────────┴──────────────────────────────────┘
```

Setiap role hanya bisa mengakses dan memproses tahap yang menjadi kewenangannya. Middleware `AdminMiddleware` + pengecekan `canApproveTahap()` di model memastikan isolasi ini.

---

## 📄 Alur Kerja Surat (10 Tahap)

```
[User] ──Ajukan──▶ [1. Pengajuan]
                        │
                        ▼
               [2. Verifikasi Arsiparis] ◀── admin_aspirasi
                        │
                        ▼
              [3. Verifikasi Kasubbag TU] ◀── admin_kasubbag_tu
                        │
                        ▼
             [4. Verifikasi Kepala Balai] ◀── admin_kepala_balai
                        │
                        ▼
                [5. Penomoran Surat] ◀──┐
                        │              │
                        ▼              │ admin_aspirasi
              [6. Tanda Tangan Digital] │ (tahap 5–10)
                        │              │
                        ▼              │
              [7. Pengiriman via TNDe] ─┤
                        │              │
                        ▼              │
            [8. Pengiriman via Srikandi]┤
                        │              │
                        ▼              │
                  [9. Pengarsipan] ─────┘
                        │
                        ▼
                  [10. ✅ Selesai]

  Ditolak di tahap mana pun → Revisi ke User atau Revisi ke Admin Aspirasi
```

**Status surat:** `draft` · `proses` · `revisi` · `revisi_admin` · `ditolak` · `selesai`

---

## 🗄 Struktur Database

18 tabel aplikasi + 6 tabel sistem Laravel. Lihat dokumentasi lengkap di [`database/DATABASE_DESIGN.md`](database/DATABASE_DESIGN.md).

**Tabel inti:**

```
users                → Semua pengguna (multi-role)
surats               → Data surat + SLA + status
surat_tahapans       → Riwayat per tahap
surat_delete_requests→ Permintaan hapus butuh approval
aspirasis            → Feedback & aspirasi pengguna
it_support_tickets   → Tiket teknis ke IT Support
activity_logs        → Audit trail (JSON diff)
notifications        → Notifikasi database Laravel
```

---

## 🚀 Instalasi Lokal

### Prasyarat

- PHP 8.2+ dengan ekstensi: `pdo_pgsql`, `pgsql`, `gd`, `zip`, `fileinfo`
- PostgreSQL 14+
- Composer 2.x
- Node.js 18+ & NPM

### Langkah Instalasi

**1. Clone dan install dependencies**

```bash
git clone https://github.com/username/persuratan-bpsuml.git
cd persuratan-bpsuml
composer install
npm install
```

**2. Konfigurasi environment**

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi lokal kamu:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_persuratan_bpsuml
DB_USERNAME=postgres
DB_PASSWORD=your_password

RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
ADMIN_SECRET_CODE=your_admin_code
```

**3. Buat database di PostgreSQL**

```bash
psql -U postgres -c "CREATE DATABASE db_persuratan_bpsuml;"
```

**4. Jalankan migrasi dan seeder**

```bash
php artisan migrate --seed
```

**5. Build assets dan jalankan server**

```bash
npm run build
php artisan serve
```

Akses di `http://127.0.0.1:8000`

> **Catatan lokal:** `APP_ENV=local` secara otomatis menonaktifkan pembatasan jam kerja dan membypass SSL verify untuk reCAPTCHA.

---

## 🐳 Deployment (Docker / Railway)

### Docker

```bash
docker build -t persuratan-bpsuml .
docker run -p 8000:80 --env-file .env persuratan-bpsuml
```

### Railway

1. Fork repo ini ke GitHub
2. Buat project baru di [Railway](https://railway.app)
3. Tambahkan service **PostgreSQL** dari Railway
4. Set environment variables berdasarkan template di [`docker/railway-env-template.txt`](docker/railway-env-template.txt)
5. Deploy otomatis dari branch `main`

---

## ⚙️ Environment Variables

| Variable | Wajib | Deskripsi |
|---|---|---|
| `APP_KEY` | ✅ | Application key (generate via `php artisan key:generate`) |
| `APP_ENV` | ✅ | `local` atau `production` |
| `DB_CONNECTION` | ✅ | `pgsql` |
| `DB_HOST` | ✅ | Host PostgreSQL |
| `DB_DATABASE` | ✅ | Nama database |
| `DB_USERNAME` | ✅ | Username PostgreSQL |
| `DB_PASSWORD` | ✅ | Password PostgreSQL |
| `RECAPTCHA_SITE_KEY` | ✅ | Google reCAPTCHA v2 site key |
| `RECAPTCHA_SECRET_KEY` | ✅ | Google reCAPTCHA v2 secret key |
| `ADMIN_SECRET_CODE` | ✅ | Kode rahasia untuk registrasi akun admin |
| `IT_SUPPORT_CODE` | ✅ | Kode rahasia untuk registrasi akun IT Support |
| `MAIL_*` | ✅ | Konfigurasi SMTP untuk notifikasi email |
| `GEMINI_API_KEY` | ❌ | API key Google Gemini (fitur AI, opsional) |

---

## 🔒 Keamanan

- **UUID di semua public URL** — mencegah ID enumeration attack
- **File disimpan di disk `private`** — tidak dapat diakses langsung via URL, hanya melalui controller dengan autentikasi
- **Tanda tangan digital di-hash** — PIN tidak tersimpan plaintext
- **NIP dienkripsi** — menggunakan Laravel `encrypted` cast
- **Session dienkripsi** — `SESSION_ENCRYPT=true` di production
- **Security Headers Middleware** — `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`
- **Rate limiting** — throttle pada route registrasi dan login
- **CSRF** — aktif di semua form mutation

---

## 📊 Fitur Monitoring

- **SLA Dashboard** — visualisasi tepat waktu vs terlambat per bulan
- **Activity Heatmap** — kontribusi aktivitas ala GitHub
- **15+ Jenis Chart** — surat per bulan, per jenis, per status, bottleneck tahap, rata-rata waktu proses, dan lainnya
- **Audit Log** — setiap aksi tercatat dengan IP, user agent, dan diff data

---

## 🤝 Kontribusi

Project ini dikembangkan dalam rangka PKL di Direktorat Metrologi Bandung, BPSUML

```
1. Fork repo
2. Buat branch fitur: git checkout -b feat/nama-fitur
3. Commit: git commit -m "feat: deskripsi singkat"
4. Push: git push origin feat/nama-fitur
5. Buat Pull Request
```

---

## 📄 Lisensi

Didistribusikan di bawah [MIT License](LICENSE). Lihat `LICENSE` untuk detail lebih lanjut.

<p align="center">Made by | Muhammad Yusuf Akram | PKL From SMK Al-Falah | BPSUML | </p>

---



