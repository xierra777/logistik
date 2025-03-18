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
        Schema::table('shipments', function (Blueprint $table) {
            // COA untuk sisi penjualan (client)
            $table->date('estimearrival')->nullable()->after('ocean_vessel_mother');
            $table->date('estimedelivery')->nullable()->after('ocean_vessel_mother');
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
