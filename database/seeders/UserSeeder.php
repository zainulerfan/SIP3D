<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ADMIN
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // NOTE: Tabel 'admins' tampaknya legacy/terpisah dan tidak punya user_id.
        // Jadi kita cukup buat User dengan role 'admin' saja.


        // 2. DOSEN
        $dosen = User::firstOrCreate([
            'email' => 'dosen@gmail.com'
        ], [
            'name' => 'Bapak Dosen',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        // Buat data di tabel dosens jika belum ada
        // NOTE: Tabel dosens tidak punya user_id, jadi kita match by email
        Dosen::firstOrCreate([
            'email' => 'dosen@gmail.com'
        ], [
            'nama' => 'Fathurahmani M.Kom',
            'nidn' => '1234567890',
            'jabatan' => 'Lektor',
            'fakultas' => 'Teknik',
            'prodi' => 'Informatika',
            'tahun' => '2024'
        ]);

        // 3. MAHASISWA
        $mahasiswa = User::firstOrCreate([
            'email' => 'mahasiswa@gmail.com'
        ], [
            'name' => 'Rida',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        // Buat data di tabel mahasiswas jika belum ada
        Mahasiswa::firstOrCreate([
            'user_id' => $mahasiswa->id
        ], [
            'nama' => 'Rida',
            'email' => 'mahasiswa@gmail.com',
            'nim' => '2401301001',
            'fakultas' => 'Ilmu Komputer',
            'prodi' => 'Teknik Informatika',
            'angkatan' => '2024',
            'status' => 'Aktif',
        ]);

        $this->command->info('✅ Default Users Created Successfully!');
    }
}
