<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable()->after('cabang_id');
            }
        });
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'module_id')) {
                $table->unsignedBigInteger('module_id')->nullable()->after('paket_id');
            }
        });
    }

    public function down(): void {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'kelas_id')) $table->dropColumn('kelas_id');
        });
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'module_id')) $table->dropColumn('module_id');
        });
    }
};
