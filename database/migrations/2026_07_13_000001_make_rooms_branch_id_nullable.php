<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE rooms DROP FOREIGN KEY IF EXISTS rooms_branch_id_foreign');
        DB::statement('ALTER TABLE rooms MODIFY COLUMN branch_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE rooms MODIFY COLUMN branch_id BIGINT UNSIGNED NOT NULL');
    }
};
