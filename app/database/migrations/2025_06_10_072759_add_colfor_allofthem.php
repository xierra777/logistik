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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
            $table->string('session_id')->nullable()->after('remember_token');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('job_containers', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('t_shipments', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('shipment_containers', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
        });
        Schema::table('charge_settings', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->after('updated_at');
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
