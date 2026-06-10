<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pivot siswa – guru
        if (!Schema::hasTable('student_teachers')) {
            Schema::create('student_teachers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['student_id', 'teacher_id']);
            });
        }

        // 2. school_classes: tambah jumlah_pertemuan, hapus ruangan
        Schema::table('school_classes', function (Blueprint $table) {
            if (!Schema::hasColumn('school_classes', 'jumlah_pertemuan')) {
                $table->unsignedSmallInteger('jumlah_pertemuan')->default(1)->after('kapasitas');
            }
            if (Schema::hasColumn('school_classes', 'ruangan')) {
                $table->dropColumn('ruangan');
            }
        });

        // 3. schedules: tambah pertemuan_ke dan tanggal_selesai
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'pertemuan_ke')) {
                $table->unsignedSmallInteger('pertemuan_ke')->nullable()->after('cabang_id');
            }
            if (!Schema::hasColumn('schedules', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'tanggal_selesai')) {
                $table->dropColumn('tanggal_selesai');
            }
            if (Schema::hasColumn('schedules', 'pertemuan_ke')) {
                $table->dropColumn('pertemuan_ke');
            }
        });

        Schema::table('school_classes', function (Blueprint $table) {
            if (!Schema::hasColumn('school_classes', 'ruangan')) {
                $table->string('ruangan')->nullable()->after('jumlah_pertemuan');
            }
            if (Schema::hasColumn('school_classes', 'jumlah_pertemuan')) {
                $table->dropColumn('jumlah_pertemuan');
            }
        });

        Schema::dropIfExists('student_teachers');
    }
};
