<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')->nullable()->index();
            }
            if (!Schema::hasColumn('schedules', 'cabang_id')) {
                $table->unsignedBigInteger('cabang_id')->nullable()->index();
            }
            if (!Schema::hasColumn('schedules', 'tanggal')) {
                $table->date('tanggal')->nullable()->index();
            }
            if (!Schema::hasColumn('schedules', 'jam_mulai')) {
                $table->time('jam_mulai')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'jam_selesai')) {
                $table->time('jam_selesai')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'topik')) {
                $table->string('topik')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'jenis')) {
                $table->enum('jenis', ['online', 'offline'])->default('offline');
            }
            if (!Schema::hasColumn('schedules', 'ruangan')) {
                $table->string('ruangan')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'link_meeting')) {
                $table->string('link_meeting')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'status')) {
                $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            }
            if (!Schema::hasColumn('schedules', 'catatan')) {
                $table->text('catatan')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'reminder_terkirim')) {
                $table->boolean('reminder_terkirim')->default(false);
            }
            if (!Schema::hasColumn('schedules', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'kelas_id','guru_id','cabang_id','tanggal','jam_mulai','jam_selesai',
                'topik','jenis','ruangan','link_meeting','status','catatan',
                'reminder_terkirim','deleted_at',
            ]);
        });
    }
};
