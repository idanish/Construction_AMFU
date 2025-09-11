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

            // Transaction_no add karo agar exist nahi karta
            if (!Schema::hasColumn('invoices', 'transaction_no')) {
                $table->unsignedBigInteger('transaction_no')->default(0)->after('request_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['request_id']);
            
            // Wapas old foreign key
            $table->foreign('request_id')
                  ->references('id')
                  ->on('requests')
                  ->onDelete('cascade');

            // Drop transaction_no
            $table->dropColumn('transaction_no');
        });
    }
};
