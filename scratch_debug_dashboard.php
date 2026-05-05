<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bulanSelected = 5;
$tahunSelected = 2026;

$admin = App\Models\User::where('role', 'admin_aspirasi')->first();
Illuminate\Support\Facades\Auth::login($admin);

$workloadQuery = App\Models\Surat::query();
$workloadQuery->where(function($q) {
    $q->where('tahap_sekarang', 2)->orWhere('tahap_sekarang', '>=', 5);
});
$totalProses = (clone $workloadQuery)->whereIn('status', ['proses', 'revisi', 'revisi_admin'])->count();


$antrianQuery = App\Models\Surat::whereIn('status', ['proses', 'revisi', 'revisi_admin'])
    ->with('user')
    ->orderByRaw("CASE WHEN status = 'revisi' OR status = 'revisi_admin' THEN 0 ELSE 1 END")
    ->orderBy('created_at', 'desc')
    ->limit(10);

$antrianQuery->where(function($q) {
    $q->where('tahap_sekarang', 2)
      ->orWhere('tahap_sekarang', '>=', 5);
});

$antrianRawCount = $antrianQuery->count();

$antrian = $antrianQuery->get()->map(function($s) {
    $s->status_label = match($s->status) {
        'revisi' => 'Perlu Revisi User',
        'revisi_admin' => 'Revisi Internal',
        'proses' => 'Proses',
        default => $s->status
    };
    $s->sla_status = $s->deadline_sla && now()->gt($s->deadline_sla) ? 'terlambat' : 'ok';
    return $s;
})->values();

$antrianCount = $antrian->count();

echo json_encode([
    'totalProses' => $totalProses,
    'antrianRawCount' => $antrianRawCount,
    'antrianCount' => $antrianCount,
    'antrianItems' => $antrian->toArray()
], JSON_PRETTY_PRINT);
