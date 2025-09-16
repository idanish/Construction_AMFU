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
            $table->unsignedBigInteger('request_id');
            $table->string('invoice_no');
            $table->decimal('amount', 10, 2);
            $table->dateTime('invoice_date')->nullable();
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
