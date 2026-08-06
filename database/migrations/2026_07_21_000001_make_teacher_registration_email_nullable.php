<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('teacher_registrations', 'email')) {
            return;
        }

        if (Config::get('database.default') === 'mysql') {
            DB::statement('ALTER TABLE teacher_registrations MODIFY email VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Config::get('database.default') === 'mysql') {
            DB::table('teacher_registrations')->whereNull('email')->update(['email' => '']);
            DB::statement('ALTER TABLE teacher_registrations MODIFY email VARCHAR(255) NOT NULL');
        }
    }
};
