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
            $table->string('container_id')->unique();
            $table->string('container_type');
            $table->string('shipper')->nullable();
            $table->string('consignee')->nullable();
            $table->string('notify')->nullable();
            $table->string('ocean_vessel_feeder')->nullable();
            $table->string('ocean_vessel_mother')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('combined_transport')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('packages')->nullable();
            $table->text('description')->nullable();
            $table->string('gross_weight')->nullable();
            $table->string('measurement')->nullable();
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