<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `courses` MODIFY `icon` VARCHAR(255) NULL;");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `courses` MODIFY `icon` VARCHAR(50) NULL;");
    }
};
