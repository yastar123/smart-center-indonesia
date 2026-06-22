<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Config::get('database.default');

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE certificates ALTER COLUMN diterbitkan_oleh TYPE VARCHAR(200) USING diterbitkan_oleh::VARCHAR(200)');
        } else {
            DB::statement('ALTER TABLE certificates MODIFY diterbitkan_oleh VARCHAR(200)');
        }
    }

    public function down(): void
    {
        $driver = Config::get('database.default');

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE certificates ALTER COLUMN diterbitkan_oleh TYPE BIGINT USING NULL');
        } else {
            DB::statement('ALTER TABLE certificates MODIFY diterbitkan_oleh BIGINT');
        }
    }
};
