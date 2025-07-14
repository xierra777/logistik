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
                ->onDelete('cascade');
            $table->string('transactionable_type');
            $table->foreignId('coa_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->decimal('debit', 16, 2)->default(0);
            $table->decimal('credit', 16, 2)->default(0);
            $table->string('description')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('reversal_of')->nullable(); // menunjuk ke journal yg direverse
            $table->boolean('is_reversal')->default(false);
            $table->string('description_of_reversal')->nullable();

            $table->index('coa_id');
            $table->index('transactionable_type'); // index untuk polymorphic type
            $table->index('date');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
