<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);        // 2024/2025
            $table->year('year_start');
            $table->year('year_end');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
 
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name', 20);        // Ganjil, Genap
            $table->tinyInteger('semester_number'); // 1, 2
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('academic_years');
    }
};
 