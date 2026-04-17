<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Surat;
use Illuminate\Support\Facades\DB;

$notifs = DB::table('notifications')->get();
foreach ($notifs as $notif) {
    $data = json_decode($notif->data, true);
    if (isset($data['surat_id']) && $data['surat_id'] == 39) {
        echo "Notification {$notif->id} URL: {$data['url']}\n";
    }
}
