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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_shipment')->nullable()->constrained('t_shipments')->nullOnDelete();
            $table->foreignId('id_job')->nullable()->constrained('t_jobs')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('coa_sale_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->foreignId('coa_cost_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('reference_type')->nullable(); // 'job', 'shipment', 'office_expense'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->index(['reference_type', 'reference_id']);
            $table->string('charge')->nullable();
            $table->string('description')->nullable();
            $table->string('freight')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('ofdtype')->nullable();
            $table->text('remarks')->nullable();

            $table->foreignId('sclient')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('scurrency', 10)->nullable();
            $table->decimal('srate', 12, 2)->nullable();
            $table->decimal('samount_qty', 16, 2)->nullable();
            $table->string('sincludedtax')->nullable();
            $table->decimal('sfcyamount', 16, 2)->nullable();
            $table->decimal('samountidr', 16, 2)->nullable();
            $table->string('sdrcr', 5)->nullable(); // debit / credit
            $table->foreignId('svatgst')->nullable()->constrained('taxes')->nullOnDelete();
            $table->decimal('staxableamount', 16, 2)->nullable();
            $table->decimal('svatgstamount', 16, 2)->nullable();
            $table->decimal('shwtaxrateusd', 16, 2)->nullable();
            $table->decimal('svatgstusd', 16, 2)->nullable();
            $table->foreignId('swhtaxrate')->nullable()->constrained('taxes')->nullOnDelete();
            $table->decimal('swhtaxamount', 16, 2)->nullable();
            $table->text('sremarks')->nullable();
            $table->string('sgrossprofit')->nullable();

            // Cost section
            $table->foreignId('cvendor')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('creferenceno')->nullable();
            $table->date('cdate')->nullable();
            $table->string('cdrcr', 5)->nullable(); // debit / credit
            $table->string('ccurrency', 10)->nullable();
            $table->decimal('crate', 12, 2)->nullable();
            $table->decimal('camount_qty', 16, 2)->nullable();
            $table->string('cincludedtax')->nullable();
            $table->decimal('cfcyamount', 16, 2)->nullable();
            $table->decimal('camountidr', 16, 2)->nullable();
            $table->foreignId('cvatgst')->nullable()->constrained('taxes')->nullOnDelete();
            $table->decimal('cvatgstamount', 16, 2)->nullable();
            $table->decimal('ctaxableamount', 16, 2)->nullable();
            $table->text('cremarks')->nullable();
            $table->decimal('chwtaxrateusd', 16, 2)->nullable();
            $table->decimal('cvatgstusd', 16, 2)->nullable();
            $table->foreignId('cwhtaxrate')->nullable()->constrained('taxes')->nullOnDelete();
            $table->decimal('cwhtaxamount', 16, 2)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
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
