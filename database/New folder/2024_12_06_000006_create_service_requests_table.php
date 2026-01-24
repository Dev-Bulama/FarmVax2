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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            
            // Association
            $table->unsignedBigInteger('user_id'); // Requester (farmer or data collector)
            $table->unsignedBigInteger('farm_record_id')->nullable();
            $table->unsignedBigInteger('livestock_id')->nullable(); // If specific to one animal
            $table->enum('requested_by_role', ['individual', 'data_collector'])->default('individual');
            
            // Service Information
            $table->enum('service_type', [
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
            ]);
            $table->string('other_service_type')->nullable();
            $table->string('service_title');
            $table->text('service_description');
            
            // Livestock Details
            $table->string('livestock_type')->nullable(); // cattle, goat, etc.
            $table->integer('number_of_animals')->default(1);
            $table->json('affected_animals')->nullable(); // Array of livestock IDs
            
            // Symptoms & Issues (if applicable)
            $table->json('symptoms')->nullable();
            $table->text('symptoms_description')->nullable();
            $table->date('symptoms_start_date')->nullable();
            $table->boolean('is_contagious')->nullable();
            $table->integer('affected_count')->nullable(); // How many animals affected
            
            // Priority & Urgency
            $table->enum('urgency_level', ['low', 'medium', 'high', 'emergency'])->default('medium');
            $table->enum('priority', ['routine', 'important', 'critical'])->default('routine');
            $table->boolean('requires_immediate_attention')->default(false);
            $table->text('urgency_reason')->nullable();
            
            // Scheduling
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->date('alternative_date')->nullable();
            $table->time('alternative_time')->nullable();
            $table->enum('time_preference', ['morning', 'afternoon', 'evening', 'anytime'])->default('anytime');
            $table->text('scheduling_notes')->nullable();
            
            // Location
            $table->text('service_location')->nullable();
            $table->string('location_type')->nullable(); // farm, clinic, mobile_unit
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('location_instructions')->nullable(); // How to find the location
            
            // Assignment
            $table->unsignedBigInteger('assigned_to')->nullable(); // Service provider/veterinarian
            $table->timestamp('assigned_at')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable(); // Admin who assigned
            $table->string('assigned_veterinarian_name')->nullable();
            $table->string('assigned_veterinarian_phone')->nullable();
            
            // Status & Workflow
            $table->enum('status', [
                'pending',
                'acknowledged',
                'assigned',
                'scheduled',
                'in_progress',
                'completed',
                'cancelled',
                'rejected'
            ])->default('pending');
            $table->text('status_notes')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Service Execution
            $table->date('actual_service_date')->nullable();
            $table->time('actual_service_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('service_notes')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_provided')->nullable();
            $table->json('medications_prescribed')->nullable();
            $table->text('recommendations')->nullable();
            
            // Follow-up
            $table->boolean('requires_followup')->default(false);
            $table->date('followup_date')->nullable();
            $table->text('followup_instructions')->nullable();
            $table->boolean('followup_completed')->default(false);
            $table->date('followup_completed_date')->nullable();
            
            // Cost Information
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->decimal('service_fee', 10, 2)->nullable();
            $table->decimal('medication_cost', 10, 2)->nullable();
            $table->decimal('transport_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('currency')->default('NGN');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'waived'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Outcome & Results
            $table->enum('outcome', ['successful', 'partially_successful', 'unsuccessful', 'pending'])->default('pending');
            $table->text('outcome_description')->nullable();
            $table->json('outcome_data')->nullable(); // Test results, measurements, etc.
            $table->boolean('animal_recovered')->nullable();
            $table->date('recovery_date')->nullable();
            
            // Documentation
            $table->json('documents')->nullable(); // Prescriptions, receipts, reports
            $table->json('images')->nullable(); // Before/after photos
            $table->string('prescription_document')->nullable();
            $table->string('service_report')->nullable();
            
            // Feedback & Rating
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('feedback')->nullable();
            $table->timestamp('feedback_date')->nullable();
            $table->boolean('would_recommend')->nullable();
            
            // Communication
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->string('alternative_contact')->nullable();
            $table->enum('preferred_contact_method', ['phone', 'sms', 'email', 'whatsapp'])->default('phone');
            
            // Admin Actions
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Notifications
            $table->boolean('requester_notified')->default(false);
            $table->timestamp('requester_notified_at')->nullable();
            $table->boolean('provider_notified')->default(false);
            $table->timestamp('provider_notified_at')->nullable();
            
            // Reminders
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->integer('reminder_count')->default(0);
            
            // Reference & Tracking
            $table->string('reference_number')->unique();
            $table->string('external_reference')->nullable(); // For integration with other systems
            
            // Notes
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('farm_record_id')->references('id')->on('farm_records')->onDelete('set null');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('user_id');
            $table->index('farm_record_id');
            $table->index('service_type');
            $table->index('status');
            $table->index('urgency_level');
            $table->index('priority');
            $table->index('assigned_to');
            $table->index('preferred_date');
            $table->index('actual_service_date');
            $table->index('payment_status');
            $table->index('reference_number');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};