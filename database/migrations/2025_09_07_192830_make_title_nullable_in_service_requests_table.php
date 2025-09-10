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
        Schema::table('service_requests', function (Blueprint $table) {
            // Make title nullable
            $table->string('title')->nullable()->change();

            // Add transaction_no if not exists
            if (!Schema::hasColumn('service_requests', 'transaction_no')) {
                $table->unsignedBigInteger('transaction_no')->default(0)->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Revert title nullable
            $table->string('title')->nullable(false)->change();

            // Drop transaction_no
            $table->dropColumn('transaction_no');
        });
    }
};
