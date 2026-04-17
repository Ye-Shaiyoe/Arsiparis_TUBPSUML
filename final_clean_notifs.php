<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Surat;
use Illuminate\Support\Facades\DB;

$notifs = DB::table('notifications')->get();
$count = 0;

foreach ($notifs as $notif) {
    $data = json_decode($notif->data, true);
    if (!isset($data['url'])) continue;

    // Cari pola /surat/id atau /Admin/Surat/id
    // regex ini menangkap baik URL absolut maupun relatif
    if (preg_match('/(Admin\/Surat\/|surat\/)(\d+)$/', $data['url'], $matches)) {
        $id = $matches[2];
        $surat = Surat::find($id);
        
        if ($surat) {
            $prefix = $matches[1];
            // Ganti angka ID dengan UUID
            $newUrl = preg_replace('/(Admin\/Surat\/|surat\/)(\d+)$/', $prefix . $surat->uuid, $data['url']);
            
            $data['url'] = $newUrl;
            DB::table('notifications')->where('id', $notif->id)->update([
                'data' => json_encode($data)
            ]);
            echo "SUCCESS: Updated Notif {$notif->id} URL to UUID\n";
            $count++;
        }
    }
}

echo "\n--- SELESAI ---\n";
echo "Total notifikasi yang diperbaiki: $count\n";
