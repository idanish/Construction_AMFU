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
            $table->longText('attachment')->nullable();
            $table->integer('year');
            $table->integer('month');
            $table->decimal('allocated', 12, 2);
            $table->decimal('requested_budget', 12, 2)->nullable();
            $table->enum('budget_type', ['monthly', 'weekly'])->nullable();
            $table->decimal('spent', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('notes')->nullable();
            $table->foreignId('requestor_id')->nullable()->constrained('users');
            $table->unsignedInteger('current_level')->default(1);
            $table->foreign('department_id')->nullable()->references('id')->on('departments')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'draft', 'need revision'])->default('pending');
            $table->unsignedBigInteger('transaction_no')->default(0);
            $table->string('current_approval_step')->nullable();
            $table->text('revert_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
