<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->enum('scope', ['global', 'lokal'])->default('global');
            $table->foreignId('cabang_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('curriculum_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained('curricula')->cascadeOnDelete();
            $table->string('judul');
            $table->unsignedSmallInteger('jumlah_sesi')->default(1);
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_chapters');
        Schema::dropIfExists('curricula');
    }
};
