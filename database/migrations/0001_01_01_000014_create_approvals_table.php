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

            // Foreign keys
            // $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
