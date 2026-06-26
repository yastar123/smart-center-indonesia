<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('judul');
            $table->enum('tipe', ['diskon', 'bundle_upgrade', 'special_price', 'lainnya'])->default('diskon');
            $table->string('kode_promo')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('banner_path')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->enum('target', ['semua', 'paket_intensif', 'cabang', 'cicilan'])->default('semua');
            $table->foreignId('cabang_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->enum('status', ['draft', 'aktif', 'berakhir'])->default('draft');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('claims')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
