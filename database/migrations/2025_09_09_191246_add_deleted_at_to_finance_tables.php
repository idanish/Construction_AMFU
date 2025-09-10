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
    Schema::table('procurements', function (Blueprint $table) {
        $table->softDeletes();
    });

    Schema::table('budgets', function (Blueprint $table) {
        $table->softDeletes();
    });

    Schema::table('payments', function (Blueprint $table) {
        $table->softDeletes();
    });

    Schema::table('invoices', function (Blueprint $table) {
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('procurements', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });

    Schema::table('budgets', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });

    Schema::table('payments', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });

    Schema::table('invoices', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
