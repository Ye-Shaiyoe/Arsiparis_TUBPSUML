# Desain Database — Sistem Persuratan Digital BP Suml

**Framework:** Laravel 12 | **Database:** PostgreSQL  
**Total Tabel:** 18 tabel (aplikasi) + 6 tabel sistem Laravel

---

## Daftar Tabel

| # | Nama Tabel | Kategori | Keterangan |
|---|---|---|---|
| 1 | `users` | Inti | Seluruh pengguna sistem (multi-role) |
| 2 | `surats` | Inti | Data surat yang diajukan |
| 3 | `surat_tahapans` | Inti | Riwayat tahap per surat |
| 4 | `surat_delete_requests` | Inti | Permintaan hapus surat oleh user |
| 5 | `aspirasis` | Fitur | Aspirasi / feedback dari user |
| 6 | `it_support_tickets` | Fitur | Tiket laporan ke IT Support |
| 7 | `activity_logs` | Audit | Log aktivitas pengguna |
| 8 | `notifications` | Sistem | Notifikasi database Laravel |
| 9 | `sessions` | Sistem | Sesi pengguna |
| 10 | `password_reset_tokens` | Sistem | Token reset password |
| 11 | `cache` | Sistem | Cache database Laravel |
| 12 | `cache_locks` | Sistem | Lock cache Laravel |
| 13 | `jobs` | Sistem | Queue jobs Laravel |
| 14 | `job_batches` | Sistem | Batch queue Laravel |
| 15 | `failed_jobs` | Sistem | Queue jobs gagal |

---

## ERD (Entity Relationship — Teks)

```
users ─────────────────┬──── surats (1 user : N surats)
                       ├──── surat_tahapans.diproses_oleh (nullable)
                       ├──── surat_delete_requests.user_id
                       ├──── surat_delete_requests.admin_id (nullable)
                       ├──── aspirasis (1 user : N aspirasis)
                       ├──── it_support_tickets.admin_id
                       ├──── activity_logs (1 user : N logs)
                       └──── notifications (polymorphic → notifiable)

surats ─────────────── ┬──── surat_tahapans (1 surat : N tahapan)
                       └──── surat_delete_requests (1 surat : 1 request)
```

---

## Detail Skema Per Tabel

---

### 1. `users`
Tabel utama pengguna. Satu akun dapat memegang multi-role.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key integer |
| `uuid` | CHAR(36) | UNIQUE, NULLABLE | Public-facing identifier (anti-enumeration) |
| `name` | VARCHAR(255) | NOT NULL | Nama lengkap |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email login |
| `nip` | VARCHAR(255) | UNIQUE, NULLABLE | Nomor Induk Pegawai |
| `role` | ENUM | NOT NULL, DEFAULT 'user' | Lihat nilai enum di bawah |
| `role_selected` | TINYINT(1) | DEFAULT 0 | Flag sudah pilih role (multi-role flow) |
| `profile_photo` | VARCHAR(255) | NULLABLE | Path foto profil |
| `signature_path` | VARCHAR(255) | NULLABLE | Path file tanda tangan digital |
| `signature_pin` | VARCHAR(255) | NULLABLE | PIN TTE (hashed) |
| `switch_token` | VARCHAR(100) | NULLABLE | Token switch akun (hashed) |
| `switch_token_expires_at` | TIMESTAMP | NULLABLE | Expired 30 hari sejak login terakhir |
| `email_verified_at` | TIMESTAMP | NULLABLE | Verifikasi email |
| `password` | VARCHAR(255) | NOT NULL | Password (bcrypt) |
| `remember_token` | VARCHAR(100) | NULLABLE | Remember me token |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Nilai ENUM `role`:**
```
'user'                → Pengusul surat (internal/eksternal)
'admin_aspirasi'      → Admin tahap 2, 5–10 + re-upload dokumen
'admin_kasubbag_tu'   → Admin tahap 3 (verifikasi Kasubbag TU)
'admin_kepala_balai'  → Admin tahap 4 (verifikasi Kepala Balai)
'it_support'          → Kelola tiket teknis & broadcast notifikasi
'admin'               → (legacy, tidak aktif dipakai)
```

---

