<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            OwnerSeeder::class,
            DemoDataSeeder::class,
            PackageSeeder::class,
            AdminDataSeeder::class,
            SubjectSeeder::class,
        ]);
    }
}
