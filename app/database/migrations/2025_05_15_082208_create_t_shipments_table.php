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
            $table->foreignId('shipper_id')->nullable()->constrained('customers');
            $table->foreignId('consignee_id')->nullable()->constrained('customers');
            $table->foreignId('notify_id')->nullable()->constrained('customers');
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
