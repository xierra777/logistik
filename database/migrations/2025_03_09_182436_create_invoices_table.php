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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->date('invoice_date');
        $table->string('currency')->default('IDR');
        $table->decimal('sub_total', 15, 2)->default(0);
        $table->decimal('total_vat', 15, 2)->default(0);
        $table->decimal('total_wht', 15, 2)->default(0);
        $table->decimal('grand_total', 15, 2)->default(0);
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
