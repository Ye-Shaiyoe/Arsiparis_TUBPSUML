<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuratDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $user = \App\Models\User::first() ?: \App\Models\User::create([
            'name' => 'Dummy User',
            'email' => 'dummy@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $jenisSurat = array_keys(\App\Models\Surat::JENIS_LABEL);
        $totalData = 1000;

        $this->command->info("Sedang membuat {$totalData} data surat dummy...");

        for ($i = 0; $i < $totalData; $i++) {
            // Random date in last 6 months
            $date = \Carbon\Carbon::now()->subDays(rand(0, 180))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Random status distribution
            $randStatus = rand(1, 100);
            if ($randStatus <= 60) {
                $status = 'selesai';
                $tahap = 10;
            } elseif ($randStatus <= 85) {
                $status = 'proses';
                $tahap = rand(1, 9);
            } else {
                $status = rand(0, 1) ? 'revisi' : 'revisi_admin';
                $tahap = rand(1, 4);
            }

            \App\Models\Surat::create([
                'user_id' => $user->id,
                'judul' => $faker->sentence(rand(4, 8)),
                'jenis' => $jenisSurat[array_rand($jenisSurat)],
                'sifat' => ['biasa', 'segera', 'rahasia'][rand(0, 2)],
                'tujuan' => $faker->company,
                'file_word' => 'dummy.docx',
                'nomor_surat' => rand(100, 999) . '/MET/' . $date->format('Y'),
                'tanggal_surat' => $date,
                'tahap_sekarang' => $tahap,
                'status' => $status,
                'disetujui_pada' => ($status === 'selesai') ? $date->copy()->addDays(rand(1, 5)) : null,
                'rating' => ($status === 'selesai' && rand(1, 100) <= 70) ? rand(3, 5) : null,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            if (($i + 1) % 100 === 0) {
                $this->command->info("Progres: " . ($i + 1) . " surat selesai dibuat.");
            }
        }

        $this->command->info("Berhasil membuat 1000 data surat dummy!");
    }
}
