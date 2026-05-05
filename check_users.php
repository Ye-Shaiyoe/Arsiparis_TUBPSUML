<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$userIds = [12, 75, 76, 5];
foreach ($userIds as $id) {
    $user = User::find($id);
    if ($user) {
        echo "User ID $id: {$user->name} ({$user->role})\n";
    } else {
        echo "User ID $id: Not found\n";
    }
}
