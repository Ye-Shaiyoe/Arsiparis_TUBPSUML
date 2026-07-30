<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data pegawai dibaca dari file CSV yang TIDAK di-commit ke Git.
     * Salin file template: database/seeders/data/pegawai.csv.example
     * Rename menjadi:      database/seeders/data/pegawai.csv
     * Isi dengan data NIP pegawai yang sebenarnya.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/pegawai.csv');

        if (! file_exists($csvPath)) {
            $this->command->error('File data tidak ditemukan: database/seeders/data/pegawai.csv');
            $this->command->warn('Salin file pegawai.csv.example, rename menjadi pegawai.csv, lalu isi datanya.');
            return;
        }

        $handle = fopen($csvPath, 'r');

        // Lewati baris header (nama,nip)
        fgetcsv($handle);

        $count = 1;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $nama = trim($row[0]);
            $nip  = trim($row[1]);

            if (empty($nama) || empty($nip)) continue;

            $emailNum = str_pad($count, 2, '0', STR_PAD_LEFT);
            $email    = "users{$emailNum}@gmail.com";

            $existing = User::findByNip($nip);

            if ($existing) {
                $existing->update([
                    'name'     => $nama,
                    'email'    => $email,
                    'role'     => 'user',
                    'nip'      => $nip,
                    'nip_hash' => User::hashNip($nip),
                ]);
                if (empty($existing->uuid)) {
                    $existing->uuid = Str::uuid();
                    $existing->saveQuietly();
                }
            } else {
                User::create([
                    'uuid'     => Str::uuid(),
                    'name'     => $nama,
                    'email'    => $email,
                    'nip'      => $nip,
                    'nip_hash' => User::hashNip($nip),
                    'password' => Hash::make('bps12345'),
                    'role'     => 'user',
                ]);
            }

            $count++;
        }

        fclose($handle);

        $this->command->info("Berhasil mendaftarkan " . ($count - 1) . " pegawai.");
    }
}
