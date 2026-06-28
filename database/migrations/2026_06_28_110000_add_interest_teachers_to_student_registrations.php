<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->json('interest_teachers')->nullable()->after('interest_sessions');
        });
    }
    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn('interest_teachers');
        });
    }
};
