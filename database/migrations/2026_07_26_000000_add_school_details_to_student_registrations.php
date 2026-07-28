<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('student_registrations', 'school_name')) {
                $table->string('school_name')->nullable()->after('parent_phone');
            }
            if (! Schema::hasColumn('student_registrations', 'grade')) {
                $table->string('grade')->nullable()->after('school_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('student_registrations', 'grade')) {
                $table->dropColumn('grade');
            }
            if (Schema::hasColumn('student_registrations', 'school_name')) {
                $table->dropColumn('school_name');
            }
        });
    }
};
