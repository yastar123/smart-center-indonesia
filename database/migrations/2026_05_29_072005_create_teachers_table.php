<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('teachers', function (Blueprint $table) {

    $table->id();

    $table->foreignId('branch_id')->nullable();

    $table->string('nig')->unique();
    $table->string('name');

    $table->enum('gender', ['L', 'P'])->nullable();

    $table->date('birth_date')->nullable();
    $table->string('birth_place')->nullable();

    $table->text('address')->nullable();

    $table->string('phone')->nullable();
    $table->string('email')->nullable();

    $table->string('education')->nullable();
    $table->text('subjects')->nullable();

    $table->string('photo')->nullable();

    $table->decimal('salary_base', 15, 2)->default(0);

    $table->date('join_date')->nullable();

    $table->enum('status', [
        'aktif',
        'nonaktif'
    ])->default('aktif');

    $table->softDeletes();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};
