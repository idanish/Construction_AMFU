<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->nullable();              // unique request number (added from alter)
            $table->string('title')->nullable();                   // title now nullable
            $table->text('description')->nullable();               // details
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->unsignedBigInteger('transaction_no')->default(0); // already added but ensured
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
