# 📬 Persuratan BP Suml

<p align="center">
  <b>Sistem manajemen surat digital berbasis web untuk Balai Pengujian (BP Suml)</b><br/>
  Dikembangkan sebagai proyek PKL oleh siswa SMK Al-Falah

  Demo video

    https://drive.google.com/drive/folders/1UYCrUPZQjaQawTXM33jwFN9uRqLGoQh_?usp=sharing
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Redis-Cache-DC382D?style=flat&logo=redis&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white" />
  <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat&logo=alpine.js&logoColor=black" />
</p>


---

## 📑 Daftar Isi

- [Demo & Akses](#-demo--akses)
- [Tech Stack](#️-tech-stack)
- [Role Pengguna](#-role-pengguna)
- [Alur Pemrosesan Surat](#-alur-pemrosesan-surat-10-tahap)
- [Fitur Unggulan](#-fitur-unggulan)
- [Struktur Halaman](#-struktur-halaman)
- [Keamanan](#-keamanan)
- [Instalasi Lokal](#-instalasi-lokal)
- [Format Surat yang Didukung](#-format-surat-yang-didukung)
- [Demo Video](#-demo-video)
- [Tim Pengembang](#-tim-pengembang)
- [Lisensi](#-lisensi)

---

## 🌐 Demo & Akses

| Lingkungan | URL |
|---|---|
| Production | [tubpsuml.com](https://tubpsuml.com) |
| Local Dev | `http://127.0.0.1:8000` |

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 12 + Breeze (Auth)
- **Database:** MySQL
- **Cache/Queue:** Redis
- **API:** REST API
- **Document:** PhpOffice (Word/Excel)
- **Runtime:** Node.js

### Frontend
- Bootstrap 5
- Tailwind CSS
- Alpine.js
- Chart.js
- GSAP + Anime.js
- Three.js

### Infrastructure & Security
- **Web Server:** Nginx
- **OS:** Linux
- **CDN:** Cloudflare CDN, jsDelivr, CDNJS
- **Security:** Cloudflare WAF, Google reCAPTCHA v2, HSTS, SSL/TLS, Rate Limiting, CSRF & XSS Protection

### Tools Dev
XAMPP · Cursor IDE · Bash · Postman · Git & GitHub · SSH · Antigravity

---

## 👥 Role Pengguna

| Role | Akses |
|---|---|
| **User** | Mengajukan surat (tahap 1), revisi surat |
| **Admin Aspirasi** | Mengelola surat di tahap 2, 5–10 |
| **Admin Kassubagtu** | Mengelola surat di tahap 3 |
| **Admin Kaplai** | Mengelola surat di tahap 4 |
| **IT Support** | Mengirim notifikasi penting |

---

## 🔄 Alur Pemrosesan Surat (10 Tahap)

```
1. Pengajuan              → User
2. Verifikasi Aspirasi    → Admin Aspirasi
3. Verifikasi Kassubagtu  → Admin Kassubagtu
4. Verifikasi Kepala Balai→ Admin Kaplai
5. Penomoran Surat        → Admin Aspirasi
6. Tanda Tangan (DS)      → Admin Aspirasi
7. Pengiriman via TNDe    → Admin Aspirasi
8. Pengiriman via Srikandi→ Admin Aspirasi
9. Pengarsipan            → Admin Aspirasi
10. ✅ Selesai
```

---

## ✨ Fitur Unggulan

### 🔁 Beralih Akun (Switch Account)
- Ganti akun tanpa perlu logout terlebih dahulu
- Mendukung multi-akun dalam satu browser
- Keamanan via `switch_token` (hash, expired 30 hari)

### 📊 Dashboard Realtime
- Update otomatis setiap 15–20 detik selama user berada di halaman dashboard (fitur ini berhenti otomatis saat pindah ke halaman lain untuk mengurangi beban server)
- Chart statistik persuratan (Line, Bar, Pie, Donut, Mixed Chart)
- Kartu ringkasan: Total Surat, Draft, Disetujui, Ditolak, Diproses, Revisi

### 🔔 Notifikasi Lengkap
- Trigger otomatis: surat baru, disetujui, ditolak, revisi, SLA
- Mark as read, mark all, hapus per item atau semua
- Notifikasi lama (>1 minggu) dihapus otomatis setiap Senin pukul 01.00

### ⏱️ SLA (Service Level Agreement)
- Durasi 24 jam per surat masuk
- Notifikasi otomatis jika SLA terlampaui
- Tampilan waktu terlambat, contoh: `-1.4 jam`
- SLA dibekukan saat akhir pekan (contoh: surat masuk Jumat 15.00 → deadline Senin 15.00)

### ♻️ Manajemen Revisi & Penghapusan
- Revisi surat bisa diarahkan ke User atau kembali ke Admin Aspirasi
- Surat selesai > 3 hari → file fisik otomatis dihapus (riwayat/tracking tetap tersimpan)
- Surat ditolak yang tidak direvisi > 5 hari → dihapus otomatis termasuk tracking

### 🔐 UUID-Based Verification
- Setiap surat memiliki URL unik berbasis UUID
- Bisa diverifikasi publik tanpa login
- Dilengkapi QR Code verifikasi

### 📝 Template Surat
- Admin dapat mengunggah template custom
- User bisa memilih template saat mengajukan surat

### 🕐 Jam Operasional

| Hari | Jam Operasional |
|---|---|
| Senin – Kamis | 07.30 – 16.00 |
| Jumat | 07.30 – 16.30 |
| Sabtu – Minggu | ❌ Libur |

---

## 📁 Struktur Halaman

### User (`/`)

| Path | Halaman |
|---|---|
| `/dashboard` | Dashboard & statistik |
| `/surat` | Daftar surat (card & tabel) |
| `/surat/ajukan` | Form pengajuan surat |
| `/surat/{uuid}` | Detail & tracking surat |
| `/statistik` | Grafik statistik |
| `/notifikasi` | Pusat notifikasi |
| `/aspirasi` | Kotak aspirasi |
| `/faq` | Bantuan & dokumentasi |
| `/about` | Tentang website |

### Admin (`/Admin/`)

| Path | Halaman |
|---|---|
| `/Admin/Dashboard` | Dashboard admin realtime |
| `/Admin/surat` | Antrian & kelola surat |
| `/Admin/Surat-Masuk` | Surat baru masuk |
| `/Admin/Surat-Proses` | Surat sedang diproses |
| `/Admin/Surat-Selesai` | Surat selesai |
| `/Admin/Surat-Ditolak` | Surat ditolak/revisi |
| `/Admin/Laporan` | Rekap bulanan (export Excel/PDF) |
| `/Admin/Riwayat` | Riwayat pemrosesan (export CSV/Excel) |
| `/Admin/Chart` | Statistik & chart lengkap |
| `/Admin/Aspirasi` | Kelola aspirasi user |
| `/Admin/Template` | Kelola template surat |
| `/Admin/Settings/Users` | Data pegawai |
| `/Admin/Settings/File-Surat` | Kelola file fisik surat |
| `/Admin/Settings/Logs` | Log aktivitas sistem |
| `/Admin/faq` | Pengelolaan FAQ |

---

## 🔒 Keamanan

- ✅ Hashing password & NIP (bcrypt)
- ✅ Google reCAPTCHA v2
- ✅ Email verifikasi saat registrasi
- ✅ CSRF Protection (Laravel built-in)
- ✅ XSS Protection
- ✅ SQL Injection Prevention
- ✅ Rate Limit login: 5x/menit per IP
- ✅ RBAC (Role Based Access Control)
- ✅ Anti-Enumeration via UUID
- ✅ Validasi MIME & ukuran file upload
- ✅ HSTS (HTTP Strict Transport Security)
- ✅ Cloudflare WAF

---

## 📦 Instalasi Lokal

```bash
# Clone repo
git clone https://github.com/Ye-Shaiyoe/Surat-Laravel
cd Surat-Laravel

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=persuratan
# DB_USERNAME=root
# DB_PASSWORD=

# Migrasi & seeder
php artisan migrate --seed

# Jalankan server
php artisan serve
npm run build -> npm run dev
```

> **Requirement:** PHP 8.2+, Composer, Node.js, MySQL/MariaDB, Redis (opsional untuk queue)

---

## 📄 Format Surat yang Didukung

- **Upload:** `.docx` (Word), maks. 10MB
- **Lampiran:** Opsional, maks. 20MB
- **Jenis Surat:** Nota Dinas, Surat Dinas, Surat Keputusan, Surat Pernyataan, Surat Keterangan, Undangan, Lainnya
- **Sifat Surat:** Biasa, Penting/Segera, Rahasia

---

## 👨‍💻 Tim Pengembang

Dikembangkan oleh siswa PKL **SMK Al-Falah** sebagai proyek full stack development.

---

## 📜 Lisensi

Proyek ini dibuat untuk keperluan **pendidikan dan PKL**. Hak cipta sepenuhnya milik tim pengembang dan instansi terkait.

---

<p align="center">Made with ❤️ by PKL SMK Al-Falah | Yusuf Akram | BP Suml</p>
