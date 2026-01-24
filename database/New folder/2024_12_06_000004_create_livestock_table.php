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
        Schema::create('livestock', function (Blueprint $table) {
            $table->id();
            
            // Ownership & Association
            $table->unsignedBigInteger('farm_record_id');
            $table->unsignedBigInteger('user_id'); // Owner (individual or farmer)
            $table->unsignedBigInteger('recorded_by')->nullable(); // Data collector who recorded it
            
            // Basic Information
            $table->string('tag_number')->unique()->nullable(); // Unique identifier for the animal
            $table->string('name')->nullable(); // Optional animal name
            $table->enum('livestock_type', [
                'cattle',
                'goat',
                'sheep',
                'pig',
                'chicken',
                'duck',
                'turkey',
                'rabbit',
                'horse',
                'donkey',
                'other'
            ]);
            $table->string('other_type')->nullable(); // If livestock_type is 'other'
            
            // Breed & Genetics
            $table->string('breed')->nullable();
            $table->enum('breed_purity', ['purebred', 'crossbred', 'unknown'])->default('unknown');
            $table->string('breed_origin')->nullable(); // Country/region of breed
            
            // Physical Characteristics
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->string('color')->nullable();
            $table->text('markings')->nullable(); // Distinctive features
            $table->decimal('weight', 8, 2)->nullable(); // in kg
            $table->string('weight_unit')->default('kg');
            $table->decimal('height', 8, 2)->nullable(); // in cm
            
            // Age Information
            $table->date('date_of_birth')->nullable();
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->enum('age_category', ['young', 'adult', 'old', 'unknown'])->default('unknown');
            
            // Origin & Acquisition
            $table->date('acquisition_date')->nullable();
            $table->enum('acquisition_method', ['birth', 'purchase', 'gift', 'inheritance', 'other'])->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('previous_owner')->nullable();
            
            // Parentage (for breeding records)
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->unsignedBigInteger('father_id')->nullable();
            $table->boolean('is_breeding_animal')->default(false);
            $table->integer('offspring_count')->default(0);
            
            // Production Information (for dairy, egg-laying, etc.)
            $table->enum('production_purpose', ['meat', 'dairy', 'eggs', 'breeding', 'work', 'mixed', 'other'])->nullable();
            $table->decimal('daily_milk_production', 8, 2)->nullable(); // liters per day
            $table->integer('monthly_egg_production')->nullable();
            $table->date('last_production_date')->nullable();
            
            // Health Status
            $table->enum('health_status', ['healthy', 'sick', 'recovering', 'deceased'])->default('healthy');
            $table->date('last_health_check')->nullable();
            $table->text('current_conditions')->nullable();
            $table->json('medical_history')->nullable();
            $table->boolean('quarantine_status')->default(false);
            $table->date('quarantine_start_date')->nullable();
            $table->date('quarantine_end_date')->nullable();
            
            // Vaccination Status
            $table->boolean('is_vaccinated')->default(false);
            $table->date('last_vaccination_date')->nullable();
            $table->json('due_vaccinations')->nullable(); // Upcoming vaccinations
            $table->integer('total_vaccinations')->default(0);
            
            // Feeding Information
            $table->json('feed_types')->nullable(); // Types of feed
            $table->decimal('daily_feed_amount', 8, 2)->nullable(); // kg per day
            $table->string('feeding_schedule')->nullable();
            $table->text('dietary_notes')->nullable();
            
            // Location & Housing
            $table->string('housing_type')->nullable(); // pen, barn, free-range, etc.
            $table->string('housing_location')->nullable();
            $table->integer('pen_number')->nullable();
            
            // Status & Lifecycle
            $table->enum('status', ['active', 'sold', 'deceased', 'transferred', 'missing'])->default('active');
            $table->date('status_change_date')->nullable();
            $table->text('status_notes')->nullable();
            
            // Deceased Information
            $table->date('death_date')->nullable();
            $table->string('death_cause')->nullable();
            $table->text('death_notes')->nullable();
            
            // Sale Information
            $table->date('sale_date')->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_contact')->nullable();
            
            // Images & Documents
            $table->json('images')->nullable(); // Array of image paths
            $table->string('primary_image')->nullable();
            
            // Notes & Additional Info
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable(); // For extensibility
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('farm_record_id')->references('id')->on('farm_records')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('mother_id')->references('id')->on('livestock')->onDelete('set null');
            $table->foreign('father_id')->references('id')->on('livestock')->onDelete('set null');
            
            // Indexes
            $table->index('farm_record_id');
            $table->index('user_id');
            $table->index('livestock_type');
            $table->index('tag_number');
            $table->index('health_status');
            $table->index('status');
            $table->index('gender');
            $table->index('is_vaccinated');
            $table->index('age_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock');
    }
};