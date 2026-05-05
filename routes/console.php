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
