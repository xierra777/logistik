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
        Schema::table('transactions', function (Blueprint $table) {
            // COA untuk sisi penjualan (client)

            $table->string('shwtaxrateusd')->nullable()->after('svatgst');
            $table->string('svatgstusd')->nullable()->after('shwtaxrateusd');
            $table->string('chwtaxrateusd')->nullable()->after('cvatgst');
            $table->string('cvatgstusd')->nullable()->after('chwtaxrateusd');
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
