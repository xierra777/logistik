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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: "VAT 11%", "WHT 2%"
            $table->enum('type', ['vat', 'wht']); // jenis pajak
            $table->decimal('rate', 5, 2); // persen, contoh: 11.00
            $table->enum('context', ['sales', 'cost'])->default('sales'); // pajak berlaku di penjualan atau pembelian
            $table->foreignId('coa_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete(); // akun COA terkait
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
