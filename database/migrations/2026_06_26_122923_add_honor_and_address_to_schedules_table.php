<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->decimal('honor_per_sesi', 12, 2)->nullable()->after('catatan');
            $table->string('alamat_kunjungan', 500)->nullable()->after('honor_per_sesi');
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['honor_per_sesi', 'alamat_kunjungan']);
        });
    }
};
