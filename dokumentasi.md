# Dokumentasi Path Frontend ↔ Backend
## Sistem Informasi Persuratan — Balai Pengelolaan SUML

---

## 1. Landing Page & Auth

| # | Frontend (View) | Backend (Controller) |
|---|---|---|
| a | `resources/views/welcome.blade.php` | `app/Http/Controllers/WelcomeController.php` |
| b | `resources/views/panduan.blade.php` | `app/Http/Controllers/WelcomeController.php` |
| c | `resources/views/auth/login.blade.php` | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` |
| d | `resources/views/auth/register.blade.php` | `app/Http/Controllers/Auth/RegisteredUserController.php` |
| e | `resources/views/auth/forgot-password.blade.php` | `app/Http/Controllers/Auth/PasswordResetLinkController.php` |
| f | `resources/views/auth/reset-password.blade.php` | `app/Http/Controllers/Auth/NewPasswordController.php` |
| g | `resources/views/auth/verify-email.blade.php` | `app/Http/Controllers/Auth/VerifyEmailController.php` |
| h | `resources/views/auth/confirm-password.blade.php` | `app/Http/Controllers/Auth/ConfirmablePasswordController.php` |
| i | `resources/views/surat/verifikasi.blade.php` | `app/Http/Controllers/VerifikasiSuratController.php` |
| j | `resources/views/surat/verifikasi-error.blade.php` | `app/Http/Controllers/VerifikasiSuratController.php` |

---

## 2. Bagian User

| # | Frontend (View) | Backend (Controller) |
|---|---|---|
| 1 | `resources/views/dashboard.blade.php` | `app/Http/Controllers/User/DashboardController.php` |
| 2 | `resources/views/user/surat/index.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 3 | `resources/views/user/surat/create.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 4 | `resources/views/user/surat/show.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 5 | `resources/views/user/surat/edit.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 6 | `resources/views/user/surat/table.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 7 | `resources/views/user/surat/file_index.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 8 | `resources/views/user/surat/preview.blade.php` | `app/Http/Controllers/User/SuratController.php` |
| 9 | `resources/views/user/notifikasi/index.blade.php` | `app/Http/Controllers/User/NotifikasiController.php` |
| 10 | `resources/views/user/statistik/index.blade.php` | `app/Http/Controllers/User/StatistikController.php` |
| 11 | `resources/views/user/sla/index.blade.php` | `app/Http/Controllers/User/SlaMonitoringController.php` |
| 12 | `resources/views/user/agenda/index.blade.php` | `app/Http/Controllers/User/AgendaController.php` |
| 13 | `resources/views/user/template/index.blade.php` | `app/Http/Controllers/User/TemplateController.php` |
| 14 | `resources/views/user/pegawai/index.blade.php` | `app/Http/Controllers/User/PegawaiController.php` |
| 15 | `resources/views/user/pegawai/show.blade.php` | `app/Http/Controllers/User/PegawaiController.php` |
| 16 | `resources/views/user/aspirasi/index.blade.php` | `app/Http/Controllers/User/AspirasiController.php` |
| 17 | `resources/views/user/activity-log/index.blade.php` | `app/Http/Controllers/User/ActivityLogController.php` |
| 18 | `resources/views/user/activity-log/show.blade.php` | `app/Http/Controllers/User/ActivityLogController.php` |
| 19 | `resources/views/user/faq/index.blade.php` | — *(static view, no controller)* |
| 20 | `resources/views/user/about/index.blade.php` | — *(static view, no controller)* |
| 21 | `resources/views/profile/edit.blade.php` | `app/Http/Controllers/ProfileController.php` |

---

## 3. Bagian Admin

| # | Frontend (View) | Backend (Controller) |
|---|---|---|
| 1 | `resources/views/admin/dashboard.blade.php` | `app/Http/Controllers/Admin/DashboardController.php` |
| 2 | `resources/views/admin/surat/index.blade.php` | `app/Http/Controllers/Admin/SuratController.php` |
| 3 | `resources/views/admin/surat/show.blade.php` | `app/Http/Controllers/Admin/SuratController.php` |
| 4 | `resources/views/admin/laporan/index.blade.php` | `app/Http/Controllers/Admin/LaporanController.php` |
| 5 | `resources/views/admin/riwayat/index.blade.php` | `app/Http/Controllers/Admin/RiwayatController.php` |
| 6 | `resources/views/admin/analytics/sla.blade.php` | `app/Http/Controllers/Admin/AnalyticsController.php` |
| 7 | `resources/views/admin/chart/index.blade.php` | `app/Http/Controllers/Admin/ChartController.php` |
| 8 | `resources/views/admin/notifikasi/index.blade.php` | `app/Http/Controllers/Admin/NotifikasiController.php` |
| 9 | `resources/views/admin/aspirasi/index.blade.php` | `app/Http/Controllers/Admin/AspirasiController.php` |
| 10 | `resources/views/admin/template/index.blade.php` | `app/Http/Controllers/Admin/TemplateSuratController.php` |
| 11 | `resources/views/admin/Settings/user/index.blade.php` | `app/Http/Controllers/Admin/UserController.php` |
| 12 | `resources/views/admin/Settings/user/show.blade.php` | `app/Http/Controllers/Admin/UserController.php` |
| 13 | `resources/views/admin/Settings/file/index.blade.php` | `app/Http/Controllers/Admin/FileSuratController.php` |
| 14 | `resources/views/admin/Settings/logs/index.blade.php` | `app/Http/Controllers/Admin/LogController.php` |
| 15 | `resources/views/admin/bug-report/index.blade.php` | `app/Http/Controllers/Admin/BugReportController.php` |
| 16 | `resources/views/admin/faq/index.blade.php` | — *(static view, no controller)* |
| 17 | `resources/views/admin/role/select.blade.php` | `app/Http/Controllers/Admin/AdminRoleSelectionController.php` |

---

## 4. Bagian IT Support

| # | Frontend (View) | Backend (Controller) |
|---|---|---|
| 1 | `resources/views/it_support/dashboard.blade.php` | `app/Http/Controllers/ITSupportController.php` |
| 2 | `resources/views/it_support/notification_create.blade.php` | `app/Http/Controllers/ITSupportController.php` |

---

## 5. Layout & Komponen Shared

| Frontend (View) | Keterangan |
|---|---|
| `resources/views/layouts/user.blade.php` | Layout utama halaman user |
| `resources/views/layouts/admin.blade.php` | Layout utama halaman admin |
| `resources/views/layouts/itsupport.blade.php` | Layout halaman IT Support |
| `resources/views/layouts/guest.blade.php` | Layout halaman tamu (auth pages) |
| `resources/views/components/activity-heatmap.blade.php` | Komponen heatmap aktivitas |
| `resources/views/components/notif-popup.blade.php` | Komponen popup notifikasi |

---

## 6. Controller Lainnya (Non-View)

| Controller | Fungsi |
|---|---|
| `app/Http/Controllers/Auth/SwitchAccountController.php` | Token-based multi-account switching |
| `app/Http/Controllers/NotificationApiController.php` | API mark-read / delete notifikasi |
| `app/Http/Controllers/NotificationStreamController.php` | Server-Sent Events (SSE) realtime notif |
| `app/Http/Controllers/Admin/SidebarController.php` | Data badge sidebar admin (antrian count) |
