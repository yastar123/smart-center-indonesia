<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE courses ALTER COLUMN icon TYPE VARCHAR(255)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE courses ALTER COLUMN icon TYPE VARCHAR(50)');
    }
};
