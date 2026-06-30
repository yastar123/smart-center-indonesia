<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            // Per-subject freelance teacher honor (Rp per sesi): { "Matematika": 150000 }
            $table->jsonb('interest_teacher_honor')->nullable()->after('interest_teachers');
            // Per-subject total sessions the teacher will teach: { "Matematika": 8 }
            $table->jsonb('interest_teacher_sesi')->nullable()->after('interest_teacher_honor');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn(['interest_teacher_honor', 'interest_teacher_sesi']);
        });
    }
};
