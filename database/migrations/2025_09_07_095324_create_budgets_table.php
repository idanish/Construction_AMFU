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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->unsignedBigInteger('department_id');
            $table->string('attachment')->nullable();
            $table->decimal('allocated', 12, 2);
            $table->decimal('spent', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
