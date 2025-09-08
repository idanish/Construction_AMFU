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
        $table->string('title')->nullable()->change();
    });
}

public function down()
{
    Schema::table('service_requests', function (Blueprint $table) {
        $table->string('title')->nullable(false)->change();
    });
}

};
