<?php

namespace App\Console\Commands;

use App\Models\Surat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupRejectedSurats extends Command
{
    protected $signature = 'surat:cleanup-rejected';

    protected $description = 'Hapus surat ditolak yang tidak direvisi lebih dari 5 hari (termasuk tracking)';

    public function handle(): int
    {
        $cutoff = now()->subDays(5);

        $surats = Surat::where('status', 'ditolak')
            ->where('updated_at', '<=', $cutoff)
            ->get();

        if ($surats->isEmpty()) {
            $this->info('✅ Tidak ada surat ditolak yang perlu dihapus.');
            return self::SUCCESS;
        }

        $deleted = 0;

        foreach ($surats as $surat) {
            if ($surat->file_word && Storage::disk('private')->exists($surat->file_word)) {
                Storage::disk('private')->delete($surat->file_word);
            }
            if ($surat->file_lampiran && Storage::disk('private')->exists($surat->file_lampiran)) {
                Storage::disk('private')->delete($surat->file_lampiran);
            }

            $this->line("  🗑 Surat #{$surat->id} — {$surat->judul}");
            $surat->delete();
            $deleted++;
        }

        $this->info("✅ {$deleted} surat ditolak kadaluarsa berhasil dihapus.");
        return self::SUCCESS;
    }
}
