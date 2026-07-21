<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg')->unique()->nullable();
            $table->string('name');
            $table->string('nig')->unique();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('education')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('branch')->nullable();
            $table->text('address')->nullable();
            $table->enum('jenis_guru', ['kontrak', 'freelance'])->nullable();
            $table->decimal('salary_base', 15, 2)->default(0);
            $table->json('course_ids')->nullable();
            $table->string('cv_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_registrations');
    }
};