### 2. `surats`
Tabel inti — setiap baris adalah satu pengajuan surat.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | — |
| `uuid` | CHAR(36) | UNIQUE, NULLABLE | Public-facing ID (dipakai di URL) |
| `user_id` | BIGINT UNSIGNED | FK → users.id, CASCADE | Pengusul surat |
| `judul` | VARCHAR(255) | NOT NULL | Judul / perihal surat |
| `jenis` | ENUM | NOT NULL | Jenis surat (lihat di bawah) |
| `sifat` | ENUM | DEFAULT 'biasa' | `biasa` / `segera` / `rahasia` |
| `tujuan` | TEXT | NOT NULL | Tujuan surat |
| `catatan_pengusul` | TEXT | NULLABLE | Catatan tambahan dari pengusul |
| `alasan_keterlambatan` | VARCHAR(255) | NULLABLE | Diisi jika melewati deadline SLA |
| `file_word` | VARCHAR(255) | NULLABLE | Path file dokumen (.docx) di storage private |
| `file_lampiran` | VARCHAR(255) | NULLABLE | Path file lampiran |
| `nomor_surat` | VARCHAR(255) | NULLABLE | Diisi saat tahap 5 (penomoran) |
| `tanggal_surat` | DATE | NULLABLE | Diisi saat penomoran |
| `tahap_sekarang` | INT | DEFAULT 1 | Tahap aktif (1–10) |
| `status` | ENUM | DEFAULT 'proses' | Status surat (lihat di bawah) |
| `status_revisi` | TINYINT(1) | DEFAULT 0 | Sedang dalam proses revisi |
| `revisi_count` | INT | DEFAULT 0 | Jumlah total revisi yang dilakukan |
| `revisi_uploaded_at` | TIMESTAMP | NULLABLE | Waktu user upload file revisi |
| `perlu_follow_up` | TINYINT(1) | DEFAULT 0 | Flag follow-up manual |
| `catatan_follow_up` | TEXT | NULLABLE | Isi catatan follow-up |
| `rating` | INT | NULLABLE | Rating user setelah selesai (1–5) |
| `deadline_sla` | TIMESTAMP | NULLABLE | 1 hari kerja sejak submit |
| `disetujui_pada` | TIMESTAMP | NULLABLE | Waktu surat disetujui final |
| `file_dihapus_pada` | TIMESTAMP | NULLABLE | Waktu file fisik dihapus (cleanup) |
| `file_expires_at` | TIMESTAMP | NULLABLE | Deadline sebelum file otomatis dihapus |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Nilai ENUM `jenis`:**
```
'nota_dinas'       'surat_dinas'        'surat_keputusan'
'surat_pernyataan' 'surat_keterangan'   'surat_undangan'
'surat_lainnya'
```

**Nilai ENUM `status`:**
```
'draft'          → Disimpan belum diajukan
'proses'         → Sedang berjalan di salah satu tahap
'revisi'         → Dikembalikan ke User untuk revisi
'revisi_admin'   → Dikembalikan ke Admin Aspirasi untuk perbaikan
'ditolak'        → Ditolak permanen
'selesai'        → Selesai semua 10 tahap
```

---

### 3. `surat_tahapans`
Riwayat detail per tahap dari setiap surat. Satu surat bisa punya hingga 10 record.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK | — |
| `surat_id` | BIGINT UNSIGNED | FK → surats.id, CASCADE | Surat terkait |
| `tahap` | INT | NOT NULL | Nomor tahap (1–10) |
| `nama_tahap` | VARCHAR(255) | NOT NULL | Label deskriptif tahap |
| `status` | ENUM | DEFAULT 'menunggu' | `menunggu` / `proses` / `selesai` / `ditolak` |
| `diproses_oleh` | BIGINT UNSIGNED | FK → users.id, SET NULL | Admin yang memproses tahap ini |
| `catatan` | TEXT | NULLABLE | Catatan dari admin (alasan tolak, komentar) |
| `selesai_pada` | TIMESTAMP | NULLABLE | Waktu tahap diselesaikan |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Alur 10 Tahap:**
```
1  → Pengajuan oleh User
2  → Verifikasi Admin Aspirasi
3  → Verifikasi Kasubbag TU
4  → Verifikasi Kepala Balai
5  → Penomoran Surat
6  → Tanda Tangan Digital (TTE)
7  → Pengiriman via TNDe
8  → Pengiriman via Srikandi
9  → Pengarsipan
10 → Selesai
```

