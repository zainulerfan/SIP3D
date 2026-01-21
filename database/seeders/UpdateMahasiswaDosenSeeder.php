<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class UpdateMahasiswaDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first dosen
        $dosen = Dosen::first();
        
        if ($dosen) {
            // Update all mahasiswa to have dosen_id
            Mahasiswa::whereNull('dosen_id')->update(['dosen_id' => $dosen->id]);
            
            $this->command->info('Mahasiswa updated with dosen_id');
        } else {
            $this->command->warn('No dosen found. Please create dosen first.');
        }
    }
}
