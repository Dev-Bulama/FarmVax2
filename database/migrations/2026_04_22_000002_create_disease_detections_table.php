<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('disease_detections')) { return; }
        Schema::create('disease_detections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('livestock_id')->nullable();
            $table->string('image_path');                            // stored path
            $table->string('animal_type', 50)->nullable();           // cattle, goat, etc.
            $table->string('symptoms_reported', 1000)->nullable();   // optional text from user
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->boolean('is_sick')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();   // 0-100
            $table->text('analysis_result')->nullable();             // full AI text
            $table->json('detected_conditions')->nullable();         // [{name, probability, severity}]
            $table->text('recommendations')->nullable();
            $table->string('urgency_level', 20)->nullable();         // low, medium, high, critical
            $table->string('ai_model', 80)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('set null');
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_detections');
    }
};
