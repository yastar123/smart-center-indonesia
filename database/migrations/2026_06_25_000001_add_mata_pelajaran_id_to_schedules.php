<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'mata_pelajaran_id')) {
                $table->foreignId('mata_pelajaran_id')
                      ->nullable()
                      ->constrained('courses')
                      ->nullOnDelete()
                      ->after('paket_id');
            }
        });

        // Fix jenis constraint to include 'private'
        DB::statement("ALTER TABLE schedules DROP CONSTRAINT IF EXISTS schedules_jenis_check");
        DB::statement("ALTER TABLE schedules ADD CONSTRAINT schedules_jenis_check CHECK (jenis IN ('online', 'offline', 'private'))");
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
        });
        DB::statement("ALTER TABLE schedules DROP CONSTRAINT IF EXISTS schedules_jenis_check");
        DB::statement("ALTER TABLE schedules ADD CONSTRAINT schedules_jenis_check CHECK (jenis IN ('online', 'offline'))");
    }
};
