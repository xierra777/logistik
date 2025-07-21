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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no')->unique(); // PAY-24001
            $table->date('payment_date');

            $table->foreignId('customerVendor_id')->nullable()->constrained('customers')->nullOnDelete();

            $table->foreignId('bank_coa')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('currency', 10)->default('IDR');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('amount', 20, 2);
            $table->text('remarks')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('journal_posted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
