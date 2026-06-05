<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'regency')) {
                $table->string('regency')->nullable();
            }

            if (! Schema::hasColumn('branches', 'email')) {
                $table->string('email')->nullable();
            }
            if (! Schema::hasColumn('branches', 'password')) {
                $table->string('password')->nullable();
            }

            if (! Schema::hasColumn('branches', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }

            if (! Schema::hasColumn('branches', 'can_students')) {
                $table->boolean('can_students')->default(true);
            }
            if (! Schema::hasColumn('branches', 'can_teachers')) {
                $table->boolean('can_teachers')->default(true);
            }
            if (! Schema::hasColumn('branches', 'can_schedules')) {
                $table->boolean('can_schedules')->default(true);
            }
            if (! Schema::hasColumn('branches', 'can_payments')) {
                $table->boolean('can_payments')->default(true);
            }
            if (! Schema::hasColumn('branches', 'can_tryouts')) {
                $table->boolean('can_tryouts')->default(true);
            }
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