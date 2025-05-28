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
        Schema::create('charge_settings', function (Blueprint $table) {
            $table->id();
            $table->string('charge_code')->unique();
            $table->string('charge_name');
            $table->foreignId('coa_sale_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->foreignId('coa_cost_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_settings');
    }
};
