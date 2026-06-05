<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // GURU
        $guru = User::firstOrCreate(
            ['email' => 'gurusci@gmail.com'],
            [
                'name' => 'Guru SCI',
                'password' => Hash::make('password123'),
            ]
        );
        $guru->assignRole('guru');

        // SISWA
        $siswa = User::firstOrCreate(
            ['email' => 'siswasci@gmail.com'],
            [
                'name' => 'Siswa SCI',
                'password' => Hash::make('password12'),
            ]
        );
        $siswa->assignRole('siswa');
    }
}   