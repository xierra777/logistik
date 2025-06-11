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
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
            $table->string('customerCodeJob')->nullable()->after('type_job');
        });
        Schema::table('job_containers', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('t_shipments', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('shipment_containers', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
        Schema::table('charge_settings', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('created_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_created_by_foreign');
            $table->dropForeign('users_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by', 'session_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_created_by_foreign');
            $table->dropForeign('transactions_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_created_by_foreign');
            $table->dropForeign('customers_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropForeign('chart_of_accounts_created_by_foreign');
            $table->dropForeign('chart_of_accounts_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('t_jobs', function (Blueprint $table) {
            $table->dropForeign('t_jobs_created_by_foreign');
            $table->dropForeign('t_jobs_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('job_containers', function (Blueprint $table) {
            $table->dropForeign('job_containers_created_by_foreign');
            $table->dropForeign('job_containers_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('t_shipments', function (Blueprint $table) {
            $table->dropForeign('t_shipments_created_by_foreign');
            $table->dropForeign('t_shipments_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('shipment_containers', function (Blueprint $table) {
            $table->dropForeign('shipment_containers_created_by_foreign');
            $table->dropForeign('shipment_containers_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropForeign('customer_addresses_created_by_foreign');
            $table->dropForeign('customer_addresses_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('charge_settings', function (Blueprint $table) {
            $table->dropForeign('charge_settings_created_by_foreign');
            $table->dropForeign('charge_settings_updated_by_foreign');
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
