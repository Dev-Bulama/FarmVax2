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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Volunteer Details
            $table->string('organization')->nullable();
            $table->string('assigned_area')->nullable();
            $table->text('motivation')->nullable(); // Why they want to volunteer
            
            // Contact Information
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Activity Tracking
            $table->integer('farmers_enrolled')->default(0); // Count of farmers they've enrolled
            $table->boolean('is_active')->default(true);
            
            // Approval Status
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved'); // Auto-approve volunteers
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Application Details
            $table->timestamp('submitted_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('approval_status');
            $table->index('is_active');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};