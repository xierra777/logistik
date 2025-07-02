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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
            $table->string('status')->default('draft')->after('invoice_number');
        });
        Schema::table('invoice_transaction', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
