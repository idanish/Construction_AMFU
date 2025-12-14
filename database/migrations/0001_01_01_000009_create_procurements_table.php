<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            // $table->integer('quantity');
            $table->unsignedInteger('quantity');
            $table->decimal('cost_estimate', 12, 2);
            $table->foreignId('requestor_id')->nullable()->constrained('users');
            $table->unsignedInteger('current_level')->default(1);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'draft', 'need revision'])->default('pending');
            $table->longText('attachment')->nullable();
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->string('current_approval_step')->nullable();
            $table->text('revert_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
