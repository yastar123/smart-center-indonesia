<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Base columns used by Student model/controller
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nis')->unique();
            $table->string('name');

            // Gender: L / P
            $table->enum('gender', ['L', 'P']);

            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();

            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();

            $table->string('photo')->nullable();

            // status: aktif / nonaktif / lulus
            $table->string('status')->default('aktif');

            $table->date('join_date')->nullable();
            $table->string('school_name')->nullable();
            $table->string('grade')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // drop foreign constraints first where possible
            $table->dropForeign(['user_id']);

            $table->dropColumn([
                'user_id',
                'nis',
                'name',
                'gender',
                'birth_date',
                'birth_place',
                'address',
                'phone',
                'parent_name',
                'parent_phone',
                'photo',
                'status',
                'join_date',
                'school_name',
                'grade',
            ]);
        });
    }
};

