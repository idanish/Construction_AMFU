<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Purana foreign key drop karo
            $table->dropForeign(['request_id']);
            
            // Naya foreign key link karo service_requests table se
            $table->foreign('request_id')
                  ->references('id')
                  ->on('service_requests')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            
            // Agar wapas requests table se link karna ho
            $table->foreign('request_id')
                  ->references('id')
                  ->on('requests')
                  ->onDelete('cascade');
        });
    }
};
