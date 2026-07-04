<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_tickers', function (Blueprint $table) {
            $table->id();
            $table->string('emoji', 10)->default('🎉');
            $table->string('text', 255);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_features', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 60)->default('bi-check-circle-fill');
            $table->string('label', 150);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_jenjangs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('label', 150);
            $table->string('image')->nullable();
            $table->string('emoji', 10)->default('📚');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_trusts', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 60)->default('bi-patch-check-fill');
            $table->string('text', 150);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_highlights', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title', 150);
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('alt', 150)->default('');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 255);
            $table->text('answer');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_faqs');
        Schema::dropIfExists('landing_galleries');
        Schema::dropIfExists('landing_highlights');
        Schema::dropIfExists('landing_trusts');
        Schema::dropIfExists('landing_jenjangs');
        Schema::dropIfExists('landing_features');
        Schema::dropIfExists('landing_tickers');
    }
};
