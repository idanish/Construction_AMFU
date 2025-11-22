<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * We alter the `attachment` column to LONGTEXT to accommodate large JSON arrays
     * of attachments (path + original name). Using raw SQL avoids requiring
     * the doctrine/dbal package.
     */
    public function up()
    {
        if (Schema::hasTable('budgets')) {
            // Use ALTER TABLE MODIFY to change the column type
            DB::statement("ALTER TABLE `budgets` MODIFY `attachment` LONGTEXT NULL");
        }
    }

    /**
     * Reverse the migrations.
     *
     * Revert to VARCHAR(255) if rolling back. If your previous column was
     * different, adjust accordingly.
     */
    public function down()
    {
        if (Schema::hasTable('budgets')) {
            DB::statement("ALTER TABLE `budgets` MODIFY `attachment` VARCHAR(255) NULL");
        }
    }
};
