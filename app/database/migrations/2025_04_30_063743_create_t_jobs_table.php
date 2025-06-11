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
            $table->foreignId('client_id')->nullable()->constrained('customers')->nullOnDelete('set null');
            $table->foreignId('carrierAirline')->nullable()->constrained('customers')->nullOnDelete('set null');;
            $table->foreignId('dagentsJob')->nullable()->constrained('customers')->nullOnDelete('set null');;
            $table->foreignId('ogentsJob')->nullable()->constrained('customers')->nullOnDelete('set null');;
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete('set null');;
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_jobs');
    }
};
