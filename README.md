# 📬 Persuratan BP Suml

> Sistem manajemen surat digital berbasis web untuk **Balai Pengujian / BP Suml**, dikembangkan sebagai proyek PKL oleh siswa SMK Al-Falah.

---

## 🌐 Demo & Akses

| Lingkungan | URL |
|---|---|
| Production | [persuratan.bp.suml.com](https://persuratan.bp.suml.com) |
| Local Dev | `http://127.0.0.1:8000` |

---

## 🛠️ Tech Stack

### Backend
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-Cache-DC382D?style=flat&logo=redis&logoColor=white)

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
- XAMPP, Cursor IDE, Bash, Postman, Git & GitHub, SSH, Antigravity

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
1. Pengajuan           → User
2. Verifikasi Aspirasi → Admin Aspirasi
3. Verifikasi Kassubagtu → Admin Kassubagtu
4. Verifikasi Kepala Balai → Admin Kaplai
5. Penomoran Surat     → Admin Aspirasi
6. Tanda Tangan (DS)   → Admin Aspirasi
7. Pengiriman via TNDe → Admin Aspirasi
8. Pengiriman via Srikandi → Admin Aspirasi
9. Pengarsipan         → Admin Aspirasi
10. ✅ Selesai
```

---

## ✨ Fitur Unggulan

### 🔁 Beralih Akun (Switch Account)
- Ganti akun tanpa perlu logout terlebih dahulu
- Mendukung multi-akun dalam satu browser
- Keamanan via `switch_token` (hash, expired 30 hari)

### 📊 Dashboard Realtime
- Update otomatis setiap 15–20 detik(contoh jika user berada di dashoard. itu akan berjalan tapi jika ganti misal ke template maka fitur update otomatis gk bekerja) untuk mngurangi beban server
- Chart statistik persuratan (Line, Bar, Pie, Donut, Mixed Chart)
- Kartu ringkasan: Total Surat, Draft, Disetujui, Ditolak, Diproses, Revisi

### 🔔 Notifikasi Lengkap
- Trigger otomatis: surat baru, disetujui, ditolak, revisi, SLA
- Mark as read, mark all, hapus per item atau semua
- Notifikasi lama (>1 minggu) dihapus otomatis setiap Senin pukul 01.00

### ⏱️ SLA (Service Level Agreement)
- Durasi 24 jam per surat masuk
- Notifikasi otomatis jika SLA terlampaui
- Tampilan waktu terlambat: contoh `-1.4 jam`
- SLA dibekukan saat akhir pekan (contoh: surat Jumat 15.00 → deadline Senin 15.00)

### ♻️ Manajemen Revisi & Penghapusan
- Revisi surat bisa diarahkan ke User atau kembali ke Admin Aspirasi
- Surat selesai >3 hari → file fisik otomatis dihapus (tracking tetap ada)
- Surat ditolak yang tidak direvisi >5 hari → dihapus otomatis termasuk tracking

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
git clone https://github.com/username/persuratan-bp-suml.git
cd persuratan-bp-suml

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
npm run dev
```

> **Requirement:** PHP 8.2+, Composer, Node.js, MySQL/MariaDB, Redis (opsional untuk queue)

---

## 📄 Format Surat yang Didukung

- **Upload:** `.docx` (Word), maks. 10MB
- **Lampiran:** Opsional, maks. 10MB
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
