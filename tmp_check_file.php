<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$s = \App\Models\Surat::latest()->first();
if (!$s) { echo "No surat found.\n"; exit; }

$disk = \Illuminate\Support\Facades\Storage::disk('public');
$path = $s->file_word;

echo "judul      : " . $s->judul . "\n";
echo "file_word  : " . $path . "\n";
echo "exists     : " . ($disk->exists($path) ? 'YES' : 'NO') . "\n";

if ($disk->exists($path)) {
    $fullPath = $disk->path($path);
    echo "size       : " . filesize($fullPath) . " bytes\n";
    echo "ext        : " . strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) . "\n";

    $fp = fopen($fullPath, 'rb');
    $bytes = fread($fp, 8);
    fclose($fp);

    $hex = bin2hex($bytes);
    echo "magic_hex  : " . $hex . "\n";

    if (substr($hex, 0, 8) === '504b0304') {
        echo "format     : VALID .docx (ZIP/OOXML) ✅\n";
    } elseif (substr($hex, 0, 8) === 'd0cf11e0') {
        echo "format     : .doc lama (OLE2/Word97) ⚠️\n";
    } elseif (substr($hex, 0, 6) === 'efbbbf') {
        echo "format     : ❌ Ada BOM UTF-8 di awal file!\n";
    } else {
        echo "format     : ❓ Unknown — hex pertama: " . $hex . "\n";
    }

    // Salin file ke public sementara untuk tes direct download
    copy($fullPath, __DIR__.'/public/test_download.docx');
    echo "test_url   : http://localhost/Surat-Metrologi/public/test_download.docx\n";
    echo "\nCoba download dari URL di atas dan bandingkan hasilnya.\n";
}
