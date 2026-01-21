<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FixedAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // email admin

            // data yang akan di-set
            [
                'name'     => 'Bu Admin',
                'password' => Hash::make('admin123'), // password: admin123
                'role'     => 'admin',
            ]
        );
    }
}
