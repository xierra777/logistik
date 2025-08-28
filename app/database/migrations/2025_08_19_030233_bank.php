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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->string('bank_code')->nullable();
            $table->string('branch_name', 100)->nullable();
            $table->string('swift_code', 11)->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->foreignId('bank_coa_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('banks');
    }
};
