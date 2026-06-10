<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('school_classes', 'jenis')) {
            DB::table('school_classes')->where('jenis', 'hybrid')->update(['jenis' => 'private']);
            DB::statement("ALTER TABLE school_classes MODIFY jenis ENUM('online','offline','private') NOT NULL DEFAULT 'offline'");
        }

        if (Schema::hasColumn('schedules', 'jenis')) {
            DB::table('schedules')->where('jenis', 'hybrid')->update(['jenis' => 'private']);
            DB::statement("ALTER TABLE schedules MODIFY jenis ENUM('online','offline','private') NOT NULL DEFAULT 'offline'");
        }

        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'target_teacher_ids')) {
                $table->json('target_teacher_ids')->nullable()->after('target');
            }
            if (!Schema::hasColumn('announcements', 'target_student_ids')) {
                $table->json('target_student_ids')->nullable()->after('target_teacher_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'target_student_ids')) {
                $table->dropColumn('target_student_ids');
            }
            if (Schema::hasColumn('announcements', 'target_teacher_ids')) {
                $table->dropColumn('target_teacher_ids');
            }
        });

        if (Schema::hasColumn('school_classes', 'jenis')) {
            DB::table('school_classes')->where('jenis', 'private')->update(['jenis' => 'offline']);
            DB::statement("ALTER TABLE school_classes MODIFY jenis ENUM('online','offline') NOT NULL DEFAULT 'offline'");
        }

        if (Schema::hasColumn('schedules', 'jenis')) {
            DB::table('schedules')->where('jenis', 'private')->update(['jenis' => 'offline']);
            DB::statement("ALTER TABLE schedules MODIFY jenis ENUM('online','offline') NOT NULL DEFAULT 'offline'");
        }
    }
};
