<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('invoices')) {
            DB::statement("ALTER TABLE `invoices` MODIFY `attachment` LONGTEXT NULL");
        }
    }

    public function down()
    {
        if (Schema::hasTable('invoices')) {
            DB::statement("ALTER TABLE `invoices` MODIFY `attachment` VARCHAR(255) NULL");
        }
    }
};
