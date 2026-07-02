<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan cleanup file kadaluarsa setiap hari jam 1 pagi
Schedule::command('files:cleanup-expired')->dailyAt('01:00');

// Jalankan cleanup notifikasi lama (> 7 hari) setiap hari jam 1 pagi
Schedule::command('notifications:cleanup')->dailyAt('01:00');

// Hapus surat ditolak yang tidak direvisi > 5 hari
Schedule::command('surat:cleanup-rejected')->dailyAt('01:15');

// Bersihkan referensi file DB yang tidak memiliki file fisik (mingguan)
Schedule::command('surat:cleanup-orphaned-references')->weekly();

// Kirim notifikasi SLA ke admin — setiap 30 menit saat jam kerja
// Senin–Jumat 07:00–17:00
Schedule::command('surat:remind-sla')
    ->everyThirtyMinutes()
    ->weekdays()
    ->between('07:00', '17:00');
