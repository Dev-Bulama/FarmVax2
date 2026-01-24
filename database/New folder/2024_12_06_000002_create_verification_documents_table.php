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
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('data_collector_profile_id')->nullable();
            
            // Document Information
            $table->string('document_type'); // ID Card, Certificate, Authorization Letter, etc.
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // PDF, JPG, PNG, etc.
            $table->integer('file_size')->nullable(); // in bytes
            
            // Document Details
            $table->string('document_number')->nullable(); // Certificate number, ID number, etc.
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_authority')->nullable(); // Who issued the document
            
            // Verification Status
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            
            // Document Category
            $table->enum('category', [
                'identification',
                'educational',
                'professional',
                'authorization',
                'reference',
                'other'
            ])->default('other');
            
            // Visibility & Access
            $table->boolean('is_primary')->default(false); // Is this the main document for verification
            $table->boolean('is_visible_to_admin')->default(true);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('data_collector_profile_id')->references('id')->on('data_collector_profiles')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('user_id');
            $table->index('document_type');
            $table->index('verification_status');
            $table->index('category');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
    }
};