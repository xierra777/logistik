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
        Schema::table('containers', function (Blueprint $table) {
            $table->string('volume_weight')->after('measurement');
            $table->string('chargeable_weight')->after('measurement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
