<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->string('regency')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->after('id');

            $table->string('email')->nullable();
            $table->string('password')->nullable();

            $table->boolean('can_students')->default(true);
            $table->boolean('can_teachers')->default(true);
            $table->boolean('can_schedules')->default(true);
            $table->boolean('can_payments')->default(true);
            $table->boolean('can_tryouts')->default(true);
        });
    }

    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->dropColumn([
                'regency',
                'user_id',
                'email',
                'password',
                'can_students',
                'can_teachers',
                'can_schedules',
                'can_payments',
                'can_tryouts'
            ]);
        });
    }
};