<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertPreference extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_preferences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'farm_record_id',
        'sms_enabled',
        'email_enabled',
        'whatsapp_enabled',
        'phone_call_enabled',
        'push_notification_enabled',
        'primary_phone',
        'secondary_phone',
        'whatsapp_number',
        'primary_email',
        'secondary_email',
        'preferred_method',
        'backup_method',
        'outbreak_alerts',
        'outbreak_sms',
        'outbreak_email',
        'outbreak_whatsapp',
        'outbreak_urgency',
        'outbreak_radius_km',
        'outbreak_diseases_interest',
        'vaccine_alerts',
        'vaccine_sms',
        'vaccine_email',
        'vaccine_whatsapp',
        'vaccine_radius_km',
        'vaccine_types_interest',
        'campaign_alerts',
        'campaign_sms',
        'campaign_email',
        'campaign_whatsapp',
        'campaign_topics_interest',
        'campaign_formats',
        'announcement_alerts',
        'announcement_sms',
        'announcement_email',
        'announcement_whatsapp',
        'announcement_types',
        'vaccination_reminder',
        'appointment_reminder',
        'health_checkup_reminder',
        'service_update',
        'weather_alerts',
        'price_alerts',
        'policy_updates',
        'quiet_hours_start',
        'quiet_hours_end',
        'preferred_days',
        'weekend_alerts',
        'night_alerts',
        'alert_frequency',
        'max_daily_alerts',
        'digest_mode',
        'digest_frequency',
        'preferred_language',
        'other_language',
        'alert_location_village',
        'alert_location_lga',
        'alert_location_state',
        'additional_locations',
        'detailed_alerts',
        'include_links',
        'include_images',
        'actionable_only',
        'total_alerts_received',
        'last_alert_sent_at',
        'alerts_this_month',
        'last_digest_sent',
        'is_subscribed',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_reason',
        'opt_out_marketing',
        'opt_out_promotional',
        'opt_out_surveys',
        'emergency_override',
        'device_tokens',
        'device_platform',
        'test_alerts_enabled',
        'last_test_alert_at',
        'phone_verified',
        'email_verified',
        'phone_verified_at',
        'email_verified_at',
        'notes',
        'custom_preferences',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quiet_hours_start' => 'datetime',
            'quiet_hours_end' => 'datetime',
            'last_alert_sent_at' => 'datetime',
            'last_digest_sent' => 'date',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
            'last_test_alert_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'outbreak_diseases_interest' => 'array',
            'vaccine_types_interest' => 'array',
            'campaign_topics_interest' => 'array',
            'campaign_formats' => 'array',
            'announcement_types' => 'array',
            'preferred_days' => 'array',
            'additional_locations' => 'array',
            'device_tokens' => 'array',
            'custom_preferences' => 'array',
            'sms_enabled' => 'boolean',
            'email_enabled' => 'boolean',
            'whatsapp_enabled' => 'boolean',
            'phone_call_enabled' => 'boolean',
            'push_notification_enabled' => 'boolean',
            'outbreak_alerts' => 'boolean',
            'outbreak_sms' => 'boolean',
            'outbreak_email' => 'boolean',
            'outbreak_whatsapp' => 'boolean',
            'vaccine_alerts' => 'boolean',
            'vaccine_sms' => 'boolean',
            'vaccine_email' => 'boolean',
            'vaccine_whatsapp' => 'boolean',
            'campaign_alerts' => 'boolean',
            'campaign_sms' => 'boolean',
            'campaign_email' => 'boolean',
            'campaign_whatsapp' => 'boolean',
            'announcement_alerts' => 'boolean',
            'announcement_sms' => 'boolean',
            'announcement_email' => 'boolean',
            'announcement_whatsapp' => 'boolean',
            'vaccination_reminder' => 'boolean',
            'appointment_reminder' => 'boolean',
            'health_checkup_reminder' => 'boolean',
            'service_update' => 'boolean',
            'weather_alerts' => 'boolean',
            'price_alerts' => 'boolean',
            'policy_updates' => 'boolean',
            'weekend_alerts' => 'boolean',
            'night_alerts' => 'boolean',
            'digest_mode' => 'boolean',
            'detailed_alerts' => 'boolean',
            'include_links' => 'boolean',
            'include_images' => 'boolean',
            'actionable_only' => 'boolean',
            'is_subscribed' => 'boolean',
            'opt_out_marketing' => 'boolean',
            'opt_out_promotional' => 'boolean',
            'opt_out_surveys' => 'boolean',
            'emergency_override' => 'boolean',
            'test_alerts_enabled' => 'boolean',
            'phone_verified' => 'boolean',
            'email_verified' => 'boolean',
        ];
    }

    /**
     * Relationships
     */

    // User who owns these preferences (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Farm record (Belongs To - Optional)
    public function farmRecord()
    {
        return $this->belongsTo(FarmRecord::class);
    }

    /**
     * Subscription Status Methods
     */

    public function isSubscribed()
    {
        return $this->is_subscribed === true;
    }

    public function isUnsubscribed()
    {
        return $this->is_subscribed === false;
    }

    /**
     * Channel Check Methods
     */

    public function canSendSms()
    {
        return $this->sms_enabled && $this->primary_phone;
    }

    public function canSendEmail()
    {
        return $this->email_enabled && $this->primary_email;
    }

    public function canSendWhatsApp()
    {
        return $this->whatsapp_enabled && $this->whatsapp_number;
    }

    public function canSendPush()
    {
        return $this->push_notification_enabled && $this->device_tokens;
    }

    /**
     * Alert Type Check Methods
     */

    public function wantsOutbreakAlerts()
    {
        return $this->outbreak_alerts === true;
    }

    public function wantsVaccineAlerts()
    {
        return $this->vaccine_alerts === true;
    }

    public function wantsCampaignAlerts()
    {
        return $this->campaign_alerts === true;
    }

    public function wantsAnnouncementAlerts()
    {
        return $this->announcement_alerts === true;
    }

    /**
     * Get preferred contact channels for specific alert type
     */

    public function getOutbreakChannels()
    {
        $channels = [];
        
        if ($this->outbreak_sms && $this->canSendSms()) {
            $channels[] = 'sms';
        }
        
        if ($this->outbreak_email && $this->canSendEmail()) {
            $channels[] = 'email';
        }
        
        if ($this->outbreak_whatsapp && $this->canSendWhatsApp()) {
            $channels[] = 'whatsapp';
        }
        
        return $channels;
    }

    public function getVaccineChannels()
    {
        $channels = [];
        
        if ($this->vaccine_sms && $this->canSendSms()) {
            $channels[] = 'sms';
        }
        
        if ($this->vaccine_email && $this->canSendEmail()) {
            $channels[] = 'email';
        }
        
        if ($this->vaccine_whatsapp && $this->canSendWhatsApp()) {
            $channels[] = 'whatsapp';
        }
        
        return $channels;
    }

    public function getCampaignChannels()
    {
        $channels = [];
        
        if ($this->campaign_sms && $this->canSendSms()) {
            $channels[] = 'sms';
        }
        
        if ($this->campaign_email && $this->canSendEmail()) {
            $channels[] = 'email';
        }
        
        if ($this->campaign_whatsapp && $this->canSendWhatsApp()) {
            $channels[] = 'whatsapp';
        }
        
        return $channels;
    }

    public function getAnnouncementChannels()
    {
        $channels = [];
        
        if ($this->announcement_sms && $this->canSendSms()) {
            $channels[] = 'sms';
        }
        
        if ($this->announcement_email && $this->canSendEmail()) {
            $channels[] = 'email';
        }
        
        if ($this->announcement_whatsapp && $this->canSendWhatsApp()) {
            $channels[] = 'whatsapp';
        }
        
        return $channels;
    }

    /**
     * Check if in quiet hours
     */

    public function isInQuietHours()
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now();
        $start = $this->quiet_hours_start;
        $end = $this->quiet_hours_end;

        // Handle cases where quiet hours span midnight
        if ($start->lessThan($end)) {
            return $now->between($start, $end);
        } else {
            return $now->greaterThanOrEqualTo($start) || $now->lessThanOrEqualTo($end);
        }
    }

    /**
     * Check if reached max daily alerts
     */

    public function hasReachedDailyLimit()
    {
        if (!$this->max_daily_alerts) {
            return false;
        }

        return $this->alerts_this_month >= $this->max_daily_alerts;
    }

    /**
     * Check if can send alert now
     */

    public function canSendAlertNow($isEmergency = false)
    {
        // Emergency override bypasses all restrictions
        if ($isEmergency && $this->emergency_override) {
            return true;
        }

        // Check if subscribed
        if (!$this->isSubscribed()) {
            return false;
        }

        // Check quiet hours (unless emergency)
        if (!$isEmergency && $this->isInQuietHours()) {
            return false;
        }

        // Check daily limit
        if ($this->hasReachedDailyLimit()) {
            return false;
        }

        // Check weekend alerts
        if (!$this->weekend_alerts && now()->isWeekend()) {
            return false;
        }

        // Check night alerts
        if (!$this->night_alerts && now()->hour >= 22 || now()->hour <= 6) {
            return false;
        }

        return true;
    }

    /**
     * Increment alert count
     */

    public function incrementAlertCount()
    {
        $this->total_alerts_received++;
        $this->alerts_this_month++;
        $this->last_alert_sent_at = now();
        $this->save();

        return $this;
    }

    /**
     * Reset monthly alert count
     */

    public function resetMonthlyCount()
    {
        $this->alerts_this_month = 0;
        $this->save();

        return $this;
    }

    /**
     * Subscribe to alerts
     */

    public function subscribe()
    {
        $this->is_subscribed = true;
        $this->subscribed_at = now();
        $this->unsubscribed_at = null;
        $this->unsubscribe_reason = null;
        $this->save();

        return $this;
    }

    /**
     * Unsubscribe from alerts
     */

    public function unsubscribe($reason = null)
    {
        $this->is_subscribed = false;
        $this->unsubscribed_at = now();
        $this->unsubscribe_reason = $reason;
        $this->save();

        return $this;
    }

    /**
     * Verify phone number
     */

    public function verifyPhone()
    {
        $this->phone_verified = true;
        $this->phone_verified_at = now();
        $this->save();

        return $this;
    }

    /**
     * Verify email address
     */

    public function verifyEmail()
    {
        $this->email_verified = true;
        $this->email_verified_at = now();
        $this->save();

        return $this;
    }

    /**
     * Add device token for push notifications
     */

    public function addDeviceToken($token, $platform = null)
    {
        $tokens = $this->device_tokens ?? [];
        
        if (!in_array($token, $tokens)) {
            $tokens[] = $token;
            $this->device_tokens = $tokens;
            
            if ($platform) {
                $this->device_platform = $platform;
            }
            
            $this->save();
        }

        return $this;
    }

    /**
     * Remove device token
     */

    public function removeDeviceToken($token)
    {
        $tokens = $this->device_tokens ?? [];
        $tokens = array_filter($tokens, fn($t) => $t !== $token);
        $this->device_tokens = array_values($tokens);
        $this->save();

        return $this;
    }

    /**
     * Scope Queries
     */

    // Get subscribed users
    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }

    // Get users wanting outbreak alerts
    public function scopeWantingOutbreakAlerts($query)
    {
        return $query->where('outbreak_alerts', true)
                     ->where('is_subscribed', true);
    }

    // Get users wanting vaccine alerts
    public function scopeWantingVaccineAlerts($query)
    {
        return $query->where('vaccine_alerts', true)
                     ->where('is_subscribed', true);
    }

    // Get users wanting campaign alerts
    public function scopeWantingCampaignAlerts($query)
    {
        return $query->where('campaign_alerts', true)
                     ->where('is_subscribed', true);
    }

    // Get users wanting announcement alerts
    public function scopeWantingAnnouncementAlerts($query)
    {
        return $query->where('announcement_alerts', true)
                     ->where('is_subscribed', true);
    }

    // Get by state
    public function scopeByState($query, $state)
    {
        return $query->where('alert_location_state', $state);
    }

    // Get by LGA
    public function scopeByLga($query, $lga)
    {
        return $query->where('alert_location_lga', $lga);
    }

    // Get by language
    public function scopeByLanguage($query, $language)
    {
        return $query->where('preferred_language', $language);
    }

    // Get users with verified phone
    public function scopePhoneVerified($query)
    {
        return $query->where('phone_verified', true);
    }

    // Get users with verified email
    public function scopeEmailVerified($query)
    {
        return $query->where('email_verified', true);
    }

    // Get users with SMS enabled
    public function scopeSmsEnabled($query)
    {
        return $query->where('sms_enabled', true)
                     ->whereNotNull('primary_phone');
    }

    // Get users with email enabled
    public function scopeEmailEnabled($query)
    {
        return $query->where('email_enabled', true)
                     ->whereNotNull('primary_email');
    }

    // Get users with WhatsApp enabled
    public function scopeWhatsAppEnabled($query)
    {
        return $query->where('whatsapp_enabled', true)
                     ->whereNotNull('whatsapp_number');
    }

    /**
     * Get summary of alert preferences
     */

    public function getSummaryAttribute()
    {
        $enabled = [];
        
        if ($this->outbreak_alerts) $enabled[] = 'Outbreaks';
        if ($this->vaccine_alerts) $enabled[] = 'Vaccines';
        if ($this->campaign_alerts) $enabled[] = 'Campaigns';
        if ($this->announcement_alerts) $enabled[] = 'Announcements';
        if ($this->vaccination_reminder) $enabled[] = 'Vaccination Reminders';
        
        return implode(', ', $enabled) ?: 'No alerts enabled';
    }

    /**
     * Get enabled channels summary
     */

    public function getEnabledChannelsAttribute()
    {
        $channels = [];
        
        if ($this->sms_enabled) $channels[] = 'SMS';
        if ($this->email_enabled) $channels[] = 'Email';
        if ($this->whatsapp_enabled) $channels[] = 'WhatsApp';
        if ($this->push_notification_enabled) $channels[] = 'Push';
        
        return implode(', ', $channels) ?: 'No channels enabled';
    }

    /**
     * Boot method
     */

    protected static function boot()
    {
        parent::boot();

        // When creating preferences, set default values
        static::creating(function ($preference) {
            // Enable SMS by default
            if (!isset($preference->sms_enabled)) {
                $preference->sms_enabled = true;
            }

            // Enable outbreak alerts by default
            if (!isset($preference->outbreak_alerts)) {
                $preference->outbreak_alerts = true;
                $preference->outbreak_sms = true;
            }

            // Enable vaccine alerts by default
            if (!isset($preference->vaccine_alerts)) {
                $preference->vaccine_alerts = true;
                $preference->vaccine_sms = true;
            }

            // Set default preferred method
            if (!$preference->preferred_method) {
                $preference->preferred_method = 'sms';
            }

            // Set default language
            if (!$preference->preferred_language) {
                $preference->preferred_language = 'english';
            }

            // Set default radius
            if (!$preference->outbreak_radius_km) {
                $preference->outbreak_radius_km = 50;
            }

            if (!$preference->vaccine_radius_km) {
                $preference->vaccine_radius_km = 30;
            }

            // Subscribe by default
            if (!isset($preference->is_subscribed)) {
                $preference->is_subscribed = true;
                $preference->subscribed_at = now();
            }

            // Enable emergency override by default
            if (!isset($preference->emergency_override)) {
                $preference->emergency_override = true;
            }

            // Set default alert frequency
            if (!$preference->alert_frequency) {
                $preference->alert_frequency = 'real_time';
            }

            // Initialize counters
            $preference->total_alerts_received = 0;
            $preference->alerts_this_month = 0;
        });
    }
}