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
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('level');
            $table->enum('status', ['pending', 'approved', 'rejected', 'draft', 'need revision'])->default('pending');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Schema::create('approvals', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
        //     $table->foreignId('step_id')->constrained('request_approval_steps')->cascadeOnDelete();
        //     $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();
        //     $table->enum('action', ['pending', 'approved', 'rejected', 'forwarded', 'returned', 'draft', 'need revision'])->default('pending');
        //     // $table->enum('status', ['pending', 'approved', 'rejected', 'forwarded', 'returned', 'draft', 'need revision'])->default('pending');
        //     $table->text('comments')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
