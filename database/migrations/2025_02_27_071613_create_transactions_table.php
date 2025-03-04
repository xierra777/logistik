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
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            // Charge section
            $table->string('charge')->nullable();
            $table->string('description')->nullable();
            $table->string('freight')->nullable();
            $table->string('unit')->nullable();
            $table->string('quantity')->nullable();
            $table->string('ofdtype')->nullable();
            $table->text('remarks')->nullable();

            // Sale section
            $table->string('sclient')->nullable();
            $table->string('scurrency')->nullable();
            $table->string('srate')->nullable();
            $table->string('samount_qty')->nullable();
            $table->string('sincludedtax')->nullable();
            $table->string('sfcyamount')->nullable();
            $table->string('samountidr')->nullable();
            $table->string('sdrcr')->nullable();
            $table->string('svatgst')->nullable();
            $table->string('staxableamount')->nullable();
            $table->string('svatgstamount')->nullable();
            $table->string('swhtaxrate')->nullable();
            $table->string('swhtaxamount')->nullable();
            $table->text('sremarks')->nullable();
            $table->string('sgrossprofit')->nullable();

            // Cost section
            $table->string('cvendor')->nullable();
            $table->string('creferenceno')->nullable();
            $table->string('cdate')->nullable();
            $table->string('cdrcr')->nullable();
            $table->string('ccurrency')->nullable();
            $table->string('crate')->nullable();
            $table->string('camount_qty')->nullable();
            $table->string('cincludedtax')->nullable();
            $table->string('cfcyamount')->nullable();
            $table->string('camountidr')->nullable();
            $table->string('cvatgst')->nullable();
            $table->string('cvatgstamount')->nullable();
            $table->string('ctaxableamount')->nullable();
            $table->text('cremarks')->nullable();
            $table->string('cwhtaxrate')->nullable();
            $table->string('cwhtaxamount')->nullable();

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
