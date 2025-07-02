<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabel utama invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('t_jobs')->onDelete('set null');
            $table->foreignId('shipment_id')->nullable()->constrained('t_shipments')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Tabel pivot invoice_transaction

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
