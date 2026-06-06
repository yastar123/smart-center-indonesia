<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'siswa_id')) {
                $table->unsignedBigInteger('siswa_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('certificates', 'cabang_id')) {
                $table->unsignedBigInteger('cabang_id')->nullable()->after('siswa_id');
            }
            if (!Schema::hasColumn('certificates', 'diterbitkan_oleh')) {
                $table->unsignedBigInteger('diterbitkan_oleh')->nullable()->after('cabang_id');
            }
            if (!Schema::hasColumn('certificates', 'nomor_sertifikat')) {
                $table->string('nomor_sertifikat')->nullable()->after('diterbitkan_oleh');
            }
            if (!Schema::hasColumn('certificates', 'jenis')) {
                $table->string('jenis', 50)->nullable()->after('nomor_sertifikat');
            }
            if (!Schema::hasColumn('certificates', 'judul')) {
                $table->string('judul')->nullable()->after('jenis');
            }
            if (!Schema::hasColumn('certificates', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('certificates', 'tanggal_terbit')) {
                $table->date('tanggal_terbit')->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('certificates', 'tanggal_expired')) {
                $table->date('tanggal_expired')->nullable()->after('tanggal_terbit');
            }
            if (!Schema::hasColumn('certificates', 'file_sertifikat')) {
                $table->string('file_sertifikat')->nullable()->after('tanggal_expired');
            }
            if (!Schema::hasColumn('certificates', 'file_qrcode')) {
                $table->string('file_qrcode')->nullable()->after('file_sertifikat');
            }
            if (!Schema::hasColumn('certificates', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            foreach ([
                'siswa_id','cabang_id','diterbitkan_oleh','nomor_sertifikat','jenis','judul','deskripsi','tanggal_terbit','tanggal_expired','file_sertifikat','file_qrcode'
            ] as $col) {
                if (Schema::hasColumn('certificates', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('certificates', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
