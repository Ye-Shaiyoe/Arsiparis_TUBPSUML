<?php
/**
 * Script untuk membersihkan file orphan yang tersimpan di storage/app/surat
 * File ini menggunakan path lama (sebelum perbaikan) yang tidak lagi digunakan
 * Jalankan: php cleanup_orphaned_files.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Storage;

echo "🧹 Membersihkan file orphan di storage/app/surat...\n";

$storageAppPath = storage_path('app/surat');

if (!is_dir($storageAppPath)) {
    echo "✅ Folder storage/app/surat tidak ada. Tidak ada file yang perlu dihapus.\n";
    exit;
}

$files = glob($storageAppPath . '/*');
$deletedCount = 0;
$totalSize = 0;

if (empty($files)) {
    echo "✅ Folder storage/app/surat kosong.\n";
    exit;
}

foreach ($files as $file) {
    if (is_file($file)) {
        $fileSize = filesize($file);
        $totalSize += $fileSize;
        
        $result = unlink($file);
        if ($result) {
            $deletedCount++;
            echo "  🗑 Dihapus: " . basename($file) . " (" . formatBytes($fileSize) . ")\n";
        } else {
            echo "  ⚠ Gagal menghapus: " . basename($file) . "\n";
        }
    }
}

echo "\n✅ Selesai! Dihapus $deletedCount file, total " . formatBytes($totalSize) . "\n";

function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}
