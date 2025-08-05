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
            $table->softDeletes();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('job_containers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('t_shipments', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('shipment_containers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('charge_settings', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('job_containers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('t_shipments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('shipment_containers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('charge_settings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};