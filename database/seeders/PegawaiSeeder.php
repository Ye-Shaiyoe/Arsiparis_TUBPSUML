<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataRaw = "
        1. Username  | NIP
        ";

        $lines = explode("\n", $dataRaw);
        $count = 1;

        foreach ($lines as $line) {
            // Hilangkan nomor urut di awal (misal "1. ")
            $cleaned = preg_replace('/^\d+\.\s+/', '', trim($line));
            
            // Split Nama dan NIP
            $parts = explode('|', $cleaned);
            if (count($parts) < 2) continue;

            $nama = trim($parts[0]);
            $nip = trim($parts[1]);

            // Format email: users01, users02, dst
            $emailNum = str_pad($count, 2, '0', STR_PAD_LEFT);
            $email = "users{$emailNum}@gmail.com";

            User::updateOrCreate(
                ['nip' => $nip], // Cek berdasarkan NIP agar tidak duplikat
                [
                    'name'     => $nama,
                    'email'    => $email,
                    'password' => Hash::make('12345678910'), // Password default seragam
                    'role'     => 'user',
                ]
            );

            $count++;
        }

        $this->command->info("Berhasil mendaftarkan " . ($count - 1) . " pegawai.");
    }
}
