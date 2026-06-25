<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('student_registrations', 'payment_status'))
                $table->string('payment_status')->default('belum_bayar')->after('status');
            if (!Schema::hasColumn('student_registrations', 'academic_status'))
                $table->string('academic_status')->default('pending')->after('payment_status');
            if (!Schema::hasColumn('student_registrations', 'assigned_teacher_id'))
                $table->unsignedBigInteger('assigned_teacher_id')->nullable()->after('academic_status');
            if (!Schema::hasColumn('student_registrations', 'biaya_per_sesi'))
                $table->decimal('biaya_per_sesi', 15, 2)->nullable()->after('assigned_teacher_id');
            if (!Schema::hasColumn('student_registrations', 'total_sessions'))
                $table->integer('total_sessions')->nullable()->after('biaya_per_sesi');
            if (!Schema::hasColumn('student_registrations', 'total_biaya'))
                $table->decimal('total_biaya', 15, 2)->nullable()->after('total_sessions');
            if (!Schema::hasColumn('student_registrations', 'invoice_id'))
                $table->unsignedBigInteger('invoice_id')->nullable()->after('total_biaya');
            if (!Schema::hasColumn('student_registrations', 'student_id'))
                $table->unsignedBigInteger('student_id')->nullable()->after('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status', 'academic_status', 'assigned_teacher_id',
                'biaya_per_sesi', 'total_sessions', 'total_biaya', 'invoice_id', 'student_id'
            ]);
        });
    }
};
