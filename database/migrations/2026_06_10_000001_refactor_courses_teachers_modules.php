<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('teacher_courses')) {
            Schema::create('teacher_courses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['teacher_id', 'course_id']);
            });
        }

        Schema::table('courses', function (Blueprint $table) {
            $columns = [];
            foreach (['kategori', 'icon', 'warna'] as $column) {
                if (Schema::hasColumn('courses', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'urutan')) {
                $table->dropColumn('urutan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'urutan')) {
                $table->unsignedInteger('urutan')->default(0)->after('deskripsi');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'kategori')) {
                $table->string('kategori', 50)->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('courses', 'icon')) {
                $table->string('icon')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('courses', 'warna')) {
                $table->string('warna', 10)->nullable()->after('icon');
            }
        });

        Schema::dropIfExists('teacher_courses');
    }
};
