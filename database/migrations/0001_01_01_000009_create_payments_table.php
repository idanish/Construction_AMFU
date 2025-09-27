<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_ref')->unique();
            $table->unsignedBigInteger('invoice_id');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'Bank', 'Online']);
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
