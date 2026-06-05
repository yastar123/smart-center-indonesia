<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (! Schema::hasColumn('branches', 'address')) {
                $table->string('address')->nullable()->after('name');
            }
            if (! Schema::hasColumn('branches', 'phone')) {
                $table->string('phone')->nullable()->after('address');
            }
            if (! Schema::hasColumn('branches', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('admin_id');
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
            if (Schema::hasColumn('branches', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('branches', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('branches', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
