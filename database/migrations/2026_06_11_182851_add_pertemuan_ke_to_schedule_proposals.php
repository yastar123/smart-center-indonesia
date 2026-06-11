<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('schedule_proposals') && !Schema::hasColumn('schedule_proposals', 'pertemuan_ke')) {
            Schema::table('schedule_proposals', function (Blueprint $table) {
                $table->unsignedTinyInteger('pertemuan_ke')->nullable()->after('class_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('schedule_proposals') && Schema::hasColumn('schedule_proposals', 'pertemuan_ke')) {
            Schema::table('schedule_proposals', function (Blueprint $table) {
                $table->dropColumn('pertemuan_ke');
            });
        }
    }
};
