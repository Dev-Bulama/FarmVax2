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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Role Management
            $table->enum('role', ['admin', 'data_collector', 'individual'])->default('individual');
            
            // Contact Information
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');
            
            // Account Status
            $table->enum('status', ['active', 'suspended', 'pending'])->default('active');
            $table->boolean('is_approved')->default(true); // For data collectors
            
            // Profile
            $table->string('profile_image')->nullable();
            $table->text('bio')->nullable();
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
            
            // Foreign Keys
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('role');
            $table->index('status');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};