<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // COA untuk sisi penjualan (client)
            $table->foreignId('coa_sale_id')->nullable()->constrained('chart_of_accounts')->after('sremarks');
            // COA untuk sisi biaya (vendor)
            $table->foreignId('coa_cost_id')->nullable()->constrained('chart_of_accounts')->after('cwhtaxamount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['coa_sale_id']);
            $table->dropColumn('coa_sale_id');
            $table->dropForeign(['coa_cost_id']);
            $table->dropColumn('coa_cost_id');
        });
    }
};
