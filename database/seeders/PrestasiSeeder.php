<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prestasi;

class PrestasiSeeder extends Seeder
{
    /**
     * Seed sample data for TPK (achievements/prestasi table).
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'D001',
                'nama' => 'Dr. Ahmad Fauzi, M.Kom',
                'skor_sinta' => 450,
                'skor_sinta_3yr' => 280,
                'jumlah_buku' => 3,
                'jumlah_hibah' => 5,
                'publikasi_scholar' => 12,
            ],
            [
                'code' => 'D002',
                'nama' => 'Prof. Siti Rahmawati, Ph.D',
                'skor_sinta' => 580,
                'skor_sinta_3yr' => 350,
                'jumlah_buku' => 5,
                'jumlah_hibah' => 8,
                'publikasi_scholar' => 20,
            ],
            [
                'code' => 'D003',
                'nama' => 'Dr. Budi Santoso, M.T',
                'skor_sinta' => 320,
                'skor_sinta_3yr' => 180,
                'jumlah_buku' => 2,
                'jumlah_hibah' => 3,
                'publikasi_scholar' => 8,
            ],
            [
                'code' => 'D004',
                'nama' => 'Ir. Dewi Kartika, M.Sc',
                'skor_sinta' => 280,
                'skor_sinta_3yr' => 150,
                'jumlah_buku' => 1,
                'jumlah_hibah' => 2,
                'publikasi_scholar' => 5,
            ],
            [
                'code' => 'D005',
                'nama' => 'Dr. Eko Prasetyo, S.T, M.Eng',
                'skor_sinta' => 400,
                'skor_sinta_3yr' => 220,
                'jumlah_buku' => 4,
                'jumlah_hibah' => 6,
                'publikasi_scholar' => 15,
            ],
        ];

        foreach ($data as $item) {
            Prestasi::firstOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
