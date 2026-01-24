<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This fixes ALL ENUM mismatches in service_requests table
     */
    public function up(): void
    {
        // Fix service_type ENUM
        DB::statement("ALTER TABLE `service_requests` MODIFY `service_type` ENUM(
            'vaccination',
            'treatment',
            'consultation',
            'emergency',
            'routine_checkup',
            'breeding',
            'deworming',
            'castration',
            'pregnancy_check',
            'artificial_insemination',
            'nutritional_advice',
            'nutrition_advice',
            'disease_diagnosis',
            'surgery',
            'other'
        ) NOT NULL");

        // Fix priority ENUM - Accept BOTH sets of values
        DB::statement("ALTER TABLE `service_requests` MODIFY `priority` ENUM(
            'low',
            'medium',
            'high',
            'routine',
            'important',
            'critical'
        ) NOT NULL DEFAULT 'medium'");

        // Fix urgency_level ENUM - Make sure it accepts the values being sent
        DB::statement("ALTER TABLE `service_requests` MODIFY `urgency_level` VARCHAR(255) DEFAULT 'medium'");

        echo "✅ All ENUM columns fixed!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUMs
        DB::statement("ALTER TABLE `service_requests` MODIFY `service_type` ENUM(
            'vaccination',
            'treatment',
            'consultation',
            'emergency',
            'routine_checkup',
            'breeding',
            'deworming',
            'castration',
            'pregnancy_check',
            'artificial_insemination',
            'nutritional_advice',
            'disease_diagnosis',
            'surgery',
            'other'
        ) NOT NULL");

        DB::statement("ALTER TABLE `service_requests` MODIFY `priority` ENUM(
            'routine',
            'important',
            'critical'
        ) NOT NULL DEFAULT 'routine'");
    }
};