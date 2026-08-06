<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('student_registrations', 'private_address')) {
                $table->string('private_address')->nullable()->after('learning_place');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('student_registrations', 'private_address')) {
                $table->dropColumn('private_address');
            }
        });
    }
};
