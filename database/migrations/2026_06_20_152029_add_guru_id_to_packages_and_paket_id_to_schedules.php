<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->unsignedBigInteger('guru_id')->nullable()->after('cabang_id');
            $table->foreign('guru_id')->references('id')->on('teachers')->nullOnDelete();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('paket_id')->nullable()->after('kelas_id');
            $table->foreign('paket_id')->references('id')->on('packages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn('paket_id');
        });
    }
};
