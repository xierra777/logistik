<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreignId('consignee_id')->nullable()->constrained('chart_of_accounts')->after('sremarks');
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
