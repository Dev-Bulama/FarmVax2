<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix service_type ENUM to match form values
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM
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
    }
};