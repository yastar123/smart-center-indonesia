<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'jenis_kursus')) {
                $table->string('jenis_kursus', 50)->nullable()->after('kategori');
            }
        });
    }
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('jenis_kursus');
        });
    }
};
