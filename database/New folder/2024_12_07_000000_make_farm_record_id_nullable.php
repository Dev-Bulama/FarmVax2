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
        Schema::table('livestock', function (Blueprint $table) {
            // Make farm_record_id nullable since not all livestock need to be linked to a farm record
            $table->unsignedBigInteger('farm_record_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock', function (Blueprint $table) {
            // Revert back to NOT NULL (optional - you may want to keep it nullable)
            $table->unsignedBigInteger('farm_record_id')->nullable(false)->change();
        });
    }
};