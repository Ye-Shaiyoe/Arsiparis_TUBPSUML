<?php
/**
 * Script untuk clean up file references yang orphaned
 * Jalankan dari: php fix_orphaned_files.php
 */

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Surat;
use Illuminate\Support\Facades\Storage;

echo "=== Cleaning up orphaned file references ===\n\n";

$surats = Surat::whereNotNull('file_word')
    ->orWhere(function ($q) {
        $q->whereNotNull('file_lampiran');
    })
    ->get();

$fixed_count = 0;
$total_checked = 0;

foreach ($surats as $surat) {
    $total_checked++;
    
    // Check file_word
    if ($surat->file_word && !Storage::disk('private')->exists($surat->file_word)) {
        echo "[FIXED] Surat #{$surat->id} - Removed orphaned file_word reference: {$surat->file_word}\n";
        $surat->update(['file_word' => null]);
        $fixed_count++;
    }
    
    // Check file_lampiran
    if ($surat->file_lampiran && !Storage::disk('private')->exists($surat->file_lampiran)) {
        echo "[FIXED] Surat #{$surat->id} - Removed orphaned file_lampiran reference: {$surat->file_lampiran}\n";
        $surat->update(['file_lampiran' => null]);
        $fixed_count++;
    }
}

echo "\n=== Summary ===\n";
echo "Total surat checked: {$total_checked}\n";
echo "Orphaned references fixed: {$fixed_count}\n";
echo "\nDone!\n";
