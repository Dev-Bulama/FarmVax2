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
        Schema::create('user_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imported_by')->constrained('users')->onDelete('cascade');
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->enum('user_type', ['farmer', 'volunteer', 'animal_health_professional'])->default('farmer');
            $table->integer('total_records')->default(0);
            $table->integer('successful_imports')->default(0);
            $table->integer('failed_imports')->default(0);
            $table->integer('duplicate_emails')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('column_mapping')->nullable(); // Stores which Excel columns map to which fields
            $table->json('errors')->nullable(); // Stores error details for failed imports
            $table->json('imported_user_ids')->nullable(); // Stores IDs of successfully imported users
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('imported_by');
            $table->index('status');
            $table->index('user_type');
            $table->index('created_at');
        });
        
        // Create table for tracking individual imported users and their email status
        Schema::create('imported_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('user_imports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('generated_password'); // Encrypted password for reference
            $table->boolean('welcome_email_sent')->default(false);
            $table->timestamp('welcome_email_sent_at')->nullable();
            $table->integer('email_resend_count')->default(0);
            $table->timestamp('last_email_sent_at')->nullable();
            $table->timestamps();
            
            $table->index('import_id');
            $table->index('user_id');
            $table->index('welcome_email_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_users');
        Schema::dropIfExists('user_imports');
    }
};