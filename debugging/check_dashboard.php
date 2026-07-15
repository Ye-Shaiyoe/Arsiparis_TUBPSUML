<?php
// Debugging script to check dashboard data
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Surat;

// Find user bakka
$user = User::where('name', 'like', '%bakka%')->first();
if ($user) {
    echo "=== USER INFO ===" . PHP_EOL;
    echo "Name: " . $user->name . PHP_EOL;
    echo "Role: " . $user->role . PHP_EOL;
    echo "Role Selected: " . $user->role_selected . PHP_EOL;
    echo "Role Label: " . $user->getRoleLabel() . PHP_EOL;
} else {
    echo "User bakka not found!" . PHP_EOL;
}

echo PHP_EOL . "=== ALL SURAT WITH STATUS PROSES/REVISI ===" . PHP_EOL;
$surats = Surat::whereIn('status', ['proses', 'revisi', 'revisi_admin'])->get();
foreach ($surats as $s) {
    echo "ID:{$s->id} | Tahap:{$s->tahap_sekarang} | Status:{$s->status} | {$s->judul}" . PHP_EOL;
}
echo "Total active surat: " . $surats->count() . PHP_EOL;

echo PHP_EOL . "=== ROLE FILTER SIMULATION ===" . PHP_EOL;
if ($user) {
    $role = $user->role;
    echo "Testing filter for role: {$role}" . PHP_EOL;
    
    if ($role === 'admin_aspirasi') {
        $filtered = Surat::whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->where(function ($sq) {
                $sq->where('tahap_sekarang', 2)->orWhere('tahap_sekarang', '>=', 5);
            })->get();
        echo "Filter: tahap_sekarang = 2 OR >= 5" . PHP_EOL;
    } elseif ($role === 'admin_kasubbag_tu') {
        $filtered = Surat::whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->where('tahap_sekarang', 3)->get();
        echo "Filter: tahap_sekarang = 3" . PHP_EOL;
    } elseif ($role === 'admin_kepala_balai') {
        $filtered = Surat::whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->where('tahap_sekarang', 4)->get();
        echo "Filter: tahap_sekarang = 4" . PHP_EOL;
    } else {
        // No filter applied - shows ALL surat (or possibly none due to how the closure works)
        $filtered = Surat::whereIn('status', ['proses', 'revisi', 'revisi_admin'])->get();
        echo "NO FILTER matched for role '{$role}' - roleFilter closure does nothing!" . PHP_EOL;
    }
    
    echo "Filtered results: " . $filtered->count() . PHP_EOL;
    foreach ($filtered as $s) {
        echo "  -> ID:{$s->id} | Tahap:{$s->tahap_sekarang} | Status:{$s->status} | {$s->judul}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== ALL ADMIN USERS ===" . PHP_EOL;
$admins = User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])->get();
foreach ($admins as $a) {
    echo "Name: {$a->name} | Role: {$a->role} | Role Selected: {$a->role_selected}" . PHP_EOL;
}
