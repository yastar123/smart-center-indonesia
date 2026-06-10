<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_student_agreements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamp('guru_confirmed_at')->nullable();
            $table->timestamp('siswa_confirmed_at')->nullable();
            $table->enum('status', ['pending', 'agreed'])->default('pending');
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->unique(['schedule_id', 'student_id']);
        });

        Schema::table('student_course_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('student_course_payments', 'catatan')) {
                $table->text('catatan')->nullable()->after('proof');
            }
            if (! Schema::hasColumn('student_course_payments', 'rejected_reason')) {
                $table->text('rejected_reason')->nullable()->after('status');
            }
            if (! Schema::hasColumn('student_course_payments', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('rejected_reason');
                $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('student_course_payments', function (Blueprint $table) {
            if (Schema::hasColumn('student_course_payments', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('student_course_payments', 'rejected_reason')) {
                $table->dropColumn('rejected_reason');
            }
            if (Schema::hasColumn('student_course_payments', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });

        Schema::dropIfExists('schedule_student_agreements');
    }
};
