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
        Schema::table('farm_records', function (Blueprint $table) {
            // Make all potentially missing fields nullable
            
            // Step 1 fields
            if (Schema::hasColumn('farm_records', 'farmer_address')) {
                $table->text('farmer_address')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'farmer_village')) {
                $table->string('farmer_village')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'farmer_country')) {
                $table->string('farmer_country')->nullable()->default('Nigeria')->change();
            }
            if (Schema::hasColumn('farm_records', 'household_size')) {
                $table->integer('household_size')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'role_in_sector')) {
                $table->string('role_in_sector')->nullable()->change();
            }
            
            // Step 2 fields
            if (Schema::hasColumn('farm_records', 'vaccination_status')) {
                $table->string('vaccination_status')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'common_diseases')) {
                $table->json('common_diseases')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'other_diseases_text')) {
                $table->text('other_diseases_text')->nullable()->change();
            }
            
            // Step 3 fields
            if (Schema::hasColumn('farm_records', 'preferred_services')) {
                $table->json('preferred_services')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'has_cold_chain')) {
                $table->boolean('has_cold_chain')->nullable()->default(false)->change();
            }
            if (Schema::hasColumn('farm_records', 'training_needs')) {
                $table->json('training_needs')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'alerts_outbreak')) {
                $table->boolean('alerts_outbreak')->nullable()->default(true)->change();
            }
            if (Schema::hasColumn('farm_records', 'alerts_vaccine')) {
                $table->boolean('alerts_vaccine')->nullable()->default(true)->change();
            }
            if (Schema::hasColumn('farm_records', 'alerts_awareness')) {
                $table->boolean('alerts_awareness')->nullable()->default(true)->change();
            }
            if (Schema::hasColumn('farm_records', 'alerts_public')) {
                $table->boolean('alerts_public')->nullable()->default(true)->change();
            }
            if (Schema::hasColumn('farm_records', 'preferred_language')) {
                $table->string('preferred_language')->nullable()->default('english')->change();
            }
            if (Schema::hasColumn('farm_records', 'feedback')) {
                $table->text('feedback')->nullable()->change();
            }
            if (Schema::hasColumn('farm_records', 'consent_data_use')) {
                $table->boolean('consent_data_use')->nullable()->default(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't reverse - keep fields nullable for safety
    }
};