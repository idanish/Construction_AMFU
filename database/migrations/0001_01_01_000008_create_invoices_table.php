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
            $table->dateTime('invoice_date')->nullable();
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->text('due_date')->nullable();
            $table->text('vendor_name')->nullable();
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
