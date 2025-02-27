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

            // Charge section
            $table->string('charge');
            $table->string('description');
            $table->string('freight');
            $table->string('unit')->nullable();
            $table->string('quantity')->default("0");
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
            $table->string('staxableamount')->default("0");
            $table->string('svatgstamount')->default("0");
            $table->string('swhtaxrate')->nullable();
            $table->string('swhtaxamount')->default("0");
            $table->text('sremarks')->nullable();
            $table->string('sgrossprofit')->default("20000");

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
            $table->string('cvatgstamount')->default("0");
            $table->string('ctaxableamount')->nullable();
            $table->text('cremarks')->nullable();
            $table->string('cwhtaxrate')->nullable();
            $table->string('cwhtaxamount')->default("0");

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
