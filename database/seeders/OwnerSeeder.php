<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::updateOrCreate(
            ['email' => 'adminpusatsci@akademi.com'],
            [
                'name'     => 'Admin Pusat SCI',
                'password' => Hash::make('password'),
            ]
        );

        $owner->assignRole('owner');
    }
}
