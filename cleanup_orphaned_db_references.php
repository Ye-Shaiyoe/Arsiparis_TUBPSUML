<?php
/**
 * Script untuk membersihkan database references yang menunjuk ke file path lama
 * Path lama: surat/...uuid..._word_edited_...docx
 * Path baru: surat/word/...docx atau surat/lampiran/...docx
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\Surat;
use Illuminate\Support\Facades\Storage;

echo "🔍 Mencari file references dengan path lama...\n\n";

// Cari semua surat dengan file path yang tidak mengandung 'surat/word' atau 'surat/lampiran'
$surats = Surat::query()
    ->where(function($q) {
        $q->where('file_word', 'like', '%_edited_%')
          ->orWhere('file_word', 'like', '%_lampiran_%')
          ->orWhere('file_lampiran', 'like', '%_edited_%')
          ->orWhere('file_lampiran', 'like', '%_word_%');
    })
    ->get();

if ($surats->isEmpty()) {
    echo "✅ Tidak ada file dengan path lama.\n";
    exit;
}

echo "Ditemukan " . $surats->count() . " surat dengan file path lama:\n\n";

$fixedCount = 0;

foreach ($surats as $surat) {
    // Cek file_word
    if ($surat->file_word && (strpos($surat->file_word, '_edited_') !== false || strpos($surat->file_word, 'surat/') === 0)) {
        if (!Storage::disk('private')->exists($surat->file_word)) {
            echo "  ❌ Surat #{$surat->id} ({$surat->judul}): file_word path lama & tidak ada\n";
            echo "     Path: {$surat->file_word}\n";
            
            // Clear reference
            $surat->update(['file_word' => null]);
            echo "     ✓ Reference di-clear\n\n";
            $fixedCount++;
        }
    }
    
    // Cek file_lampiran
    if ($surat->file_lampiran && (strpos($surat->file_lampiran, '_edited_') !== false || strpos($surat->file_lampiran, 'surat/') === 0)) {
        if (!Storage::disk('private')->exists($surat->file_lampiran)) {
            echo "  ❌ Surat #{$surat->id} ({$surat->judul}): file_lampiran path lama & tidak ada\n";
            echo "     Path: {$surat->file_lampiran}\n";
            
            // Clear reference
            $surat->update(['file_lampiran' => null]);
            echo "     ✓ Reference di-clear\n\n";
            $fixedCount++;
        }
    }
}

echo "✅ Selesai! Dibersihkan $fixedCount file reference.\n";
