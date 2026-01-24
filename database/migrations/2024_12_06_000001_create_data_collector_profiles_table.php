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
        Schema::create('data_collector_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            
            // Professional Information
            $table->string('organization')->nullable();
            $table->string('position')->nullable();
            $table->string('employee_id')->nullable();
            $table->text('reason_for_applying');
            $table->text('experience')->nullable();
            $table->string('education_level')->nullable();
            
            // Verification Documents
            $table->string('id_card_type')->nullable(); // National ID, Passport, Driver's License
            $table->string('id_card_number')->nullable();
            $table->string('id_card_document')->nullable(); // File path
            
            // Certificates & Qualifications
            $table->json('certificates')->nullable(); // Array of certificate file paths
            $table->string('professional_certification')->nullable();
            
            // Assignment & Territory
            $table->string('assigned_territory')->nullable();
            $table->string('coverage_area')->nullable(); // LGA, District, etc.
            $table->text('work_regions')->nullable(); // JSON or comma-separated
            
            // Government/Organization Verification
            $table->string('verification_document')->nullable(); // Letter of assignment, authorization
            $table->string('reference_name')->nullable();
            $table->string('reference_phone')->nullable();
            $table->string('reference_email')->nullable();
            
            // Approval Process
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            
            // Performance Metrics (for future use)
            $table->integer('total_submissions')->default(0);
            $table->integer('approved_submissions')->default(0);
            $table->decimal('accuracy_rate', 5, 2)->default(0.00); // Percentage
            $table->timestamp('last_submission_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('approval_status');
            $table->index('assigned_territory');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_collector_profiles');
    }
};