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
        Schema::create('vaccination_history', function (Blueprint $table) {
            $table->id();
            
            // Association
            $table->unsignedBigInteger('livestock_id')->nullable(); // Specific animal
            $table->unsignedBigInteger('farm_record_id'); // Farm record
            $table->unsignedBigInteger('user_id'); // Farm owner
            $table->unsignedBigInteger('recorded_by')->nullable(); // Data collector who recorded it
            
            // Vaccination Type
            $table->string('livestock_type'); // cattle, goat, etc.
            $table->integer('number_of_animals')->default(1); // If batch vaccination
            
            // Vaccine Information
            $table->string('vaccine_name');
            $table->string('vaccine_type')->nullable(); // Live, inactivated, toxoid, etc.
            $table->string('disease_target'); // What disease it prevents
            $table->text('vaccine_description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('lot_number')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Administration Details
            $table->date('vaccination_date');
            $table->time('vaccination_time')->nullable();
            $table->enum('administration_route', [
                'intramuscular',
                'subcutaneous',
                'intranasal',
                'oral',
                'intravenous',
                'other'
            ])->nullable();
            $table->string('injection_site')->nullable(); // neck, hip, etc.
            $table->decimal('dosage', 8, 2)->nullable();
            $table->string('dosage_unit')->default('ml');
            
            // Administered By
            $table->string('veterinarian_name')->nullable();
            $table->string('veterinarian_license')->nullable();
            $table->string('veterinarian_phone')->nullable();
            $table->string('administrator_name')->nullable(); // If not a vet
            $table->enum('administrator_type', ['veterinarian', 'vet_technician', 'farmer', 'data_collector', 'other'])->nullable();
            
            // Vaccination Schedule
            $table->boolean('is_initial_dose')->default(true);
            $table->boolean('is_booster')->default(false);
            $table->integer('dose_number')->default(1); // 1st, 2nd, 3rd dose
            $table->integer('total_doses_required')->default(1);
            $table->date('next_dose_due_date')->nullable();
            $table->date('next_booster_due_date')->nullable();
            $table->integer('booster_interval_days')->nullable(); // Days until next booster
            
            // Cost Information
            $table->decimal('vaccine_cost', 10, 2)->nullable();
            $table->decimal('administration_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('currency')->default('NGN');
            $table->string('payment_method')->nullable();
            
            // Health Status Before & After
            $table->text('pre_vaccination_health')->nullable();
            $table->decimal('pre_vaccination_temperature', 5, 2)->nullable(); // °C
            $table->text('post_vaccination_observation')->nullable();
            $table->decimal('post_vaccination_temperature', 5, 2)->nullable();
            
            // Side Effects & Reactions
            $table->boolean('adverse_reaction')->default(false);
            $table->enum('reaction_severity', ['none', 'mild', 'moderate', 'severe'])->default('none');
            $table->json('reaction_symptoms')->nullable();
            $table->text('reaction_notes')->nullable();
            $table->date('reaction_start_date')->nullable();
            $table->date('reaction_resolution_date')->nullable();
            $table->boolean('reaction_reported')->default(false);
            
            // Follow-up & Monitoring
            $table->boolean('requires_followup')->default(false);
            $table->date('followup_date')->nullable();
            $table->text('followup_notes')->nullable();
            $table->boolean('followup_completed')->default(false);
            
            // Documentation
            $table->string('certificate_number')->nullable(); // Vaccination certificate
            $table->json('documents')->nullable(); // Array of document paths
            $table->json('images')->nullable(); // Before/after images
            
            // Location Information
            $table->string('vaccination_location')->nullable(); // Farm, clinic, mobile unit
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Campaign/Program Information
            $table->string('campaign_name')->nullable(); // If part of a vaccination campaign
            $table->string('program_sponsor')->nullable(); // Government, NGO, etc.
            $table->boolean('is_government_program')->default(false);
            $table->boolean('is_subsidized')->default(false);
            $table->decimal('subsidy_amount', 10, 2)->nullable();
            
            // Verification & Quality
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Status
            $table->enum('status', ['scheduled', 'completed', 'missed', 'cancelled'])->default('completed');
            $table->text('status_notes')->nullable();
            
            // Reminders
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('set null');
            $table->foreign('farm_record_id')->references('id')->on('farm_records')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('livestock_id');
            $table->index('farm_record_id');
            $table->index('user_id');
            $table->index('vaccination_date');
            $table->index('next_dose_due_date');
            $table->index('next_booster_due_date');
            $table->index('vaccine_name');
            $table->index('disease_target');
            $table->index('status');
            $table->index('adverse_reaction');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination_history');
    }
};