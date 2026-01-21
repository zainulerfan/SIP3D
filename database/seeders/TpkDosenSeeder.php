<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;

class TpkDosenSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = [
            ['Fathurrahmani, M.Kom', 972, 463, 6, 3, 13],
            ['Winda Aprianti, M.Si', 645, 332, 4, 2, 3],
            ['Veri Julianto, M.Si', 602, 300, 4, 2, 10],
            ['Ir. Agustian Noor, M.Kom', 732, 274, 4, 2, 13],
            ['Herfia Romadhona, S.Kom., M.Kom', 511, 190, 3, 1, 6],
            ['Jaka Permadi, S.Si., M.Cs', 366, 167, 2, 1, 3],
            ['M. Najamudin Ridha, S.Kom., M.Kom', 98, 88, 1, 1, 7],
            ['Mamed Rofendi Manalu, M.Kom', 179, 63, 1, 1, 7],
            ['Oky Rahmanto, S.Kom., M.T', 75, 62, 2, 1, 7],
            ['Aidil Fajar Zulfahri, S.Kom., M.Kom', 51, 47, 1, 1, 1],
            ['Nina Mia Aristi, M.Kom', 58, 43, 4, 1, 1],
            ['Billy Sabella, S.Kom., M.Kom', 100, 42, 3, 1, 1],
            ['Wiwik Kusrini, S.Kom., M.Cs', 178, 29, 1, 1, 1],
            ['Dwi Agung Wibowo, M.Kom', 103, 22, 1, 1, 1],
            ['Muhammad Reza Riansyah, M.Kom', 20, 13, 1, 1, 2],
            ['Miftahul Rahmi, M.Kom', 13, 12, 2, 2, 1],
            ['Rabini Sayyidati, M.Pd', 42, 10, 1, 3, 2],
            ['Zaenul Mutaqin, M.M.S.I', 9, 9, 1, 4, 1],
            ['Muhammad Noor, M.H.I', 127, 8, 1, 5, 1],
            ['Yunita Prastyaningsih, M.Kom', 10, 6, 3, 3, 1],
            ['Afian Syafaadi Rizki, M.Kom', 8, 1, 2, 1, 1],
            ['Nindy Permatasari, S. Kom., M.Kom', 68, 80, 1, 1, 1],
            ['Khairul Anwar Hafizd, M.Kom', 92, 110, 1, 1, 2],
            ['Cahya Karima, M.Kom', 41, 20, 1, 1, 3],
            ['Susan Hidayah Nova, S.Kom., M.Kom', 59, 40, 1, 1, 4],
        ];

        foreach ($dosens as $index => $data) {
            $nama = $data[0];
            // Buat email dummy dari nama
            $email = strtolower(str_replace(['.', ',', ' '], ['', '', ''], explode(',', $nama)[0])) . '@example.com';

            Dosen::updateOrCreate(
                ['nama' => $nama],
                [
                    'email' => $email,
                    'nidn' => '1000' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'fakultas' => 'Teknik',
                    'prodi' => 'Teknik Informatika',
                    'jabatan' => 'Dosen',
                    'tahun' => date('Y'),
                    'status' => 'Aktif',
                    'skor_sinta' => $data[1],
                    'skor_sinta_3yr' => $data[2],
                    'jumlah_buku' => $data[3],
                    'jumlah_hibah' => $data[4],
                    'publikasi_scholar' => $data[5],
                ]
            );
        }
    }
}
