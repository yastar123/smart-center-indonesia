<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A previous migration (2026_06_26_141554) dropped `pertemuan_ke` from
     * `schedules`, but the meeting-number feature (attendance ordering,
     * admin schedule list, and the whole ScheduleProposalService slot
     * system) still depends on this column. Restore it so those features
     * work again; new weekly-generated schedules simply leave it null.
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'pertemuan_ke')) {
                $table->unsignedSmallInteger('pertemuan_ke')->nullable()->after('guru_id');
            }
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'pertemuan_ke')) {
                $table->dropColumn('pertemuan_ke');
            }
        });
    }
};
