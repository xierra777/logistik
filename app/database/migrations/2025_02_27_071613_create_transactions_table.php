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
        Schema::create('salesTransactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained('t_shipments')->nullOnDelete();
            $table->foreignId('job_id')->nullable()->constrained('t_jobs')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('coa_sale_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->foreignId('coa_cost_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('charge')->nullable();
            $table->string('description')->nullable();
            $table->string('freight')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('ofdtype')->nullable();
            $table->text('remarks')->nullable();

            $table->foreignId('sclient')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('scurrency', 10)->nullable();
            $table->decimal('srate', 12, 4)->nullable();
            $table->decimal('samount_qty', 16, 4)->nullable();
            $table->boolean('sincludedtax')->default(false);
            $table->decimal('sfcyamount', 16, 4)->nullable();
            $table->decimal('samountidr', 16, 4)->nullable();
            $table->string('sdrcr', 5)->nullable(); // debit / credit
            $table->decimal('svatgst', 5, 2)->nullable();
            $table->decimal('staxableamount', 16, 4)->nullable();
            $table->decimal('svatgstamount', 16, 4)->nullable();
            $table->decimal('shwtaxrateusd', 16, 4)->nullable();
            $table->decimal('svatgstusd', 16, 4)->nullable();
            $table->decimal('swhtaxrate', 5, 2)->nullable();
            $table->decimal('swhtaxamount', 16, 4)->nullable();
            $table->text('sremarks')->nullable();
            $table->decimal('sgrossprofit', 16, 4)->nullable();

            // Cost section
            $table->foreignId('cvendor')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('creferenceno')->nullable();
            $table->date('cdate')->nullable();
            $table->string('cdrcr', 5)->nullable(); // debit / credit
            $table->string('ccurrency', 10)->nullable();
            $table->decimal('crate', 12, 4)->nullable();
            $table->decimal('camount_qty', 16, 4)->nullable();
            $table->boolean('cincludedtax')->default(false);
            $table->decimal('cfcyamount', 16, 4)->nullable();
            $table->decimal('camountidr', 16, 4)->nullable();
            $table->decimal('cvatgst', 5, 2)->nullable();
            $table->decimal('cvatgstamount', 16, 4)->nullable();
            $table->decimal('ctaxableamount', 16, 4)->nullable();
            $table->text('cremarks')->nullable();
            $table->decimal('chwtaxrateusd', 16, 4)->nullable();
            $table->decimal('cvatgstusd', 16, 4)->nullable();
            $table->decimal('cwhtaxrate', 5, 2)->nullable();
            $table->decimal('cwhtaxamount', 16, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
