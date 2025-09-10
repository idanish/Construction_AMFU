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
            if (!Schema::hasColumn('service_requests', 'request_no')) {
                $table->string('request_no')->after('id');
            }
            if (!Schema::hasColumn('service_requests', 'transaction_no')) {
                $table->unsignedBigInteger('transaction_no')->default(0)->after('request_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['request_no', 'transaction_no']);
        });
    }
};