---

### 4. `surat_delete_requests`
Permintaan penghapusan surat yang harus disetujui admin.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK | — |
| `surat_id` | BIGINT UNSIGNED | FK → surats.id, CASCADE | Surat yang ingin dihapus |
| `user_id` | BIGINT UNSIGNED | FK → users.id, CASCADE | User yang mengajukan |
| `admin_id` | BIGINT UNSIGNED | FK → users.id, SET NULL, NULLABLE | Admin yang memproses |
| `alasan` | TEXT | NULLABLE | Alasan dari user |
| `status` | ENUM | DEFAULT 'pending' | `pending` / `disetujui` / `ditolak` |
| `admin_catatan` | TEXT | NULLABLE | Catatan balasan admin |
| `admin_approved_at` | TIMESTAMP | NULLABLE | Waktu admin menyetujui/menolak |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

---

### 5. `aspirasis`
Aspirasi, saran, keluhan, atau pertanyaan dari pengguna.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK | — |
| `uuid` | CHAR(36) | UNIQUE, NOT NULL | Public-facing ID |
| `user_id` | BIGINT UNSIGNED | FK → users.id, CASCADE | Pengirim aspirasi |
| `judul` | VARCHAR(255) | NOT NULL | Judul aspirasi |
| `isi` | TEXT | NOT NULL | Isi / konten aspirasi |
| `kategori` | VARCHAR(255) | DEFAULT 'lainnya' | Kategori bebas (saran, keluhan, bug, dll) |
| `tujuan` | VARCHAR(255) | DEFAULT 'admin' | `admin` / `itsupport` (routing tujuan) |
| `status` | ENUM | DEFAULT 'pending' | `pending` / `dibaca` / `dibalas` |
| `balasan` | TEXT | NULLABLE | Balasan dari admin/IT Support |
| `dibalas_at` | TIMESTAMP | NULLABLE | Waktu dibalas |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

---

### 6. `it_support_tickets`
Tiket laporan teknis dari Admin ke IT Support.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK | — |
| `admin_id` | BIGINT UNSIGNED | FK → users.id, CASCADE | Admin pelapor |
| `subjek` | VARCHAR(255) | NOT NULL | Judul tiket |
| `kategori` | ENUM | NOT NULL | `bug` / `error` / `fitur` / `lainnya` |
| `detail` | TEXT | NOT NULL | Deskripsi masalah |
| `status` | ENUM | DEFAULT 'menunggu' | `menunggu` / `diproses` / `selesai` |
| `catatan_it` | TEXT | NULLABLE | Balasan / catatan dari IT Support |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

---

### 7. `activity_logs`
Audit trail seluruh aksi pengguna di sistem.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK | — |
| `user_id` | BIGINT UNSIGNED | FK → users.id, CASCADE | Pelaku aksi |
| `action` | VARCHAR(255) | NOT NULL | Tipe aksi: `create`, `update`, `delete`, `download`, `view`, dll |
| `model_type` | VARCHAR(255) | NULLABLE | Nama model target: `Surat`, `User`, dll |
| `model_id` | BIGINT UNSIGNED | NULLABLE | ID record yang diaksi |
| `description` | VARCHAR(255) | NULLABLE | Deskripsi teks bebas |
| `changes` | JSON | NULLABLE | Diff sebelum/sesudah (before → after) |
| `ip_address` | VARCHAR(255) | NULLABLE | IP address user |
| `user_agent` | TEXT | NULLABLE | Browser / UA string |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Indeks:** `(user_id, created_at)`, `(action, created_at)`, `(model_type, model_id)`

---

### 8. `notifications` (Laravel Database Notification)

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | CHAR(36) | PK (UUID) | — |
| `type` | VARCHAR(255) | NOT NULL | Namespace class Notification |
| `notifiable_type` | VARCHAR(255) | NOT NULL | Polymorphic: model penerima |
| `notifiable_id` | BIGINT UNSIGNED | NOT NULL | ID penerima |
| `data` | TEXT | NOT NULL | JSON payload notifikasi |
| `read_at` | TIMESTAMP | NULLABLE | NULL = belum dibaca |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

---

