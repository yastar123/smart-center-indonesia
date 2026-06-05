<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ── INVOICES ─────────────────────────────────────────────────────
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'siswa_id'))         $table->unsignedBigInteger('siswa_id')->nullable()->index();
            if (!Schema::hasColumn('invoices', 'cabang_id'))        $table->unsignedBigInteger('cabang_id')->nullable()->index();
            if (!Schema::hasColumn('invoices', 'nomor_invoice'))    $table->string('nomor_invoice')->nullable()->unique();
            if (!Schema::hasColumn('invoices', 'subtotal'))         $table->decimal('subtotal', 15, 2)->default(0);
            if (!Schema::hasColumn('invoices', 'diskon'))           $table->decimal('diskon', 15, 2)->default(0);
            if (!Schema::hasColumn('invoices', 'pajak'))            $table->decimal('pajak', 15, 2)->default(0);
            if (!Schema::hasColumn('invoices', 'total'))            $table->decimal('total', 15, 2)->default(0);
            if (!Schema::hasColumn('invoices', 'deskripsi'))        $table->text('deskripsi')->nullable();
            if (!Schema::hasColumn('invoices', 'periode'))          $table->string('periode', 50)->nullable();
            if (!Schema::hasColumn('invoices', 'jatuh_tempo'))      $table->date('jatuh_tempo')->nullable()->index();
            if (!Schema::hasColumn('invoices', 'status'))           $table->enum('status', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar')->index();
            if (!Schema::hasColumn('invoices', 'catatan'))          $table->text('catatan')->nullable();
            if (!Schema::hasColumn('invoices', 'deleted_at'))       $table->softDeletes();
        });

        // ── PAYMENTS ─────────────────────────────────────────────────────
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'invoice_id'))           $table->unsignedBigInteger('invoice_id')->nullable()->index();
            if (!Schema::hasColumn('payments', 'siswa_id'))             $table->unsignedBigInteger('siswa_id')->nullable()->index();
            if (!Schema::hasColumn('payments', 'cabang_id'))            $table->unsignedBigInteger('cabang_id')->nullable()->index();
            if (!Schema::hasColumn('payments', 'nomor_pembayaran'))     $table->string('nomor_pembayaran')->nullable()->unique();
            if (!Schema::hasColumn('payments', 'jumlah'))               $table->decimal('jumlah', 15, 2)->default(0);
            if (!Schema::hasColumn('payments', 'metode'))               $table->enum('metode', ['cash', 'transfer', 'qris'])->default('cash');
            if (!Schema::hasColumn('payments', 'nama_bank'))            $table->string('nama_bank')->nullable();
            if (!Schema::hasColumn('payments', 'nomor_rekening'))       $table->string('nomor_rekening')->nullable();
            if (!Schema::hasColumn('payments', 'bukti_pembayaran'))     $table->string('bukti_pembayaran')->nullable();
            if (!Schema::hasColumn('payments', 'tanggal_pembayaran'))   $table->date('tanggal_pembayaran')->nullable()->index();
            if (!Schema::hasColumn('payments', 'status'))               $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->index();
            if (!Schema::hasColumn('payments', 'alasan_penolakan'))     $table->text('alasan_penolakan')->nullable();
            if (!Schema::hasColumn('payments', 'catatan'))              $table->text('catatan')->nullable();
            if (!Schema::hasColumn('payments', 'disetujui_oleh'))       $table->unsignedBigInteger('disetujui_oleh')->nullable();
            if (!Schema::hasColumn('payments', 'tanggal_disetujui'))    $table->timestamp('tanggal_disetujui')->nullable();
            if (!Schema::hasColumn('payments', 'deleted_at'))           $table->softDeletes();
        });

        // ── SCHOOL_CLASSES ────────────────────────────────────────────────
        Schema::table('school_classes', function (Blueprint $table) {
            if (!Schema::hasColumn('school_classes', 'cabang_id'))          $table->unsignedBigInteger('cabang_id')->nullable()->index();
            if (!Schema::hasColumn('school_classes', 'mata_pelajaran_id'))   $table->unsignedBigInteger('mata_pelajaran_id')->nullable();
            if (!Schema::hasColumn('school_classes', 'guru_id'))            $table->unsignedBigInteger('guru_id')->nullable()->index();
            if (!Schema::hasColumn('school_classes', 'tahun_akademik_id'))  $table->unsignedBigInteger('tahun_akademik_id')->nullable();
            if (!Schema::hasColumn('school_classes', 'nama'))               $table->string('nama')->nullable();
            if (!Schema::hasColumn('school_classes', 'nama_kelas'))         $table->string('nama_kelas')->nullable();
            if (!Schema::hasColumn('school_classes', 'kapasitas'))          $table->unsignedSmallInteger('kapasitas')->default(30);
            if (!Schema::hasColumn('school_classes', 'jenis'))              $table->enum('jenis', ['online', 'offline'])->default('offline');
            if (!Schema::hasColumn('school_classes', 'ruangan'))            $table->string('ruangan')->nullable();
            if (!Schema::hasColumn('school_classes', 'link_zoom'))          $table->string('link_zoom')->nullable();
            if (!Schema::hasColumn('school_classes', 'status'))             $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->index();
            if (!Schema::hasColumn('school_classes', 'deleted_at'))         $table->softDeletes();
        });

        // ── COURSES ───────────────────────────────────────────────────────
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'cabang_id'))  $table->unsignedBigInteger('cabang_id')->nullable()->index();
            if (!Schema::hasColumn('courses', 'kode'))       $table->string('kode', 20)->nullable();
            if (!Schema::hasColumn('courses', 'nama'))       $table->string('nama', 100)->nullable();
            if (!Schema::hasColumn('courses', 'deskripsi'))  $table->text('deskripsi')->nullable();
            if (!Schema::hasColumn('courses', 'kategori'))   $table->string('kategori', 50)->nullable();
            if (!Schema::hasColumn('courses', 'icon'))       $table->string('icon', 50)->nullable();
            if (!Schema::hasColumn('courses', 'warna'))      $table->string('warna', 10)->nullable();
            if (!Schema::hasColumn('courses', 'status'))     $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->index();
            if (!Schema::hasColumn('courses', 'deleted_at')) $table->softDeletes();
        });
    }

    public function down()
    {
        // Drop added columns only
    }
};
