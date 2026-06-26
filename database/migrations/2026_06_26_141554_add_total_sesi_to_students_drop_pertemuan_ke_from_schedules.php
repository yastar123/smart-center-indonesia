<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'total_sesi')) {
                $table->unsignedSmallInteger('total_sesi')->default(0)->after('package_id')
                      ->comment('Jumlah sesi yang dialokasikan untuk siswa ini, bukan dari paket');
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'pertemuan_ke')) {
                $table->dropColumn('pertemuan_ke');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'total_sesi')) {
                $table->dropColumn('total_sesi');
            }
        });
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'pertemuan_ke')) {
                $table->unsignedSmallInteger('pertemuan_ke')->nullable()->after('guru_id');
            }
        });
    }
};
