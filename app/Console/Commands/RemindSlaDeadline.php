<?php

namespace App\Console\Commands;

use App\Models\Surat;
use App\Models\User;
use App\Notifications\SlaDeadlineReminderNotification;
use Illuminate\Console\Command;

class RemindSlaDeadline extends Command
{
    protected $signature = 'surat:remind-sla';

    protected $description = 'Kirim notifikasi ke admin untuk surat yang SLA-nya hampir habis atau sudah terlewat';

    /**
     * Ambang waktu pengingat.
     * Notifikasi dikirim jika sisa SLA <= JAM_PERINGATAN jam.
     * Notifikasi "terlewat" dikirim jika sudah melewati deadline <= JAM_MAKS_TERLAMBAT jam
     * (lebih dari itu dianggap sudah ditangani / notif sebelumnya sudah cukup).
     */
    const JAM_PERINGATAN    = 6;   // Kirim peringatan jika sisa <= 6 jam
    const JAM_MAKS_TERLAMBAT = 24; // Jangan spam notif untuk yg sudah terlambat > 24 jam

    public function handle(): int
    {
        // Ambil semua surat yang masih aktif (bukan draft/selesai/ditolak)
        // dan memiliki deadline_sla
        $surats = Surat::whereNotNull('deadline_sla')
            ->whereNotIn('status', ['draft', 'selesai', 'ditolak'])
            ->whereNull('alasan_keterlambatan') // belum ada alasan = belum ditangani
            ->get();

        if ($surats->isEmpty()) {
            $this->info('✅ Tidak ada surat yang perlu diingatkan.');
            return Command::SUCCESS;
        }

        // Kumpulkan admin yang relevan (semua role admin)
        $admins = User::whereIn('role', [
            'admin',
            'admin_aspirasi',
            'admin_kasubbag_tu',
            'admin_kepala_balai',
        ])->get();

        if ($admins->isEmpty()) {
            $this->warn('⚠ Tidak ada admin yang terdaftar untuk menerima notifikasi.');
            return Command::SUCCESS;
        }

        $kirimMendekati = 0;
        $kirimTerlambat = 0;

        foreach ($surats as $surat) {
            $now        = now();
            $deadline   = $surat->deadline_sla;
            $sisaJam    = $now->diffInHours($deadline, false); // negatif = sudah lewat
            $sudahLewat = $sisaJam < 0;

            // Filter admin yang berwenang di tahap surat ini
            $adminTarget = $admins->filter(
                fn(User $admin) => $admin->canApproveTahap($surat->tahap_sekarang)
            );

            // Jika tidak ada admin spesifik untuk tahap ini, broadcast ke semua admin
            if ($adminTarget->isEmpty()) {
                $adminTarget = $admins;
            }

            if ($sudahLewat) {
                $jamTerlambat = abs($sisaJam);

                // Hanya notif jika terlambat <= 24 jam (hindari spam untuk kasus lama)
                if ($jamTerlambat > self::JAM_MAKS_TERLAMBAT) {
                    $this->line("  ⏭ Skip (terlambat > " . self::JAM_MAKS_TERLAMBAT . "j): {$surat->judul}");
                    continue;
                }

                // Cek apakah sudah ada notif "terlewat" untuk surat ini hari ini
                // (hindari kirim ulang setiap 30 menit)
                $sudahDinotif = $adminTarget->first()?->notifications()
                    ->where('type', SlaDeadlineReminderNotification::class)
                    ->where('created_at', '>=', now()->startOfDay())
                    ->whereJsonContains('data->surat_id', $surat->id)
                    ->whereJsonContains('data->type', 'danger')
                    ->exists();

                if ($sudahDinotif) {
                    $this->line("  ⏭ Skip (sudah dinotif hari ini): {$surat->judul}");
                    continue;
                }

                $adminTarget->each(fn(User $admin) => $admin->notify(
                    new SlaDeadlineReminderNotification($surat, 'terlewat', $jamTerlambat)
                ));

                $this->warn("  🔴 Terlewat {$jamTerlambat}j: {$surat->judul}");
                $kirimTerlambat++;

            } elseif ($sisaJam <= self::JAM_PERINGATAN) {
                // Sisa <= 6 jam — kirim peringatan

                // Cek apakah sudah ada notif "mendekati" untuk surat ini dalam 3 jam terakhir
                // (hindari duplikat jika command jalan tiap 30 menit)
                $sudahDinotif = $adminTarget->first()?->notifications()
                    ->where('type', SlaDeadlineReminderNotification::class)
                    ->where('created_at', '>=', now()->subHours(3))
                    ->whereJsonContains('data->surat_id', $surat->id)
                    ->whereJsonContains('data->type', 'warning')
                    ->exists();

                if ($sudahDinotif) {
                    $this->line("  ⏭ Skip (sudah dinotif 3j terakhir): {$surat->judul}");
                    continue;
                }

                $adminTarget->each(fn(User $admin) => $admin->notify(
                    new SlaDeadlineReminderNotification($surat, 'mendekati', $sisaJam)
                ));

                $this->info("  🟡 Sisa {$sisaJam}j: {$surat->judul}");
                $kirimMendekati++;
            }
        }

        $this->newLine();
        $this->info("✅ Selesai — Mendekati: {$kirimMendekati}, Terlewat: {$kirimTerlambat}");

        return Command::SUCCESS;
    }
}
