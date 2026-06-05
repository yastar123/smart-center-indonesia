<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // OWNER
        // =========================
        $owner = User::updateOrCreate(
            [
                'email' => 'adminpusatsci@akademi.com'
            ],
            [
                'name' => 'Admin Pusat SCI',
                'password' => Hash::make('password'),
            ]
        );

        $owner->assignRole('owner');

        // =========================
        // ADMIN CABANG
        // =========================
        $admin = User::updateOrCreate(
            [
                'email' => 'admincabangsci@akademi.com'
            ],
            [
                'name' => 'Admin Cabang SCI',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('admin');

        // =========================
        // GURU
        // =========================
        $guru = User::updateOrCreate(
            [
                'email' => 'guru@akademipro.com'
            ],
            [
                'name' => 'Guru Akademi',
                'password' => Hash::make('password123'),
            ]
        );

        $guru->assignRole('guru');

        // =========================
        // SISWA / USER
        // =========================
        $siswa = User::updateOrCreate(
            [
                'email' => 'siswa@akademipro.com'
            ],
            [
                'name' => 'Siswa Akademi',
                'password' => Hash::make('password123'),
            ]
        );

        $siswa->assignRole('siswa');
    }
}