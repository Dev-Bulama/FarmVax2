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
        Schema::table('alert_preferences', function (Blueprint $table) {
            // Make phone and email fields nullable
            $table->string('primary_phone')->nullable()->change();
            $table->string('secondary_phone')->nullable()->change();
            $table->string('whatsapp_number')->nullable()->change();
            $table->string('primary_email')->nullable()->change();
            $table->string('secondary_email')->nullable()->change();
            
            // Make location fields nullable
            $table->string('alert_location_village')->nullable()->change();
            $table->string('alert_location_lga')->nullable()->change();
            $table->string('alert_location_state')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_preferences', function (Blueprint $table) {
            $table->string('primary_phone')->nullable(false)->change();
            $table->string('secondary_phone')->nullable(false)->change();
            $table->string('whatsapp_number')->nullable(false)->change();
            $table->string('primary_email')->nullable(false)->change();
            $table->string('secondary_email')->nullable(false)->change();
            $table->string('alert_location_village')->nullable(false)->change();
            $table->string('alert_location_lga')->nullable(false)->change();
            $table->string('alert_location_state')->nullable(false)->change();
        });
    }
};