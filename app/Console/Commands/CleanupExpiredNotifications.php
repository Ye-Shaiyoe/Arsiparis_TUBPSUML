<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus notifikasi yang sudah lebih dari 7 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = 7;
        $date = now()->subDays($days);

        $this->info("🧹 Memulai pembersihan notifikasi yang lebih lama dari {$days} hari ({$date->toDateTimeString()})...");

        $count = DB::table('notifications')
            ->where('created_at', '<', $date)
            ->delete();

        if ($count > 0) {
            $this->info("✅ Berhasil menghapus {$count} notifikasi lama.");
        } else {
            $this->info("✨ Tidak ada notifikasi lama yang perlu dihapus.");
        }

        return Command::SUCCESS;
    }
}
