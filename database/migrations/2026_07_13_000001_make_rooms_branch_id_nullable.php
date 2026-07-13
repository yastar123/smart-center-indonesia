<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE rooms DROP CONSTRAINT IF EXISTS rooms_branch_id_foreign');
        DB::statement('ALTER TABLE rooms ALTER COLUMN branch_id DROP NOT NULL');
    }
    public function down(): void
    {
        DB::statement('ALTER TABLE rooms ALTER COLUMN branch_id SET NOT NULL');
    }
};
