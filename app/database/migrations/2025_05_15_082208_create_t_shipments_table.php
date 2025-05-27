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
        Schema::create('t_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_job')->nullable()->constrained('t_jobs')->onDelete('cascade');
            $table->string('shipmentsTypeJob');
            $table->string('shipment_id')->unique();
            $table->foreignId('shipmentClient_id')->nullable()->constrained('customers');
            $table->foreignId('shipmentShipper_id')->nullable()->constrained('customers');
            $table->foreignId('shipmentConsignee_id')->nullable()->constrained('customers');
            $table->foreignId('shipmentNotify_id')->nullable()->constrained('customers');
            $table->foreignId('shipmentCarrierAirline')->nullable()->constrained('customers');
            $table->foreignId('shipmentContainerDeliveryAgent')->nullable()->constrained('customers');
            $table->foreignId('containerShipmentCarrierAirline')->nullable()->constrained('customers');
            $table->foreignId('shipmentCarrierAgent')->nullable()->constrained('customers');
            $table->foreignId('shipmentDeliveryAgent')->nullable()->constrained('customers');
            $table->string('shipmentClient_address')->nullable();
            $table->foreignid('employee_id')->nullable()->constrained('users');
            $table->json('dataShipments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_shipments');
    }
};
