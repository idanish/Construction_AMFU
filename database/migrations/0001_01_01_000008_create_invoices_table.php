<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procurement_id')->nullable();
            $table->string('invoice_no')->unique();
            $table->decimal('amount', 10, 2);
            $table->dateTime('invoice_date');
            $table->enum('status', ['unpaid', 'partial','paid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->dateTime('due_date');
            $table->text('vendor_name');
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('procurement_id')->references('id')->on('procurements')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
