<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'farm_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'farmer_id',
        'created_by_role',
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
        'location_accuracy',
        'farm_type',
        'farming_purpose',
        'total_livestock_count',
        'livestock_types',
        'livestock_details',
        'young_count',
        'adult_count',
        'old_count',
        'breed_information',
        'last_vaccination_date',
        'vaccination_history',
        'has_health_issues',
        'current_health_issues',
        'health_notes',
        'veterinarian_name',
        'veterinarian_phone',
        'last_vet_visit',
        'disease_outbreak_history',
        'past_diseases',
        'disease_notes',
        'service_needs',
        'urgency_level',
        'service_description',
        'preferred_service_date',
        'needs_immediate_attention',
        'sms_alerts',
        'email_alerts',
        'phone_alerts',
        'alert_types',
        'preferred_contact_method',
        'alternative_phone',
        'data_sharing_consent',
        'research_participation_consent',
        'marketing_consent',
        'additional_comments',
        'feedback',
        'status',
        'admin_notes',
        'submitted_at',
        'approved_at',
        'approved_by',
        'is_verified',
        'data_completeness_score',
        'validation_errors',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'farming_purpose' => 'array',
            'livestock_types' => 'array',
            'livestock_details' => 'array',
            'breed_information' => 'array',
            'vaccination_history' => 'array',
            'current_health_issues' => 'array',
            'past_diseases' => 'array',
            'service_needs' => 'array',
            'alert_types' => 'array',
            'validation_errors' => 'array',
            'last_vaccination_date' => 'date',
            'last_vet_visit' => 'date',
            'preferred_service_date' => 'date',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'has_health_issues' => 'boolean',
            'disease_outbreak_history' => 'boolean',
            'needs_immediate_attention' => 'boolean',
            'sms_alerts' => 'boolean',
            'email_alerts' => 'boolean',
            'phone_alerts' => 'boolean',
            'data_sharing_consent' => 'boolean',
            'research_participation_consent' => 'boolean',
            'marketing_consent' => 'boolean',
            'is_verified' => 'boolean',
            'data_completeness_score' => 'decimal:2',
            'farm_size' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Relationships
     */

    // User who created the record (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Actual farmer (if different from creator - Belongs To)
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    // Admin who approved the record (Belongs To)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Livestock associated with this farm (One-to-Many)
    public function livestock()
    {
        return $this->hasMany(Livestock::class);
    }

    // Vaccination history for this farm (One-to-Many)
    public function vaccinationHistory()
    {
        return $this->hasMany(VaccinationHistory::class);
    }

    // Service requests for this farm (One-to-Many)
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    // Alert preferences for this farm (One-to-One)
    public function alertPreferences()
    {
        return $this->hasOne(AlertPreference::class);
    }

    /**
     * Status Checking Methods
     */

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Role Checking Methods
     */

    public function createdByIndividual()
    {
        return $this->created_by_role === 'individual';
    }

    public function createdByDataCollector()
    {
        return $this->created_by_role === 'data_collector';
    }

    /**
     * Get farm location as string
     */
    public function getFullLocationAttribute()
    {
        $parts = array_filter([
            $this->farmer_address,
            $this->farmer_lga,
            $this->farmer_city,
            $this->farmer_state,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get Google Maps URL
     */
    public function getMapUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    /**
     * Get livestock summary
     */
    public function getLivestockSummaryAttribute()
    {
        if (!$this->livestock_types || !is_array($this->livestock_types)) {
            return 'No livestock recorded';
        }

        $summary = [];
        foreach ($this->livestock_types as $type) {
            $count = $this->livestock_details[$type]['count'] ?? 0;
            $summary[] = "$count " . ucfirst($type);
        }

        return implode(', ', $summary);
    }

    /**
     * Get total livestock from details
     */
    public function getTotalLivestockAttribute()
    {
        if (!$this->livestock_details || !is_array($this->livestock_details)) {
            return 0;
        }

        $total = 0;
        foreach ($this->livestock_details as $details) {
            $total += $details['count'] ?? 0;
        }

        return $total;
    }

    /**
     * Get vaccination status badge
     */
    public function getVaccinationStatusBadgeAttribute()
    {
        if (!$this->last_vaccination_date) {
            return 'Not Vaccinated';
        }

        $daysSince = now()->diffInDays($this->last_vaccination_date);

        if ($daysSince <= 90) {
            return 'Up to Date';
        } elseif ($daysSince <= 180) {
            return 'Due Soon';
        } else {
            return 'Overdue';
        }
    }

    /**
     * Get urgency badge color
     */
    public function getUrgencyBadgeColorAttribute()
    {
        return match($this->urgency_level) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'emergency' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope Queries
     */

    // Get by status
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Get by role
    public function scopeByIndividuals($query)
    {
        return $query->where('created_by_role', 'individual');
    }

    public function scopeByDataCollectors($query)
    {
        return $query->where('created_by_role', 'data_collector');
    }

    // Get urgent records
    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency_level', ['high', 'emergency'])
                     ->orWhere('needs_immediate_attention', true);
    }

    // Get records with health issues
    public function scopeWithHealthIssues($query)
    {
        return $query->where('has_health_issues', true);
    }

    // Get records with disease history
    public function scopeWithDiseaseHistory($query)
    {
        return $query->where('disease_outbreak_history', true);
    }

    // Get by location (state)
    public function scopeByState($query, $state)
    {
        return $query->where('farmer_state', $state);
    }

    // Get by LGA
    public function scopeByLga($query, $lga)
    {
        return $query->where('farmer_lga', $lga);
    }

    // Get verified records
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Get records needing attention
    public function scopeNeedingAttention($query)
    {
        return $query->where('needs_immediate_attention', true);
    }

    // Get recently submitted (last 7 days)
    public function scopeRecentlySubmitted($query)
    {
        return $query->where('submitted_at', '>=', now()->subDays(7));
    }

    // Get by farm type
    public function scopeByFarmType($query, $type)
    {
        return $query->where('farm_type', $type);
    }

    // Get records within radius (requires latitude/longitude)
    public function scopeWithinRadius($query, $lat, $lng, $radiusKm)
    {
        $haversine = "(6371 * acos(cos(radians($lat)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians($lng)) 
                        + sin(radians($lat)) 
                        * sin(radians(latitude))))";
        
        return $query->whereNotNull('latitude')
                     ->whereNotNull('longitude')
                     ->whereRaw("{$haversine} < ?", [$radiusKm]);
    }

    /**
     * Workflow Methods
     */

    public function submit()
    {
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->save();

        return $this;
    }

    public function markUnderReview()
    {
        $this->status = 'under_review';
        $this->save();

        return $this;
    }

    public function approve($adminId, $notes = null)
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = $adminId;
        $this->is_verified = true;
        
        if ($notes) {
            $this->admin_notes = $notes;
        }

        $this->save();

        return $this;
    }

    public function reject($adminId, $notes = null)
    {
        $this->status = 'rejected';
        
        if ($notes) {
            $this->admin_notes = $notes;
        }

        $this->save();

        return $this;
    }

    /**
     * Calculate data completeness score
     */
    public function calculateCompletenessScore()
    {
        $requiredFields = [
            'farmer_name',
            'farmer_phone',
            'farmer_address',
            'farmer_city',
            'farmer_state',
            'total_livestock_count',
            'livestock_types',
        ];

        $optionalFields = [
            'farmer_email',
            'farm_name',
            'latitude',
            'longitude',
            'veterinarian_name',
            'last_vaccination_date',
            'service_needs',
        ];

        $requiredScore = 0;
        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $requiredScore++;
            }
        }

        $optionalScore = 0;
        foreach ($optionalFields as $field) {
            if (!empty($this->$field)) {
                $optionalScore++;
            }
        }

        // Required fields worth 70%, optional worth 30%
        $score = (($requiredScore / count($requiredFields)) * 70) + 
                 (($optionalScore / count($optionalFields)) * 30);

        $this->data_completeness_score = round($score, 2);
        $this->save();

        return $this->data_completeness_score;
    }

    /**
     * Check if record is complete
     */
    public function isComplete()
    {
        return $this->data_completeness_score >= 80.00;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a record, set default values
        static::creating(function ($record) {
            if (!$record->status) {
                $record->status = 'draft';
            }

            if (!$record->urgency_level) {
                $record->urgency_level = 'low';
            }

            if (!$record->farm_size_unit) {
                $record->farm_size_unit = 'hectares';
            }

            if (!$record->preferred_contact_method) {
                $record->preferred_contact_method = 'sms';
            }
        });

        // After saving, calculate completeness score
        static::saved(function ($record) {
            if ($record->status !== 'draft') {
                $record->calculateCompletenessScore();
            }
        });
    }
}