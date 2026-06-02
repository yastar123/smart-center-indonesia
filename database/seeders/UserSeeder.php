<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // OWNER
        $owner = User::firstOrCreate(
            ['email' => 'ownersci@gmail.com'],
            [
                'name' => 'Owner SCI',
                'password' => Hash::make('password123'),
            ]
        );
        $owner->assignRole('owner');

        // ADMIN CABANG
        $admin = User::firstOrCreate(
            ['email' => 'adminsci@gmail.com'],
            [
                'name' => 'Admin Cabang SCI',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('admin');

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