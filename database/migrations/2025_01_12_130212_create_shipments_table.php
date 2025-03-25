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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id')->unique();
            $table->string('shipper')->nullable();
            $table->string('consignee')->nullable();
            $table->string('notify')->nullable();
            $table->string('ocean_vessel_feeder')->nullable();
            $table->string('ocean_vessel_mother')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('place_of_receipt')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
