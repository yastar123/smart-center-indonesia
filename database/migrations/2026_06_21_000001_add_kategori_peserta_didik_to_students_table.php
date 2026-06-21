<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'kategori_peserta_didik')) {
                $table->string('kategori_peserta_didik')->nullable()->after('grade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'kategori_peserta_didik')) {
                $table->dropColumn('kategori_peserta_didik');
            }
        });
    }
};
