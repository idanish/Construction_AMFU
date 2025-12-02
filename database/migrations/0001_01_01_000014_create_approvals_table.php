<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();

            // Polymorphic: invoice, payment, budget, procurement, request
            $table->string('approvable_type')->nullable();
            $table->unsignedBigInteger('approvable_id')->nullable();

            // Backward support for old requests
            $table->unsignedBigInteger('request_id')->nullable();

            // Approver
            $table->unsignedBigInteger('approver_id');

            // Workflow info
            $table->string('approval_step')->default('PM');
            $table->integer('step_order')->default(1);
            $table->string('assigned_role')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('note')->nullable();  // old comments
            $table->text('revert_reason')->nullable();

            $table->timestamp('acted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
