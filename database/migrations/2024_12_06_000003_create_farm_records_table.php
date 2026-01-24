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
        Schema::create('farm_records', function (Blueprint $table) {
            $table->id();
            
            // Record Ownership
            $table->unsignedBigInteger('user_id'); // Who created the record (individual or data collector)
            $table->unsignedBigInteger('farmer_id')->nullable(); // If data collector, references the actual farmer
            $table->enum('created_by_role', ['individual', 'data_collector'])->default('individual');
            
            // STEP 1: Stakeholder Information
            $table->string('farmer_name');
            $table->string('farmer_email')->nullable();
            $table->string('farmer_phone');
            $table->text('farmer_address');
            $table->string('farmer_city');
            $table->string('farmer_state');
            $table->string('farmer_lga')->nullable(); // Local Government Area
            $table->string('farm_name')->nullable();
            $table->decimal('farm_size', 10, 2)->nullable(); // in hectares or acres
            $table->string('farm_size_unit')->default('hectares'); // hectares or acres
            
            // Geolocation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_accuracy')->nullable();
            
            // Farm Type & Purpose
            $table->enum('farm_type', ['commercial', 'subsistence', 'mixed'])->default('subsistence');
            $table->json('farming_purpose')->nullable(); // meat, dairy, breeding, etc.
            
            // STEP 2: Livestock Profile
            $table->integer('total_livestock_count')->default(0);
            $table->json('livestock_types')->nullable(); // cattle, goats, sheep, pigs, poultry, etc.
            $table->json('livestock_details')->nullable(); // Detailed breakdown by type
            
            // Age Distribution
            $table->integer('young_count')->default(0); // < 1 year
            $table->integer('adult_count')->default(0); // 1-5 years
            $table->integer('old_count')->default(0); // > 5 years
            
            // Breed Information
            $table->json('breed_information')->nullable();
            
            // STEP 3: Health & Vaccination History
            $table->date('last_vaccination_date')->nullable();
            $table->json('vaccination_history')->nullable(); // Array of vaccinations
            $table->boolean('has_health_issues')->default(false);
            $table->json('current_health_issues')->nullable();
            $table->text('health_notes')->nullable();
            
            // Veterinary Information
            $table->string('veterinarian_name')->nullable();
            $table->string('veterinarian_phone')->nullable();
            $table->date('last_vet_visit')->nullable();
            
            // Disease History
            $table->boolean('disease_outbreak_history')->default(false);
            $table->json('past_diseases')->nullable();
            $table->text('disease_notes')->nullable();
            
            // STEP 4: Service Needs & Requests
            $table->json('service_needs')->nullable(); // vaccination, treatment, consultation, etc.
            $table->enum('urgency_level', ['low', 'medium', 'high', 'emergency'])->default('low');
            $table->text('service_description')->nullable();
            $table->date('preferred_service_date')->nullable();
            $table->boolean('needs_immediate_attention')->default(false);
            
            // STEP 5: Alert Preferences
            $table->boolean('sms_alerts')->default(true);
            $table->boolean('email_alerts')->default(false);
            $table->boolean('phone_alerts')->default(false);
            $table->json('alert_types')->nullable(); // vaccination reminders, health alerts, etc.
            $table->string('preferred_contact_method')->default('sms');
            $table->string('alternative_phone')->nullable();
            
            // STEP 6: Feedback & Consent
            $table->boolean('data_sharing_consent')->default(false);
            $table->boolean('research_participation_consent')->default(false);
            $table->boolean('marketing_consent')->default(false);
            $table->text('additional_comments')->nullable();
            $table->text('feedback')->nullable();
            
            // Record Status & Workflow
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('submitted');
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            
            // Data Quality
            $table->boolean('is_verified')->default(false);
            $table->decimal('data_completeness_score', 5, 2)->default(0.00); // Percentage
            $table->json('validation_errors')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('farmer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('user_id');
            $table->index('farmer_id');
            $table->index('status');
            $table->index('created_by_role');
            $table->index('farm_type');
            $table->index('urgency_level');
            $table->index('submitted_at');
            $table->index(['latitude', 'longitude']); // Geospatial queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_records');
    }
};