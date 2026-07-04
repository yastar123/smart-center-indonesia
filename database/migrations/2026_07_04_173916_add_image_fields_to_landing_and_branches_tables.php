<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('landing_programs', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon_emoji');
        });

        Schema::table('landing_testimonials', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('initial');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('landing_programs', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('landing_testimonials', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
