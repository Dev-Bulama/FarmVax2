<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VaccinationHistory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vaccination_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'livestock_id',
        'farm_record_id',
        'user_id',
        'recorded_by',
        'livestock_type',
        'number_of_animals',
        'vaccine_name',
        'vaccine_type',
        'disease_target',
        'vaccine_description',
        'manufacturer',
        'batch_number',
        'lot_number',
        'manufacture_date',
        'expiry_date',
        'vaccination_date',
        'vaccination_time',
        'administration_route',
        'injection_site',
        'dosage',
        'dosage_unit',
        'veterinarian_name',
        'veterinarian_license',
        'veterinarian_phone',
        'administrator_name',
        'administrator_type',
        'is_initial_dose',
        'is_booster',
        'dose_number',
        'total_doses_required',
        'next_dose_due_date',
        'next_booster_due_date',
        'booster_interval_days',
        'vaccine_cost',
        'administration_cost',
        'total_cost',
        'currency',
        'payment_method',
        'pre_vaccination_health',
        'pre_vaccination_temperature',
        'post_vaccination_observation',
        'post_vaccination_temperature',
        'adverse_reaction',
        'reaction_severity',
        'reaction_symptoms',
        'reaction_notes',
        'reaction_start_date',
        'reaction_resolution_date',
        'reaction_reported',
        'requires_followup',
        'followup_date',
        'followup_notes',
        'followup_completed',
        'certificate_number',
        'documents',
        'images',
        'vaccination_location',
        'latitude',
        'longitude',
        'campaign_name',
        'program_sponsor',
        'is_government_program',
        'is_subsidized',
        'subsidy_amount',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_notes',
        'status',
        'status_notes',
        'reminder_sent',
        'reminder_sent_at',
        'notes',
        'custom_fields',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'manufacture_date' => 'date',
            'expiry_date' => 'date',
            'vaccination_date' => 'date',
            'vaccination_time' => 'datetime',
            'next_dose_due_date' => 'date',
            'next_booster_due_date' => 'date',
            'reaction_start_date' => 'date',
            'reaction_resolution_date' => 'date',
            'followup_date' => 'date',
            'verified_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
            'reaction_symptoms' => 'array',
            'documents' => 'array',
            'images' => 'array',
            'custom_fields' => 'array',
            'is_initial_dose' => 'boolean',
            'is_booster' => 'boolean',
            'adverse_reaction' => 'boolean',
            'reaction_reported' => 'boolean',
            'requires_followup' => 'boolean',
            'followup_completed' => 'boolean',
            'is_government_program' => 'boolean',
            'is_subsidized' => 'boolean',
            'is_verified' => 'boolean',
            'reminder_sent' => 'boolean',
            'vaccine_cost' => 'decimal:2',
            'administration_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'subsidy_amount' => 'decimal:2',
            'dosage' => 'decimal:2',
            'pre_vaccination_temperature' => 'decimal:2',
            'post_vaccination_temperature' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Relationships
     */

    // Livestock that was vaccinated (Belongs To)
    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    // Farm record (Belongs To)
    public function farmRecord()
    {
        return $this->belongsTo(FarmRecord::class);
    }

    // Owner/Farmer (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Data collector who recorded (Belongs To)
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Admin who verified (Belongs To)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Status Checking Methods
     */

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isMissed()
    {
        return $this->status === 'missed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if vaccine is expired
     */
    public function isVaccineExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    /**
     * Check if adverse reaction occurred
     */
    public function hasAdverseReaction()
    {
        return $this->adverse_reaction === true;
    }

    /**
     * Get reaction severity color
     */
    public function getReactionSeverityColorAttribute()
    {
        return match($this->reaction_severity) {
            'none' => 'green',
            'mild' => 'yellow',
            'moderate' => 'orange',
            'severe' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'completed' => 'green',
            'missed' => 'red',
            'cancelled' => 'gray',
            default => 'yellow',
        };
    }

    /**
     * Check if booster is due
     */
    public function isBoosterDue()
    {
        if (!$this->next_booster_due_date) {
            return false;
        }

        return now()->greaterThanOrEqualTo($this->next_booster_due_date);
    }

    /**
     * Check if next dose is due
     */
    public function isNextDoseDue()
    {
        if (!$this->next_dose_due_date) {
            return false;
        }

        return now()->greaterThanOrEqualTo($this->next_dose_due_date);
    }

    /**
     * Get days until booster
     */
    public function getDaysUntilBoosterAttribute()
    {
        if (!$this->next_booster_due_date) {
            return null;
        }

        return now()->diffInDays($this->next_booster_due_date, false);
    }

    /**
     * Get days until next dose
     */
    public function getDaysUntilNextDoseAttribute()
    {
        if (!$this->next_dose_due_date) {
            return null;
        }

        return now()->diffInDays($this->next_dose_due_date, false);
    }

    /**
     * Get document URLs
     */
    public function getDocumentUrlsAttribute()
    {
        if (!$this->documents || !is_array($this->documents)) {
            return [];
        }

        return array_map(function ($doc) {
            return asset('storage/' . $doc);
        }, $this->documents);
    }

    /**
     * Get image URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    /**
     * Get full vaccination details
     */
    public function getFullDetailsAttribute()
    {
        return [
            'vaccine' => $this->vaccine_name,
            'disease' => $this->disease_target,
            'date' => $this->vaccination_date->format('M d, Y'),
            'veterinarian' => $this->veterinarian_name ?? $this->administrator_name,
            'batch' => $this->batch_number,
            'status' => ucfirst($this->status),
        ];
    }

    /**
     * Scope Queries
     */

    // Get completed vaccinations
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Get scheduled vaccinations
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Get missed vaccinations
    public function scopeMissed($query)
    {
        return $query->where('status', 'missed');
    }

    // Get by vaccine name
    public function scopeByVaccine($query, $vaccineName)
    {
        return $query->where('vaccine_name', $vaccineName);
    }

    // Get by disease target
    public function scopeByDisease($query, $disease)
    {
        return $query->where('disease_target', $disease);
    }

    // Get by livestock type
    public function scopeByLivestockType($query, $type)
    {
        return $query->where('livestock_type', $type);
    }

    // Get with adverse reactions
    public function scopeWithAdverseReactions($query)
    {
        return $query->where('adverse_reaction', true);
    }

    // Get severe reactions
    public function scopeSevereReactions($query)
    {
        return $query->where('reaction_severity', 'severe');
    }

    // Get government programs
    public function scopeGovernmentPrograms($query)
    {
        return $query->where('is_government_program', true);
    }

    // Get subsidized vaccinations
    public function scopeSubsidized($query)
    {
        return $query->where('is_subsidized', true);
    }

    // Get verified records
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Get pending verification
    public function scopePendingVerification($query)
    {
        return $query->where('is_verified', false)
                     ->where('status', 'completed');
    }

    // Get boosters due
    public function scopeBoostersDue($query)
    {
        return $query->whereNotNull('next_booster_due_date')
                     ->where('next_booster_due_date', '<=', now());
    }

    // Get next doses due
    public function scopeNextDosesDue($query)
    {
        return $query->whereNotNull('next_dose_due_date')
                     ->where('next_dose_due_date', '<=', now());
    }

    // Get by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('vaccination_date', [$startDate, $endDate]);
    }

    // Get recent vaccinations (last 30 days)
    public function scopeRecent($query)
    {
        return $query->where('vaccination_date', '>=', now()->subDays(30));
    }

    // Get requiring follow-up
    public function scopeRequiringFollowup($query)
    {
        return $query->where('requires_followup', true)
                     ->where('followup_completed', false);
    }

    // Get by campaign
    public function scopeByCampaign($query, $campaignName)
    {
        return $query->where('campaign_name', $campaignName);
    }

    /**
     * Verification Methods
     */

    public function verify($adminId, $notes = null)
    {
        $this->is_verified = true;
        $this->verified_at = now();
        $this->verified_by = $adminId;
        $this->verification_notes = $notes;
        $this->save();

        return $this;
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->save();

        // Update livestock vaccination status
        if ($this->livestock_id) {
            $livestock = $this->livestock;
            $livestock->is_vaccinated = true;
            $livestock->last_vaccination_date = $this->vaccination_date;
            $livestock->total_vaccinations++;
            $livestock->save();
        }

        return $this;
    }

    /**
     * Schedule reminder
     */
    public function sendReminder()
    {
        $this->reminder_sent = true;
        $this->reminder_sent_at = now();
        $this->save();

        // TODO: Implement actual reminder sending logic (SMS, Email, etc.)

        return $this;
    }

    /**
     * Calculate next booster date
     */
    public function calculateNextBoosterDate($intervalDays = null)
    {
        if (!$intervalDays) {
            $intervalDays = $this->booster_interval_days ?? 365; // Default 1 year
        }

        $this->next_booster_due_date = $this->vaccination_date->addDays($intervalDays);
        $this->booster_interval_days = $intervalDays;
        $this->save();

        return $this;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a vaccination record, set default values
        static::creating(function ($vaccination) {
            if (!$vaccination->status) {
                $vaccination->status = 'completed';
            }

            if (!$vaccination->dosage_unit) {
                $vaccination->dosage_unit = 'ml';
            }

            if (!$vaccination->currency) {
                $vaccination->currency = 'NGN';
            }

            if (!$vaccination->reaction_severity) {
                $vaccination->reaction_severity = 'none';
            }

            // Calculate total cost
            if ($vaccination->vaccine_cost || $vaccination->administration_cost) {
                $vaccination->total_cost = 
                    ($vaccination->vaccine_cost ?? 0) + 
                    ($vaccination->administration_cost ?? 0);
            }
        });

        // After updating, recalculate total cost
        static::updating(function ($vaccination) {
            if ($vaccination->isDirty(['vaccine_cost', 'administration_cost'])) {
                $vaccination->total_cost = 
                    ($vaccination->vaccine_cost ?? 0) + 
                    ($vaccination->administration_cost ?? 0);
            }
        });
    }
}