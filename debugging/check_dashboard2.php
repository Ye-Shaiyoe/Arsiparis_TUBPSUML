<?php
// Test the exact query that the DashboardController generates
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Surat;
use App\Models\User;

$admin = User::where('name', 'bakka')->first();
echo "Admin: {$admin->name} | Role: {$admin->role}" . PHP_EOL;

// Replicate the EXACT roleFilter from DashboardController
$roleFilter = function ($q) use ($admin) {
    if ($admin->role === 'admin_aspirasi') {
        $q->where(function ($sq) {
            $sq->where('tahap_sekarang', 2)->orWhere('tahap_sekarang', '>=', 5);
        });
    } elseif ($admin->role === 'admin_kasubbag_tu') {
        $q->where('tahap_sekarang', 3);
    } elseif ($admin->role === 'admin_kepala_balai') {
        $q->where('tahap_sekarang', 4);
    }
};

// This is the EXACT query used in the controller
$workloadQuery = Surat::query()->where($roleFilter);

echo PHP_EOL . "=== Workload Query SQL ===" . PHP_EOL;
echo $workloadQuery->toSql() . PHP_EOL;
echo "Bindings: " . json_encode($workloadQuery->getBindings()) . PHP_EOL;

$antrian = (clone $workloadQuery)
    ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
    ->with('user')
    ->orderByRaw("CASE WHEN status IN ('revisi', 'revisi_admin') THEN 0 ELSE 1 END")
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo PHP_EOL . "=== Antrian Results ===" . PHP_EOL;
echo "Count: " . $antrian->count() . PHP_EOL;

$mapped = $antrian->map(function ($s) {
    return [
        'id'            => $s->id,
        'uuid'          => $s->uuid,
        'judul'         => $s->judul,
        'jenis'         => $s->jenis_label,
        'status'        => $s->status,
        'status_label'  => match ($s->status) {
            'revisi'       => 'Perlu Revisi User',
            'revisi_admin' => 'Revisi Internal',
            'proses'       => 'Proses',
            default        => $s->status,
        },
        'sla_status'    => $s->sla_status,
        'tahap_sekarang'=> $s->tahap_sekarang,
        'deadline_sla'  => $s->deadline_sla?->toISOString(),
        'created_at'    => $s->created_at?->toISOString(),
        'user'          => $s->user ? ['name' => $s->user->name] : null,
    ];
})->values();

echo PHP_EOL . "=== Mapped JSON (what gets sent to view) ===" . PHP_EOL;
echo json_encode($mapped, JSON_PRETTY_PRINT) . PHP_EOL;
