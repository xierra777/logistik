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
        Schema::create('invoice_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->decimal('amountInvoice', 15, 2)->nullable();
            $table->decimal('amountInvoiceUsd', 15, 2)->nullable();
            $table->decimal('quantityInvoice', 15, 2)->nullable();
            $table->decimal('vatInvoice', 15, 2)->nullable();
            $table->decimal('vatInvoiceUsd', 15, 2)->nullable();
            $table->decimal('whtInvoice', 15, 2)->nullable();
            $table->decimal('whtInvoiceUsd', 15, 2)->nullable();
            $table->decimal('exchangeRate', 15, 2)->nullable();

            // $table->decimal('scurrencyInvoice', 15, 2);
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['invoice_id', 'transaction_id']); // supaya gak duplikat
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
