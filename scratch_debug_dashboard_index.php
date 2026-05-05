<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/Admin/Dashboard/live-data?bulan=5&tahun=2026', 'GET');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');

$admin = App\Models\User::where('role', 'admin_aspirasi')->first();
Illuminate\Support\Facades\Auth::login($admin);

$controller = new App\Http\Controllers\Admin\DashboardController();
$response = $controller->index($request);

echo $response->getContent();
