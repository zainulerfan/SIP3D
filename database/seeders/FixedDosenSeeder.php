<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FixedDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user dosen terlebih dahulu
        $user = User::updateOrCreate(
            ['email' => 'dosen@gmail.com'], // email dosen

            [
                'name'     => 'Fathurahmani M.Kom',
                'password' => Hash::make('dosen123'), // password: dosen123
                'role'     => 'dosen',
            ]
        );

        // Buat data dosen di tabel dosens
        DB::table('dosens')->updateOrInsert(
            ['email' => 'dosen@gmail.com'],
            [
                'nidn'       => '123456789',
                'nama'       => 'Fathurahmani M.Kom',
                'email'      => 'dosen@gmail.com',
                'fakultas'   => 'Teknik',
                'prodi'      => 'Teknik Informatika',
                'jabatan'    => 'Dosen Tetap',
                'tahun'      => 2023,
                'status'     => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
