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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coa_id')->nullable()->constrained('chart_of_accounts');
            $table->enum('category', ['creditor', 'debtor']);
            $table->string('name');
            $table->string('country');
            $table->json('roles');
            $table->string('contact');
            $table->string('web');
            $table->string('email');
            $table->string('customer_code')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
