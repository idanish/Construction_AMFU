<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    // Invoices
    Schema::table('invoices', function (Blueprint $table) {
        if (!Schema::hasColumn('invoices', 'status')) {
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid')->after('amount');
        }
    });

    // Payments
    Schema::table('payments', function (Blueprint $table) {
        if (!Schema::hasColumn('payments', 'status')) {
            $table->enum('status', ['completed', 'pending'])->default('pending')->after('amount');
        }
    });

    // Budgets
    Schema::table('budgets', function (Blueprint $table) {
        if (!Schema::hasColumn('budgets', 'status')) {
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending')->after('balance');
        }
    });

    // Procurements
    Schema::table('procurements', function (Blueprint $table) {
        if (!Schema::hasColumn('procurements', 'status')) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('attachment');
        }
    });
}

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('procurements', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
