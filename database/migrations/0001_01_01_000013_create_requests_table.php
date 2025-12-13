<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requestor_id')->constrained('users');
            $table->enum('type', ['general', 'private'])->default('general');
            // $table->foreignId('assigned_to_user_id')->nullable()->constrained('users');
            $table->foreignId('assigned_to_user_id')->nullable();
            // $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('department_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('comments')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'draft', 'need revision'])->default('pending');
            $table->unsignedInteger('current_level')->default(1);
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};