### 9–10. `sessions` & `password_reset_tokens` (Laravel Standard)

**sessions**

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | VARCHAR(255) PK | Session ID |
| `user_id` | BIGINT NULLABLE | FK ke users (indexed) |
| `ip_address` | VARCHAR(45) | IPv4 / IPv6 |
| `user_agent` | TEXT | — |
| `payload` | LONGTEXT | Session data (terenkripsi) |
| `last_activity` | INT | Unix timestamp (indexed) |

**password_reset_tokens**

| Kolom | Tipe | Keterangan |
|---|---|---|
| `email` | VARCHAR(255) PK | — |
| `token` | VARCHAR(255) | Hashed token |
| `created_at` | TIMESTAMP | Expired dalam 60 menit |

---

### 11–15. Tabel Queue & Cache (Laravel Standard)

**cache** → `key` (PK), `value`, `expiration`  
**cache_locks** → `key` (PK), `owner`, `expiration`  
**jobs** → Queue jobs pending  
**job_batches** → Batch queue  
**failed_jobs** → Jobs yang gagal dieksekusi  

---

## Relasi Lengkap

```
┌──────────────────────────────────────────────────────────────┐
│                          users                               │
│  id, uuid, name, email, nip, role, role_selected,           │
│  profile_photo, signature_path, signature_pin,              │
│  switch_token, switch_token_expires_at                      │
└────────┬───────────────────────────────────────┬────────────┘
         │ hasMany                                │ hasMany
         ▼                                        ▼
┌─────────────────────┐              ┌─────────────────────────┐
│       surats        │              │       aspirasis         │
│  id, uuid, user_id  │              │  id, uuid, user_id      │
│  jenis, sifat       │              │  judul, isi, kategori   │
│  status (6 nilai)   │              │  tujuan, status         │
│  tahap_sekarang     │              │  balasan, dibalas_at    │
│  nomor_surat        │              └─────────────────────────┘
│  deadline_sla       │
│  rating, ...        │
└────────┬────────────┘
         │
    ┌────┴─────────────────────┐
    │ hasMany                  │ hasOne
    ▼                          ▼
┌──────────────────┐  ┌────────────────────────┐
│  surat_tahapans  │  │  surat_delete_requests │
│  surat_id        │  │  surat_id, user_id     │
│  tahap (1-10)    │  │  admin_id (nullable)   │
│  status          │  │  alasan, status        │
│  diproses_oleh ──┼──┤→ FK ke users.id        │
│  catatan         │  └────────────────────────┘
│  selesai_pada    │
└──────────────────┘

┌──────────────────────────────┐
│     it_support_tickets       │
│  admin_id → users.id         │
│  subjek, kategori, detail    │
│  status, catatan_it          │
└──────────────────────────────┘

┌──────────────────────────────┐
│       activity_logs          │
│  user_id → users.id          │
│  action, model_type, model_id│
│  changes (JSON), ip_address  │
└──────────────────────────────┘

┌──────────────────────────────┐
│       notifications          │
│  notifiable_type + id        │ (Polymorphic → users)
│  type, data, read_at         │
└──────────────────────────────┘
```

---

## Aturan Bisnis Penting (Terimplementasi di DB)

| Aturan | Implementasi |
|---|---|
| UUID di public URL | `surats.uuid`, `users.uuid`, `aspirasis.uuid` — semua UNIQUE |
| File auto-expire | `surats.file_expires_at` → diproses oleh `CleanupExpiredFiles` command |
| `surats.deadline_sla` | Diisi otomatis saat surat diajukan. Dihitung 1 hari kerja + 6 jam (30 jam kerja efektif) sejak submit, melewati weekend dan di luar jam operasional. |
| Revisi terisolasi | `status_revisi`, `revisi_count`, `revisi_uploaded_at` di `surats` |
| Delete butuh approval | Tabel `surat_delete_requests` memastikan tidak ada hard-delete langsung |
| Audit trail lengkap | Tabel `activity_logs` dengan JSON diff di kolom `changes` |
| TTE (Tanda Tangan) | `signature_path` + `signature_pin` di `users` |
| Switch account token | `switch_token` (hashed) + `switch_token_expires_at` di `users` |
| Rating kepuasan | `surats.rating` (1–5), diisi setelah status = `selesai` |
