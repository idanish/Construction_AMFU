<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->string('attachment')->nullable();
            $table->integer('year');
            $table->decimal('allocated', 12, 2);
             // New columns
            $table->decimal('requested_budget', 12, 2)->nullable();
            $table->enum('budget_type', ['monthly', 'weekly'])->nullable();

            $table->decimal('spent', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('notes')->nullable();
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending');
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->string('current_approval_step')->default('PM');
            $table->text('revert_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
