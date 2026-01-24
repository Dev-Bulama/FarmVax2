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
        // First, check if 'lga' column exists and rename it
        if (Schema::hasColumn('farm_records', 'lga') && !Schema::hasColumn('farm_records', 'farmer_lga')) {
            Schema::table('farm_records', function (Blueprint $table) {
                $table->renameColumn('lga', 'farmer_lga');
            });
        }
        
        // Add farmer_lga if it doesn't exist
        if (!Schema::hasColumn('farm_records', 'farmer_lga')) {
            Schema::table('farm_records', function (Blueprint $table) {
                $table->string('farmer_lga')->nullable()->after('farmer_state');
            });
        }
        
        // Ensure all required columns exist
        Schema::table('farm_records', function (Blueprint $table) {
            // Stakeholder columns
            if (!Schema::hasColumn('farm_records', 'farmer_city')) {
                $table->string('farmer_city')->nullable()->after('farmer_address');
            }
            if (!Schema::hasColumn('farm_records', 'farmer_state')) {
                $table->string('farmer_state')->nullable()->after('farmer_city');
            }
            if (!Schema::hasColumn('farm_records', 'average_household_size')) {
                $table->integer('average_household_size')->nullable()->after('farm_type');
            }
            
            // Livestock columns
            if (!Schema::hasColumn('farm_records', 'young_count')) {
                $table->integer('young_count')->default(0)->after('total_livestock_count');
            }
            if (!Schema::hasColumn('farm_records', 'adult_count')) {
                $table->integer('adult_count')->default(0)->after('young_count');
            }
            if (!Schema::hasColumn('farm_records', 'old_count')) {
                $table->integer('old_count')->default(0)->after('adult_count');
            }
            
            // Health columns
            if (!Schema::hasColumn('farm_records', 'has_health_issues')) {
                $table->boolean('has_health_issues')->default(false)->after('last_vaccination_date');
            }
            if (!Schema::hasColumn('farm_records', 'current_health_issues')) {
                $table->json('current_health_issues')->nullable()->after('has_health_issues');
            }
            if (!Schema::hasColumn('farm_records', 'health_notes')) {
                $table->text('health_notes')->nullable()->after('current_health_issues');
            }
            if (!Schema::hasColumn('farm_records', 'veterinarian_name')) {
                $table->string('veterinarian_name')->nullable()->after('health_notes');
            }
            if (!Schema::hasColumn('farm_records', 'veterinarian_phone')) {
                $table->string('veterinarian_phone')->nullable()->after('veterinarian_name');
            }
            if (!Schema::hasColumn('farm_records', 'last_vet_visit')) {
                $table->date('last_vet_visit')->nullable()->after('veterinarian_phone');
            }
            if (!Schema::hasColumn('farm_records', 'past_diseases')) {
                $table->json('past_diseases')->nullable()->after('last_vet_visit');
            }
            
            // Service columns
            if (!Schema::hasColumn('farm_records', 'service_needs')) {
                $table->json('service_needs')->nullable()->after('urgency_level');
            }
            if (!Schema::hasColumn('farm_records', 'needs_immediate_attention')) {
                $table->boolean('needs_immediate_attention')->default(false)->after('preferred_service_date');
            }
            
            // Alert columns
            if (!Schema::hasColumn('farm_records', 'sms_alerts')) {
                $table->boolean('sms_alerts')->default(false)->after('needs_immediate_attention');
            }
            if (!Schema::hasColumn('farm_records', 'email_alerts')) {
                $table->boolean('email_alerts')->default(false)->after('sms_alerts');
            }
            if (!Schema::hasColumn('farm_records', 'phone_alerts')) {
                $table->boolean('phone_alerts')->default(false)->after('email_alerts');
            }
            if (!Schema::hasColumn('farm_records', 'alert_types')) {
                $table->json('alert_types')->nullable()->after('phone_alerts');
            }
            if (!Schema::hasColumn('farm_records', 'alternative_phone')) {
                $table->string('alternative_phone')->nullable()->after('alert_types');
            }
            
            // Consent columns
            if (!Schema::hasColumn('farm_records', 'data_sharing_consent')) {
                $table->boolean('data_sharing_consent')->default(false)->after('alternative_phone');
            }
            if (!Schema::hasColumn('farm_records', 'research_participation_consent')) {
                $table->boolean('research_participation_consent')->default(false)->after('data_sharing_consent');
            }
            if (!Schema::hasColumn('farm_records', 'marketing_consent')) {
                $table->boolean('marketing_consent')->default(false)->after('research_participation_consent');
            }
            if (!Schema::hasColumn('farm_records', 'additional_comments')) {
                $table->text('additional_comments')->nullable()->after('marketing_consent');
            }
            if (!Schema::hasColumn('farm_records', 'feedback')) {
                $table->text('feedback')->nullable()->after('additional_comments');
            }
            
            // Timestamp columns
            if (!Schema::hasColumn('farm_records', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('farm_records', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('submitted_at');
            }
            if (!Schema::hasColumn('farm_records', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            }
        });
        
        // Fix any existing data that might have 'lga' instead of 'farmer_lga'
        // This is safe because we've already renamed the column if it existed
        
        echo "✅ All farm_records columns verified and fixed!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversing - this is a fix migration
    }
};