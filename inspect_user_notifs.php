<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$notifs = DB::table('notifications')->get();
foreach ($notifs as $n) {
    if (strpos($n->data, 'surat/') !== false && strpos($n->data, 'Admin/Surat/') === false) {
        echo "ID: {$n->id}\n";
        echo "DATA: {$n->data}\n";
        echo "-------------------\n";
    }
}
