<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section', 60);
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 30)->default('text');
            $table->string('label', 150);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('text');
            $table->string('gradient')->default('linear-gradient(135deg,#c84ddf,#68117e)');
            $table->string('initial', 5)->default('A');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('badge_label', 80)->default('PROGRAM');
            $table->string('badge_bg')->default('rgba(200,77,223,.1)');
            $table->string('badge_color')->default('#68117e');
            $table->string('icon_emoji', 10)->default('📖');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_programs');
        Schema::dropIfExists('landing_testimonials');
        Schema::dropIfExists('landing_settings');
    }
};
