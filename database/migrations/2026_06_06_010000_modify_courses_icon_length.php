<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    public function up()
    {
        $driver = Config::get('database.default');
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE courses MODIFY icon VARCHAR(255)');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE courses ALTER COLUMN icon TYPE VARCHAR(255)');
        }
    }

    public function down()
    {
        $driver = Config::get('database.default');
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE courses MODIFY icon VARCHAR(50)');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE courses ALTER COLUMN icon TYPE VARCHAR(50)');
        }
    }
};
