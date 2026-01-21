<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user untuk mahasiswa terlebih dahulu
        $user = User::updateOrCreate(
            ['email' => 'mahasiswa1@example.com'],
            [
                'name' => 'Rida',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]
        );

        // Kemudian buat data mahasiswa dengan user_id
        DB::table('mahasiswas')->insert([
            'user_id' => $user->id,
            'nim' => '2023001',
            'nama' => 'Rida',
            'email' => 'mahasiswa1@example.com',
            'fakultas' => 'Teknik',
            'prodi' => 'Teknik Informatika',
            'angkatan' => 2023,
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
