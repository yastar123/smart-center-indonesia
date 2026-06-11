<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('branches', 'allowed_pages')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->json('allowed_pages')->nullable()->after('can_tryouts');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('branches', 'allowed_pages')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('allowed_pages');
            });
        }
    }
};
