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
        Schema::create('alert_preferences', function (Blueprint $table) {
            $table->id();
            
            // Association
            $table->unsignedBigInteger('user_id')->unique(); // One preference record per user
            $table->unsignedBigInteger('farm_record_id')->nullable(); // Optional link to farm record
            
            // Communication Channels
            $table->boolean('sms_enabled')->default(true);
            $table->boolean('email_enabled')->default(false);
            $table->boolean('whatsapp_enabled')->default(false);
            $table->boolean('phone_call_enabled')->default(false);
            $table->boolean('push_notification_enabled')->default(true);
            
            // Contact Information
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('primary_email')->nullable();
            $table->string('secondary_email')->nullable();
            
            // Preferred Contact Method
            $table->enum('preferred_method', ['sms', 'email', 'whatsapp', 'phone', 'push'])->default('sms');
            $table->enum('backup_method', ['sms', 'email', 'whatsapp', 'phone', 'push', 'none'])->default('none');
            
            // ALERT TYPE 1: Outbreak Alerts
            $table->boolean('outbreak_alerts')->default(true);
            $table->boolean('outbreak_sms')->default(true);
            $table->boolean('outbreak_email')->default(false);
            $table->boolean('outbreak_whatsapp')->default(false);
            $table->enum('outbreak_urgency', ['immediate', 'within_hour', 'within_day'])->default('immediate');
            $table->integer('outbreak_radius_km')->default(50); // Alert radius from farm location
            $table->json('outbreak_diseases_interest')->nullable(); // Specific diseases to be alerted about
            
            // ALERT TYPE 2: Vaccine Availability Alerts
            $table->boolean('vaccine_alerts')->default(true);
            $table->boolean('vaccine_sms')->default(true);
            $table->boolean('vaccine_email')->default(false);
            $table->boolean('vaccine_whatsapp')->default(false);
            $table->integer('vaccine_radius_km')->default(30); // Location-based radius
            $table->json('vaccine_types_interest')->nullable(); // Specific vaccines interested in
            
            // ALERT TYPE 3: Awareness Campaigns
            $table->boolean('campaign_alerts')->default(true);
            $table->boolean('campaign_sms')->default(false);
            $table->boolean('campaign_email')->default(true);
            $table->boolean('campaign_whatsapp')->default(false);
            $table->json('campaign_topics_interest')->nullable(); // Disease prevention, nutrition, biosecurity
            $table->json('campaign_formats')->nullable(); // workshops, radio, webinars
            
            // ALERT TYPE 4: Public Announcements
            $table->boolean('announcement_alerts')->default(true);
            $table->boolean('announcement_sms')->default(true);
            $table->boolean('announcement_email')->default(false);
            $table->boolean('announcement_whatsapp')->default(false);
            $table->json('announcement_types')->nullable(); // government directives, market closures, movement restrictions
            
            // Additional Alert Types
            $table->boolean('vaccination_reminder')->default(true);
            $table->boolean('appointment_reminder')->default(true);
            $table->boolean('health_checkup_reminder')->default(true);
            $table->boolean('service_update')->default(true);
            $table->boolean('weather_alerts')->default(false);
            $table->boolean('price_alerts')->default(false);
            $table->boolean('policy_updates')->default(true);
            
            // Timing Preferences
            $table->time('quiet_hours_start')->nullable(); // Don't send alerts during these hours
            $table->time('quiet_hours_end')->nullable();
            $table->json('preferred_days')->nullable(); // Days of week to receive alerts
            $table->boolean('weekend_alerts')->default(true);
            $table->boolean('night_alerts')->default(false); // Allow alerts at night for emergencies
            
            // Frequency Controls
            $table->enum('alert_frequency', ['real_time', 'hourly', 'daily', 'weekly'])->default('real_time');
            $table->integer('max_daily_alerts')->default(10); // Maximum alerts per day
            $table->boolean('digest_mode')->default(false); // Combine multiple alerts into one
            $table->enum('digest_frequency', ['daily', 'weekly', 'monthly'])->nullable();
            
            // Language & Localization
            $table->enum('preferred_language', [
                'english',
                'french',
                'swahili',
                'hausa',
                'fulfulde',
                'yoruba',
                'igbo',
                'pidgin',
                'other'
            ])->default('english');
            $table->string('other_language')->nullable();
            
            // Geographic Preferences
            $table->string('alert_location_village')->nullable();
            $table->string('alert_location_lga')->nullable();
            $table->string('alert_location_state')->nullable();
            $table->json('additional_locations')->nullable(); // Other locations of interest
            
            // Content Preferences
            $table->boolean('detailed_alerts')->default(false); // Short vs detailed messages
            $table->boolean('include_links')->default(true);
            $table->boolean('include_images')->default(false);
            $table->boolean('actionable_only')->default(false); // Only alerts requiring action
            
            // Notification History
            $table->integer('total_alerts_received')->default(0);
            $table->timestamp('last_alert_sent_at')->nullable();
            $table->integer('alerts_this_month')->default(0);
            $table->date('last_digest_sent')->nullable();
            
            // Subscription Management
            $table->boolean('is_subscribed')->default(true);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->text('unsubscribe_reason')->nullable();
            
            // Opt-out Options
            $table->boolean('opt_out_marketing')->default(false);
            $table->boolean('opt_out_promotional')->default(false);
            $table->boolean('opt_out_surveys')->default(false);
            
            // Emergency Override
            $table->boolean('emergency_override')->default(true); // Receive emergency alerts even if unsubscribed
            
            // Device Information
            $table->json('device_tokens')->nullable(); // For push notifications
            $table->string('device_platform')->nullable(); // iOS, Android, Web
            
            // Testing & Verification
            $table->boolean('test_alerts_enabled')->default(false);
            $table->timestamp('last_test_alert_at')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->boolean('email_verified')->default(false);
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->json('custom_preferences')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('farm_record_id')->references('id')->on('farm_records')->onDelete('set null');
            
            // Indexes
            $table->index('user_id');
            $table->index('preferred_method');
            $table->index('is_subscribed');
            $table->index('outbreak_alerts');
            $table->index('vaccine_alerts');
            $table->index('alert_location_state');
            $table->index('alert_location_lga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_preferences');
    }
};