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
            $table->foreignId('consignee_id')->nullable()->constrained('customers');
            $table->foreignId('shipper_id')->nullable()->constrained('customers');
            $table->foreignId('notify_id')->nullable()->constrained('customers');
            $table->string('ocean_vessel_feeder')->nullable();
            $table->string('ocean_vessel_mother')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('place_of_receipt')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->date('estimearrival')->nullable()->after('ocean_vessel_mother');
            $table->date('estimedelivery')->nullable()->after('ocean_vessel_mother');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['consignee_id']);
            $table->dropColumn('consignee_id');
            $table->dropForeign(['shipper_id']);
            $table->dropColumn('shipper_id');
            $table->dropForeign(['notify_id']);
            $table->dropColumn('notify_id');
        });
    }
};
