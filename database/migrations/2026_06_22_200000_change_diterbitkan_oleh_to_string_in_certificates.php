<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE certificates ALTER COLUMN diterbitkan_oleh TYPE VARCHAR(200) USING diterbitkan_oleh::VARCHAR(200)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE certificates ALTER COLUMN diterbitkan_oleh TYPE BIGINT USING NULL');
    }
};
