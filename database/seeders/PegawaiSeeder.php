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
        1. Aris Kusnandar, S.T., M.T. | 198001102005021001
        2. Restiyana, S.E., M.Ak. | 198409272009012004
        3. Henry Gunawan, S.T., M.E. | 198007222005021001
        4. Anggia Anggraeni, S.Si., M.T. | 198111022006042003
        5. I Wayan Ariada, S.T. | 198003262006041013
        6. Syarif Aditya Budi P, S.E., M.M. | 198106302005021001
        7. Kumbara Zohar Taufik, S.Si., M.T. | 197610292006041003
        8. Mira Puspitasari, S.T., M.M. | 198306052008012011
        9. Siti Nurhayati, S.T. | 198308102010012028
        10. Apit Yuhana, S.Kom., M.M. | 197810142006041001
        11. Larisa Deviyani, S.Si., M.T. | 198109082006042002
        12. Wanda Darmawan, S.T., M.T. | 198404132012121001
        13. Nur Annisah, S.T., M.T. | 198105262008012011
        14. Machida Nurul Kholishoh, S.Si., M.Si. | 198811202012122001
        15. Anik Linawati, S.Si., M.Biotech | 198508242012122003
        16. Taufik Faturohman, S.S.T. | 198609222012121002
        17. Selvy Febriani Rahayu, S.T. | 198402242006042001
        18. Nurul Fatikhah, S.T. | 198112052008012015
        19. Angga Budianto, S.T. | 199001112012121002
        20. Luthfiana Asry Ayuni, S.Si. | 198305172012122001
        21. Lasbert Leonyus, S.E. | 198212112015021001
        22. Devi Anarianti, S.Si. | 199005222012122001
        23. Meylani Eka Putri, S.Ak | 199405052015022001
        24. Elnasari Ramadhan, S.Si. | 199502172022032006
        25. Fitrianto Nugroho, S.Si. | 199104222022031003
        26. Ersan Nur Bambang, S.T. | 198104142012121001
        27. Dwiky Eka Hastanto, S.T. | 199605032022031004
        28. Arini Shafia Afkari, S.Si., M.Si. | 199804152022032010
        29. Enqi Resha Kirana | 198506052006042001
        30. Mhd. Brian Awiruddin, S.T. | 200003172025051002
        31. Mahadevi Pramudyawardhani, S.Ak. | 200103202025052006
        32. Siti Zara Kania Audita, S.Si. | 200110112025052007
        33. Nana Rusmana, S.AP | 197801062025211016
        34. Erwin Winarno, S.T. | 198603082025211028
        35. Hamdan Setiana, S.E. | 197310142025211010
        36. Putri Silvi Pertiwi, A.Md., AK | 199811142024212015
        37. Zico Arief Febrianto, A.Md. | 198502152025211030
        38. Gunawan Bayu Suratno, A.Md. | 197510222025211011
        39. Allia Arfah, A.Md. | 199611262025212024
        40. Ade Aulia Rizky, A.Md. | 199610202025212034
        41. Adhika Pulung Pratama Wibawa, A.Md. | 199811292025211022
        42. Angga Langgara | 199606102025211028";

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
