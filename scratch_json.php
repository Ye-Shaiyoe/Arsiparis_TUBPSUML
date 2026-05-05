<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\Surat::with('user')->first();
$s->status_label = 'Test';
$s->sla_status = 'ok';
echo json_encode($s);
