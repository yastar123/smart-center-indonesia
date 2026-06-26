<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extra_class_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->date('tanggal_rencana');
            $table->time('jam_mulai');
            $table->unsignedSmallInteger('jumlah_sesi')->default(1);
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('status')->default('pending'); // pending | confirmed | lunas | ditolak
            $table->text('catatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extra_class_requests');
    }
};
