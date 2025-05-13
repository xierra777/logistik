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
        Schema::create('t_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->unique();
            $table->string('type_job');
            $table->foreignId('client_id')->nullable()->constrained('customers');
            $table->foreignId('dagentsJob')->nullable()->constrained('customers');
            $table->foreignId('ogentsJob')->nullable()->constrained('customers');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->dropForeign(['consignee_id']);
            $table->dropColumn('consignee_id');
            $table->dropForeign(['shipper_id']);
            $table->dropColumn('shipper_id');
            $table->dropForeign(['notify_id']);
            $table->dropColumn('notify_id');
        });
    }
};
