<?php

namespace App\Console\Commands;

use App\Models\Surat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedDbReferences extends Command
{
    protected $signature = 'surat:cleanup-orphaned-references';
    protected $description = 'Membersihkan database references yang menunjuk ke file path lama';

    public function handle()
    {
        $this->info('🔍 Mencari file references dengan path lama...');

        // Cari semua surat dengan file path yang tidak mengandung 'surat/word' atau 'surat/lampiran'
        $surats = Surat::query()
            ->where(function ($q) {
                $q->where('file_word', 'like', '%_edited_%')
                  ->orWhere('file_word', 'like', '%_lampiran_%')
                  ->orWhere('file_lampiran', 'like', '%_edited_%')
                  ->orWhere('file_lampiran', 'like', '%_word_%');
            })
            ->get();

        if ($surats->isEmpty()) {
            $this->info('✅ Tidak ada file dengan path lama.');
            return Command::SUCCESS;
        }

        $this->info('Ditemukan ' . $surats->count() . ' surat dengan file path lama:');
        $this->newLine();

        $fixedCount = 0;

        foreach ($surats as $surat) {
            // Cek file_word
            if ($surat->file_word && (strpos($surat->file_word, '_edited_') !== false || strpos($surat->file_word, 'surat/') === 0)) {
                if (!Storage::disk('private')->exists($surat->file_word)) {
                    $this->warn("❌ Surat #{$surat->id} ({$surat->judul}): file_word path lama & tidak ada");
                    $this->line("   Path: {$surat->file_word}");
                    
                    // Clear reference
                    $surat->update(['file_word' => null]);
                    $this->line('   ✓ Reference di-clear');
                    $this->newLine();
                    $fixedCount++;
                }
            }
            
            // Cek file_lampiran
            if ($surat->file_lampiran && (strpos($surat->file_lampiran, '_edited_') !== false || strpos($surat->file_lampiran, 'surat/') === 0)) {
                if (!Storage::disk('private')->exists($surat->file_lampiran)) {
                    $this->warn("❌ Surat #{$surat->id} ({$surat->judul}): file_lampiran path lama & tidak ada");
                    $this->line("   Path: {$surat->file_lampiran}");
                    
                    // Clear reference
                    $surat->update(['file_lampiran' => null]);
                    $this->line('   ✓ Reference di-clear');
                    $this->newLine();
                    $fixedCount++;
                }
            }
        }

        $this->info("✅ Selesai! Dibersihkan $fixedCount file reference.");
        return Command::SUCCESS;
    }
}
