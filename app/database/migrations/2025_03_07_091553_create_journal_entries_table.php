<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->onDelete('cascade')
                ->after('coa_id');
            $table->foreignId('coa_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->string('debit')->default(0);
            $table->string('credit')->default(0);
            $table->string('description');
            $table->date('date')->nullable();
            $table->timestamps();

            $table->index('coa_id');
            $table->index('transaction_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};
