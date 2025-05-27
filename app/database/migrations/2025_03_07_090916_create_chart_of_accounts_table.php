<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Buat tabel chart_of_accounts dengan field untuk kode, nama, tipe akun, term type, dan parent account
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->enum('account_type', ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense']);
            // Field term_type menentukan apakah akun ini berfungsi sebagai CR (Credit) atau DR (Debit)
            $table->enum('term_type', ['CR', 'DR'])->nullable()->comment('Payment term type: CR (credit) atau DR (debit)');
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->timestamps();
        });

        // Tambahkan foreign key untuk self-referencing parent account
        // Schema::table('chart_of_accounts', function (Blueprint $table) {
        //     $table->foreign('parent_account_id')
        //         ->references('id')
        //         ->on('chart_of_accounts')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropForeign(['parent_account_id']);
        });
        Schema::dropIfExists('chart_of_accounts');
    }
};
