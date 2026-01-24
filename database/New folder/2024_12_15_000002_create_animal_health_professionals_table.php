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
        Schema::create('animal_health_professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Professional Type
            $table->enum('professional_type', [
                'veterinarian',
                'paraveterinarian',
                'community_animal_health_worker',
                'others'
            ]);
            
            // Professional Details
            $table->string('license_number')->nullable();
            $table->string('organization')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('specialization')->nullable();
            $table->string('assigned_territory')->nullable();
            
            // Contact Information
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Approval Status
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Application Details
            $table->timestamp('submitted_at')->nullable();
            $table->text('application_notes')->nullable();
            $table->json('verification_documents')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('approval_status');
            $table->index('professional_type');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_health_professionals');
    }
};