<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('budgets', function (Blueprint $table) {
        if (!Schema::hasColumn('budgets', 'title')) {
            $table->string('title')->nullable();
        }
        if (!Schema::hasColumn('budgets', 'attachment')) {
            $table->string('attachment')->nullable();
        }
    });
}

public function down()
{
    Schema::table('budgets', function (Blueprint $table) {
        if (Schema::hasColumn('budgets', 'title')) {
            $table->dropColumn('title');
        }
        if (Schema::hasColumn('budgets', 'attachment')) {
            $table->dropColumn('attachment');
        }
    });
}

};
