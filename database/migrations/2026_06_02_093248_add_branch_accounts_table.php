<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->string('regency')->nullable();

            $table->string('email')->nullable();
            $table->string('password')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->boolean('can_students')->default(true);
            $table->boolean('can_teachers')->default(true);
            $table->boolean('can_schedules')->default(true);
            $table->boolean('can_payments')->default(true);
            $table->boolean('can_tryouts')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn([
                'regency',
                'email',
                'password',
                'status',
                'can_students',
                'can_teachers',
                'can_schedules',
                'can_payments',
                'can_tryouts'
            ]);
        });
    }
};