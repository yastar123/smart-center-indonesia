<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $foreignKey = DB::selectOne("SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'rooms'
              AND COLUMN_NAME = 'branch_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL");

        if ($foreignKey && !empty($foreignKey->CONSTRAINT_NAME)) {
            DB::statement('ALTER TABLE rooms DROP FOREIGN KEY ' . $foreignKey->CONSTRAINT_NAME);
        }

        DB::statement('ALTER TABLE rooms MODIFY COLUMN branch_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE rooms MODIFY COLUMN branch_id BIGINT UNSIGNED NOT NULL');
    }
};
