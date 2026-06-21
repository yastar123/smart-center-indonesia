<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('job')->nullable();
            $table->string('program')->nullable();
            $table->string('system')->nullable();
            $table->string('learning_place')->nullable();
            $table->string('pickup_mode')->nullable();
            $table->string('branch')->nullable();
            $table->json('interests')->nullable();
            $table->json('day_preferences')->nullable();
            $table->string('schedule_time')->nullable();
            $table->date('start_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
