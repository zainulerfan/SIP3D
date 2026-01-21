<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    /**
     * Seed the kriteria table with default TPK criteria.
     */
    public function run(): void
    {
        $kriterias = [
            [
                'nama_kriteria' => 'Skor SINTA',
                'bobot' => 0.2,
                'tipe' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Skor SINTA 3 Tahun',
                'bobot' => 0.2,
                'tipe' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Jumlah Buku',
                'bobot' => 0.2,
                'tipe' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Jumlah Hibah',
                'bobot' => 0.2,
                'tipe' => 'benefit',
            ],
            [
                'nama_kriteria' => 'Publikasi Scholar (1 Tahun)',
                'bobot' => 0.2,
                'tipe' => 'benefit',
            ],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::firstOrCreate(
                ['nama_kriteria' => $kriteria['nama_kriteria']],
                $kriteria
            );
        }
    }
}
