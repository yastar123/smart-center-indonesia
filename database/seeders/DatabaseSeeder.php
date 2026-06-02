<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    $this->call([
        RolePermissionSeeder::class,
        BranchSeeder::class,
                UserSeeder::class,
    ]);
}
}
