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
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->string('jobBillLadingNo')->nullable()->after('type_job');
            $table->date('jobBillLadingDate')->nullable()->after('type_job');
            $table->string('houseJobBillLadingNo')->nullable()->after('type_job');
            $table->date('houseJobBillLadingDate')->nullable()->after('type_job');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'jobBillLadingNo',
                'jobBillLadingDate',
                'houseJobBillLadingNo',
                'houseJobBillLadingDate'
            ]);
        });
    }
};
