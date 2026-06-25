<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_course_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unique(['package_id', 'course_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_course_teachers');
    }
};
