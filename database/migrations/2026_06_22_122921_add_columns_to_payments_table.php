<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'invoice_id')) {
                $table->unsignedBigInteger('invoice_id')->nullable()->after('id');
                $table->unsignedBigInteger('siswa_id')->nullable()->after('invoice_id');
                $table->unsignedBigInteger('cabang_id')->nullable()->after('siswa_id');
                $table->string('nomor_pembayaran')->nullable()->after('cabang_id');
                $table->decimal('jumlah', 15, 2)->default(0)->after('nomor_pembayaran');
                $table->string('metode')->default('transfer')->after('jumlah');
                $table->string('nama_bank')->nullable()->after('metode');
                $table->string('nomor_rekening')->nullable()->after('nama_bank');
                $table->string('bukti_pembayaran')->nullable()->after('nomor_rekening');
                $table->date('tanggal_pembayaran')->nullable()->after('bukti_pembayaran');
                $table->string('status')->default('pending')->after('tanggal_pembayaran');
                $table->text('alasan_penolakan')->nullable()->after('status');
                $table->text('catatan')->nullable()->after('alasan_penolakan');
                $table->unsignedBigInteger('disetujui_oleh')->nullable()->after('catatan');
                $table->timestamp('tanggal_disetujui')->nullable()->after('disetujui_oleh');
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'invoice_id','siswa_id','cabang_id','nomor_pembayaran','jumlah',
                'metode','nama_bank','nomor_rekening','bukti_pembayaran',
                'tanggal_pembayaran','status','alasan_penolakan','catatan',
                'disetujui_oleh','tanggal_disetujui',
            ]);
        });
    }
};
