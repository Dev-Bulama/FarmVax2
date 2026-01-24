<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmRecord extends Model
{
    use SoftDeletes;

    /**
     * CRITICAL: Set the correct table name
     */
    protected $table = 'farm_records';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // Record Ownership
        'user_id',
        'farmer_id',
        'created_by_role',
        
        // Step 1: Stakeholder Information
        'farmer_name',
        'farmer_email',
        'farmer_phone',
        'farmer_address',
        'farmer_city',
        'farmer_state',
        'farmer_lga',  // ← CORRECT column name
        'farm_name',
        'farm_size',
        'farm_size_unit',
        'latitude',
        'longitude',
        'farm_type',
        'average_household_size',
        
        // Step 2: Livestock Profile
        'total_livestock_count',
        'livestock_types',
        'young_count',
        'adult_count',
        'old_count',
        
        // Step 3: Health & Vaccination
        'last_vaccination_date',
        'has_health_issues',
        'current_health_issues',
        'health_notes',
        'veterinarian_name',
        'veterinarian_phone',
        'last_vet_visit',
        'past_diseases',
        
        // Step 4: Service Needs
        'service_needs',
        'urgency_level',
        'service_description',
        'preferred_service_date',
        'needs_immediate_attention',
        
        // Step 5: Alert Preferences
        'sms_alerts',
        'email_alerts',
        'phone_alerts',
        'alert_types',
        'preferred_contact_method',
        'alternative_phone',
        
        // Step 6: Consent & Feedback
        'data_sharing_consent',
        'research_participation_consent',
        'marketing_consent',
        'additional_comments',
        'feedback',
        
        // Status
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        'admin_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'livestock_types' => 'array',
        'current_health_issues' => 'array',
        'past_diseases' => 'array',
        'service_needs' => 'array',
        'alert_types' => 'array',
        'has_health_issues' => 'boolean',
        'needs_immediate_attention' => 'boolean',
        'sms_alerts' => 'boolean',
        'email_alerts' => 'boolean',
        'phone_alerts' => 'boolean',
        'data_sharing_consent' => 'boolean',
        'research_participation_consent' => 'boolean',
        'marketing_consent' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'last_vaccination_date' => 'date',
        'last_vet_visit' => 'date',
        'preferred_service_date' => 'date',
    ];

    /**
     * Get the user who created this record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the farmer this record belongs to
     */
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Get the admin who approved this record
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Get draft records
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Get submitted records
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope: Get approved records
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected records
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get records under review
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }
}