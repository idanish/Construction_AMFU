<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approval_levels', function (Blueprint $table) {
        $table->id();
        $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
        $table->string('name');
        $table->unsignedInteger('sequence');
        $table->string('role_name')->nullable();  // Using Spatie roles
        // $table->foreignId('user_id')->nullable(); // Specific person approval
        $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_levels');
    }
};