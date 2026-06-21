<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'adminpusatsci@akademi.com')->first();

        if (! $owner) {
            $owner = new User();
            $owner->email = 'adminpusatsci@akademi.com';
        }

        $owner->forceFill([
            'name' => 'Admin Pusat SCI',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $owner->save();

        if (method_exists($owner, 'syncRoles')) {
            $owner->syncRoles(['owner']);
        }
    }
}
