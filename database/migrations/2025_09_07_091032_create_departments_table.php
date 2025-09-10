<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // auto increment primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps(); // created_at and updated_at
            $table->unsignedBigInteger('transaction_no')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
