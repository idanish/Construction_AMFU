<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('procurements', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->unsignedBigInteger('department_id')->nullable();
        $table->text('description')->nullable();
        $table->string('attachment')->nullable();
        $table->timestamps();
        $table->unsignedBigInteger('transaction_no')->default(0);
        $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
