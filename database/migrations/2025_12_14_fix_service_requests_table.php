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
        Schema::table('service_requests', function (Blueprint $table) {
            // Make all potentially missing or required columns nullable
            
            // Make farm_record_id nullable if it exists
            if (Schema::hasColumn('service_requests', 'farm_record_id')) {
                $table->unsignedBigInteger('farm_record_id')->nullable()->change();
            }
            
            // Make livestock_id nullable if it exists
            if (Schema::hasColumn('service_requests', 'livestock_id')) {
                $table->unsignedBigInteger('livestock_id')->nullable()->change();
            }
            
            // Make contact_phone nullable if it exists
            if (Schema::hasColumn('service_requests', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->change();
            }
            
            // Add urgency column if it doesn't exist (but urgency_level does)
            if (!Schema::hasColumn('service_requests', 'urgency') && Schema::hasColumn('service_requests', 'urgency_level')) {
                // Don't add urgency, we'll just use urgency_level
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert
    }
};