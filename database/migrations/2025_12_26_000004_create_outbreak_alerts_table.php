<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbreak_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('disease_name');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('location_state')->nullable();
            $table->string('location_lga')->nullable();
            $table->string('location_village')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('radius_km')->default(50); // Alert radius
            $table->date('outbreak_date');
            $table->enum('status', ['active', 'contained', 'resolved'])->default('active');
            $table->text('precautions')->nullable();
            $table->text('symptoms')->nullable();
            $table->json('affected_animals')->nullable(); // ['cattle', 'goats', etc.]
            $table->integer('confirmed_cases')->default(0);
            $table->integer('deaths')->default(0);
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('outbreak_alert_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outbreak_alert_id')->constrained('outbreak_alerts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('channel', ['email', 'sms', 'push', 'whatsapp']);
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbreak_alert_notifications');
        Schema::dropIfExists('outbreak_alerts');
    }
};
