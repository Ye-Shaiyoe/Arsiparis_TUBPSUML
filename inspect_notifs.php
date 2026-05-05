<?php
// inspect_notifs.php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$total = DB::table('notifications')->count();
echo "Total Notifications: $total\n";

$byUser = DB::table('notifications')
    ->select('notifiable_id', DB::raw('count(*) as total'))
    ->groupBy('notifiable_id')
    ->get();

echo "By User:\n";
foreach ($byUser as $row) {
    echo "User ID {$row->notifiable_id}: {$row->total} notifications\n";
}

$unread = DB::table('notifications')->whereNull('read_at')->count();
echo "Unread: $unread\n";
echo "Read: " . ($total - $unread) . "\n";
