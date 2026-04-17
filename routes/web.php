<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\TemplateSuratController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\SuratController as UserSurat;
use App\Http\Controllers\User\TemplateController as UserTemplateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationApiController;

Route::get('/', function () {
    return view('welcome');
});


// ===== USER =====
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    Route::get('/template', [UserTemplateController::class, 'index'])->name('user.template.index');
    Route::get('/template/download/{nama}', [UserSurat::class, 'templateDownload'])->name('user.template.download');
    Route::get('/about', function () {
        return view('user.about.index', ['title' => 'Tentang Aplikasi']);
    })->name('user.about.index');

    Route::prefix('surat')->name('user.surat.')->group(function () {
        Route::get('/',          [UserSurat::class, 'index'])->name('index');
        Route::get('/ajukan',    [UserSurat::class, 'create'])->name('create');
        Route::post('/ajukan',   [UserSurat::class, 'store'])->name('store');
        Route::get('/{surat}',   [UserSurat::class, 'show'])->name('show');
        Route::get('/{surat}/preview/{tipe}', [UserSurat::class, 'preview'])->name('preview');
        Route::get('/{surat}/download/{tipe}', [UserSurat::class, 'download'])->name('download');
        Route::post('/{surat}/reupload', [UserSurat::class, 'reuploadFile'])->name('reupload');
        Route::delete('/{surat}', [UserSurat::class, 'requestDelete'])->name('requestDelete');
    });
});


// ===== ADMIN =====
Route::prefix('Admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {

    // Role Selection (tanpa middleware admin.role.check, karena ini tempat pilih role)
    Route::get('/Role-Selection', [\App\Http\Controllers\Admin\AdminRoleSelectionController::class, 'show'])->name('role.select');
    Route::post('/Role-Selection', [\App\Http\Controllers\Admin\AdminRoleSelectionController::class, 'store'])->name('role.store');

    // Download routes (tanpa admin.role.check agar binary gak corrupt)
    Route::get('/Surat/{surat}/preview/{tipe}', [\App\Http\Controllers\Admin\SuratController::class, 'preview'])->name('surat.preview');
    Route::get('/Surat/{surat}/preview-content/{tipe}', [\App\Http\Controllers\Admin\SuratController::class, 'previewContent'])->name('surat.previewContent');
    Route::get('/Surat/{surat}/download/{tipe}', [\App\Http\Controllers\Admin\SuratController::class, 'download'])->name('surat.download');

    // Dashboard & other routes (dengan middleware admin.role.check)
    Route::middleware(['admin.role.check'])->group(function () {
        Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/Surat', [SuratController::class, 'index'])->name('surat.index');
        Route::get('/Surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
        Route::post('/Surat/{surat}/setujui', [SuratController::class, 'setujui'])->name('surat.setujui');
        Route::post('/Surat/{surat}/tolak', [SuratController::class, 'tolak'])->name('surat.tolak');
        Route::post('/Surat/delete-request/{deleteRequest}/approve', [SuratController::class, 'approveDelete'])->name('surat.approveDelete');
        Route::post('/Surat/delete-request/{deleteRequest}/reject', [SuratController::class, 'rejectDelete'])->name('surat.rejectDelete');

        Route::get('/Laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/Laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

        Route::get('/Chart', [\App\Http\Controllers\Admin\ChartController::class, 'index'])->name('chart.index');
        Route::get('/Chart/data', [\App\Http\Controllers\Admin\ChartController::class, 'data'])->name('chart.data');

        // Riwayat Pemrosesan Surat
        Route::get('/Riwayat', [\App\Http\Controllers\Admin\RiwayatController::class, 'index'])->name('riwayat.index');

        Route::get('/Template', [TemplateSuratController::class, 'index'])->name('template.index');
        Route::get('/Template/download/{nama}', [TemplateSuratController::class, 'download'])->name('template.download');
        Route::post('/Template', [TemplateSuratController::class, 'store'])->name('template.store');
        Route::delete('/Template', [TemplateSuratController::class, 'destroy'])->name('template.destroy');

        // Users / Pegawai
        Route::get('/Users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/Users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::delete('/Users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Notifikasi Admin
        Route::get('/Notifikasi', [\App\Http\Controllers\Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::post('/Notifikasi/read/{id}', [\App\Http\Controllers\Admin\NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
        Route::post('/Notifikasi/read-all', [\App\Http\Controllers\Admin\NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.readAll');
        Route::delete('/Notifikasi/{id}', [\App\Http\Controllers\Admin\NotifikasiController::class, 'destroy'])->name('notifikasi.delete');
        Route::delete('/Notifikasi', [\App\Http\Controllers\Admin\NotifikasiController::class, 'destroyAll'])->name('notifikasi.deleteAll');
    });
});

Route::middleware(['auth', 'verified'])->prefix('notif')->name('notif.')->group(function () {
    Route::get('/read/{id}',      [\App\Http\Controllers\User\NotifikasiController::class, 'read'])->name('read');
    Route::post('/read-all',      [\App\Http\Controllers\User\NotifikasiController::class, 'readAll'])->name('readAll');
    Route::post('/delete/{id}',   [NotificationApiController::class, 'destroy'])->name('delete');
    Route::post('/delete-all',    [NotificationApiController::class, 'destroyAll'])->name('deleteAll');
});

// ===== PROFILE (Breeze) =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
