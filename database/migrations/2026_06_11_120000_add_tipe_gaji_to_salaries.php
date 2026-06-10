<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('salaries', 'tipe_gaji')) {
                $table->string('tipe_gaji', 50)->default('bulanan')->after('periode');
            }
        });
    }

    public function down()
    {
        Schema::table('salaries', function (Blueprint $table) {
            if (Schema::hasColumn('salaries', 'tipe_gaji')) {
                $table->dropColumn('tipe_gaji');
            }
        });
    }
};
