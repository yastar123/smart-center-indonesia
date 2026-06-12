<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensi_siswas', function (Blueprint $table) {
            $table->boolean('guru_hadir')->default(false)->after('siswa_id');
            $table->timestamp('siswa_konfirmasi_at')->nullable()->after('guru_hadir');
        });

        // Update existing records: if status='hadir' set guru_hadir=true
        \DB::table('absensi_siswas')->where('status', 'hadir')->update(['guru_hadir' => true]);
    }

    public function down()
    {
        Schema::table('absensi_siswas', function (Blueprint $table) {
            $table->dropColumn(['guru_hadir', 'siswa_konfirmasi_at']);
        });
    }
};
