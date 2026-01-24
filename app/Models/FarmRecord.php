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
        'farmer_lga',
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS - ALL FIXED
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user who created this record (Data Collector)
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
     * Get all livestock records associated with this farm record
     * ⚠️ THIS WAS MISSING - NOW FIXED!
     */
    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'farm_record_id');
    }

    /**
     * Get vaccination history for this farm record
     * ⚠️ THIS WAS MISSING - NOW FIXED!
     */
    public function vaccinationHistory()
    {
        return $this->hasMany(VaccinationHistory::class, 'farm_record_id');
    }

    /**
     * Get service requests for this farm record
     * ⚠️ THIS WAS MISSING - NOW FIXED!
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'farm_record_id');
    }

    /**
     * Get alert preferences for this farm record
     * ⚠️ THIS WAS MISSING - NOW FIXED!
     */
    public function alertPreferences()
    {
        return $this->hasOne(AlertPreference::class, 'farm_record_id');
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

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

    /**
     * Scope: Get records by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get records by farmer
     */
    public function scopeByFarmer($query, $farmerId)
    {
        return $query->where('farmer_id', $farmerId);
    }

    /**
     * Scope: Get records by state
     */
    public function scopeByState($query, $state)
    {
        return $query->where('farmer_state', $state);
    }

    /**
     * Scope: Get records by LGA
     */
    public function scopeByLga($query, $lga)
    {
        return $query->where('farmer_lga', $lga);
    }

    /**
     * Scope: Get recent records
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if record is editable
     */
    public function isEditable()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Check if record is pending approval
     */
    public function isPending()
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    /**
     * Check if record is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if record is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplay()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get formatted submission date
     */
    public function getSubmittedDateAttribute()
    {
        return $this->submitted_at ? $this->submitted_at->format('M d, Y') : 'Not submitted';
    }

    /**
     * Get formatted approval date
     */
    public function getApprovedDateAttribute()
    {
        return $this->approved_at ? $this->approved_at->format('M d, Y') : 'Not approved';
    }
}