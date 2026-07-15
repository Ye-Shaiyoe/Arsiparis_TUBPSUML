<?php
// Test what @json($antrian) produces
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Surat;
use App\Models\User;

$admin = User::where('name', 'bakka')->first();

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

$workloadQuery = Surat::query()->where($roleFilter);

$antrian = (clone $workloadQuery)
    ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
    ->with('user')
    ->orderByRaw("CASE WHEN status IN ('revisi', 'revisi_admin') THEN 0 ELSE 1 END")
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get()
    ->map(function ($s) {
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

$antrianCount = $antrian->count();

echo "=== RAW @json(\$antrian) output ===" . PHP_EOL;
echo json_encode($antrian) . PHP_EOL;

echo PHP_EOL . "=== Type of \$antrian ===" . PHP_EOL;
echo get_class($antrian) . PHP_EOL;

echo PHP_EOL . "=== Count: {$antrianCount} ===" . PHP_EOL;

echo PHP_EOL . "=== Simulated x-data ===" . PHP_EOL;
$xdata = "antrian: { items: " . json_encode($antrian) . ", count: {$antrianCount} }";
echo $xdata . PHP_EOL;

echo PHP_EOL . "=== Check for special chars that could break JS ===" . PHP_EOL;
$json = json_encode($antrian);
if (strpos($json, "'") !== false) echo "Contains single quotes!" . PHP_EOL;
if (strpos($json, "\n") !== false) echo "Contains newlines!" . PHP_EOL;
if (strpos($json, "</script>") !== false) echo "Contains script tags!" . PHP_EOL;
echo "JSON is valid: " . (json_last_error() === JSON_ERROR_NONE ? 'YES' : 'NO') . PHP_EOL;
echo "JSON length: " . strlen($json) . " chars" . PHP_EOL;
