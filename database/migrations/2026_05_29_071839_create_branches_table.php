<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('student_count')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['status', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};