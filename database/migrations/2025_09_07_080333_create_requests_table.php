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
    Schema::create('requests', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // invoice, payment, budget, procurement
        $table->unsignedBigInteger('reference_id'); // record ka id
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->unsignedBigInteger('requested_by'); // jisne create kiya
        $table->unsignedBigInteger('approved_by')->nullable(); // admin
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
