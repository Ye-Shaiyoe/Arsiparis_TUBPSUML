<?php

namespace App\Console\Commands;

use App\Models\Surat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredFiles extends Command
{
    protected $signature = 'files:cleanup-expired';

    protected $description = 'Hapus file surat yang sudah kadaluarsa (3 hari setelah persetujuan)';

    public function handle()
    {
        $now = now();
        
        // Cari surat yang sudah disetujui dan file_expires_at sudah lewat
        $expiredSurats = Surat::whereNotNull('file_expires_at')
            ->whereNull('file_dihapus_pada')
            ->where('file_expires_at', '<=', $now)
            ->get();

        if ($expiredSurats->isEmpty()) {
            $this->info('✅ Tidak ada file yang perlu dihapus.');
            return Command::SUCCESS;
        }

        $deletedCount = 0;

        foreach ($expiredSurats as $surat) {
            // Hapus file word
            if ($surat->file_word && Storage::disk('local')->exists($surat->file_word)) {
                Storage::disk('local')->delete($surat->file_word);
                $this->line("  🗑 Menghapus: {$surat->file_word}");
            }

            // Hapus file lampiran
            if ($surat->file_lampiran && Storage::disk('local')->exists($surat->file_lampiran)) {
                Storage::disk('local')->delete($surat->file_lampiran);
                $this->line("  🗑 Menghapus: {$surat->file_lampiran}");
            }

            // Tandai file sudah dihapus
            $surat->update(['file_dihapus_pada' => $now]);
            $deletedCount++;
            
            $this->warn("  ✓ Surat '{$surat->judul}' - file dihapus");
        }

        $this->info("✅ Selesai! {$deletedCount} surat telah dibersihkan filenya.");
        
        return Command::SUCCESS;
    }
}
