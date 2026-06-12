# 📬 Persuratan BP Suml

<p align="center">
# 📬 Persuratan BP Suml
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" />
</p>

<p align="center">
  <strong>Sistem Manajemen Surat Digital Berbasis Web untuk Balai Pengujian (BP Suml)</strong><br />
  Dikembangkan sebagai proyek Praktik Kerja Lapangan (PKL) oleh siswa SMK Al-Falah.
</p>

<p align="center">
  <a href="https://tubpsuml.com"><strong>🌐 Live Demo (Production)</strong></a> | 
  <a href="https://drive.google.com/drive/folders/1UYCrUPZQjaQawTXM33jwFN9uRqLGoQh_?usp=sharing"><strong>🎬 Tonton Demo Video</strong></a>
</p>

---

## 📑 Daftar Isi
- [🌐 Demo & Akses](#-demo--akses)
- [🛠️ Tech Stack](#️-tech-stack)
- [👥 Role Pengguna](#-role-pengguna)
- [🔄 Alur Pemrosesan Surat](#-alur-pemrosesan-surat)
- [✨ Fitur Unggulan](#-fitur-unggulan)
- [📁 Struktur Halaman](#-struktur-halaman)
- [🔒 Fitur Keamanan](#-fitur-keamanan)
- [📄 Format & Jenis Surat](#-format--jenis-surat)
- [📦 Panduan Instalasi Lokal](#-panduan-instalasi-lokal)
- [👨‍💻 Tim Pengembang](#-tim-pengembang)
- [📜 Lisensi](#-lisensi)

---

## 🌐 Demo & Akses

| Lingkungan | URL / Alamat Akses |
| :--- | :--- |
| **Production Server** | [tubpsuml.com](https://tubpsuml.com) |
| **Local Development** | `http://127.0.0.1:8000` |

---

## 🛠️ Tech Stack

### 🔹 Backend & Database
* **Framework:** Laravel 12 + Laravel Breeze (Authentication)
* **Language Runtime:** PHP 8.2+ & Node.js
* **Database:** MySQL 8.0
* **Cache & Queue:** Redis (Opsional untuk optimalisasi performa antrian)
* **Document Processor:** PhpOffice (Word / Excel handling)

### 🔹 Frontend & UI/UX
* **Utility-First CSS:** Tailwind CSS & Bootstrap 5
* **Reactivity:** Alpine.js
* **Data Visualization:** Chart.js (Grafik Interaktif)
* **Animation Libraries:** GSAP, Anime.js, & Three.js

### 🔹 Infrastruktur & Integrasi
* **Web Server:** Nginx (Linux OS Environment)
* **Content Delivery Network (CDN):** Cloudflare CDN, jsDelivr, CDNJS

---

## 👥 Role Pengguna & Hak Akses

| Role | Deskripsi Hak Akses |
| :--- | :--- |
| **User (Pegawai)** | Mengajukan surat baru (Tahap 1) & melakukan revisi surat jika ditolak. |
| **Admin Aspirasi** | Verifikator awal (Tahap 2), penomoran, tanda tangan digital, pengiriman, dan pengarsipan (Tahap 5–10). |
| **Admin Kassubagtu** | Melakukan verifikasi tingkat menengah (Tahap 3). |
| **Admin Kaplai** | Melakukan verifikasi akhir / persetujuan Kepala Balai (Tahap 4). |
| **IT Support** | Manajemen sistem eksternal & pengiriman notifikasi massal/penting. |

---

## 🔄 Alur Pemrosesan Surat

Sistem ini menerapkan workflow **10 Tahap** yang terintegrasi secara runtut:

```mermaid
graph TD
    A[1. Pengajuan oleh User] --> B[2. Verifikasi Admin Aspirasi]
    B --> C[3. Verifikasi Kassubagtu]
    C --> D[4. Verifikasi Kepala Balai]
    D --> E[5. Penomoran Surat oleh Admin Aspirasi]
    E --> F[6. Tanda Tangan Digital / DS]
    F --> G[7. Pengiriman via TNDe]
    G --> H[8. Pengiriman via Srikandi]
    H --> I[9. Pengarsipan Otomatis]
    I --> J[10. Selesai ✅]
