<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreignId('consignee_id')->nullable()->constrained('customers')->after('shipment_id');
            $table->foreignId('shipper_id')->nullable()->constrained('customers')->after('shipment_id');
            $table->foreignId('notify_id')->nullable()->constrained('customers')->after('shipment_id');
        });
    }

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
