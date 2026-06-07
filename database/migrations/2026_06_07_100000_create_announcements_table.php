<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cabang_id')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->string('judul');
            $table->text('konten');
            $table->string('jenis', 30)->default('info'); // info, promo, penting, update
            $table->string('target', 30)->default('semua'); // semua, admin, guru, siswa
            $table->string('file')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->string('status', 20)->default('aktif'); // aktif, draft, arsip
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
