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
            // Only add columns that don't exist
            if (!Schema::hasColumn('farm_records', 'young_count')) {
                $table->integer('young_count')->nullable()->after('total_livestock_count');
            }
            
            if (!Schema::hasColumn('farm_records', 'adult_count')) {
                $table->integer('adult_count')->nullable()->after('young_count');
            }
            
            if (!Schema::hasColumn('farm_records', 'old_count')) {
                $table->integer('old_count')->nullable()->after('adult_count');
            }
            
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
            
            if (!Schema::hasColumn('farm_records', 'service_needs')) {
                $table->json('service_needs')->nullable()->after('service_description');
            }
            
            if (!Schema::hasColumn('farm_records', 'needs_immediate_attention')) {
                $table->boolean('needs_immediate_attention')->default(false)->after('preferred_service_date');
            }
            
            if (!Schema::hasColumn('farm_records', 'sms_alerts')) {
                $table->boolean('sms_alerts')->default(false)->after('preferred_contact_method');
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_records', function (Blueprint $table) {
            $columns = [
                'young_count', 'adult_count', 'old_count',
                'has_health_issues', 'current_health_issues', 'health_notes',
                'veterinarian_name', 'veterinarian_phone', 'last_vet_visit', 'past_diseases',
                'service_needs', 'needs_immediate_attention',
                'sms_alerts', 'email_alerts', 'phone_alerts', 'alert_types', 'alternative_phone',
                'data_sharing_consent', 'research_participation_consent', 'marketing_consent',
                'additional_comments', 'feedback'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('farm_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};