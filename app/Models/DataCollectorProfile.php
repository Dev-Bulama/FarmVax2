<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataCollectorProfile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_collector_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'organization',
        'position',
        'employee_id',
        'reason_for_applying',
        'experience',
        'education_level',
        'id_card_type',
        'id_card_number',
        'id_card_document',
        'certificates',
        'professional_certification',
        'assigned_territory',
        'coverage_area',
        'work_regions',
        'verification_document',
        'reference_name',
        'reference_phone',
        'reference_email',
        'approval_status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'total_submissions',
        'approved_submissions',
        'accuracy_rate',
        'last_submission_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'certificates' => 'array',
            'work_regions' => 'array',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'last_submission_at' => 'datetime',
            'accuracy_rate' => 'decimal:2',
        ];
    }

    /**
     * Relationships
     */

    // User who owns this profile (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verification documents associated with this profile (One-to-Many)
    public function verificationDocuments()
    {
        return $this->hasMany(VerificationDocument::class);
    }

    // Admin who reviewed this profile (Belongs To)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Status Checking Methods
     */

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function isUnderReview()
    {
        return $this->approval_status === 'under_review';
    }

    /**
     * Get ID card document URL
     */
    public function getIdCardDocumentUrlAttribute()
    {
        if ($this->id_card_document) {
            return asset('storage/' . $this->id_card_document);
        }
        return null;
    }

    /**
     * Get verification document URL
     */
    public function getVerificationDocumentUrlAttribute()
    {
        if ($this->verification_document) {
            return asset('storage/' . $this->verification_document);
        }
        return null;
    }

    /**
     * Get certificate URLs
     */
    public function getCertificateUrlsAttribute()
    {
        if (!$this->certificates || !is_array($this->certificates)) {
            return [];
        }

        return array_map(function ($cert) {
            return asset('storage/' . $cert);
        }, $this->certificates);
    }

    /**
     * Calculate performance percentage
     */
    public function getPerformancePercentageAttribute()
    {
        if ($this->total_submissions == 0) {
            return 0;
        }

        return round(($this->approved_submissions / $this->total_submissions) * 100, 2);
    }

    /**
     * Get full reference contact
     */
    public function getFullReferenceContactAttribute()
    {
        $parts = array_filter([
            $this->reference_name,
            $this->reference_phone,
            $this->reference_email,
        ]);

        return implode(' | ', $parts);
    }

    /**
     * Scope Queries
     */

    // Get pending applications
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // Get approved profiles
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // Get rejected profiles
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    // Get profiles under review
    public function scopeUnderReview($query)
    {
        return $query->where('approval_status', 'under_review');
    }

    // Get profiles by territory
    public function scopeByTerritory($query, $territory)
    {
        return $query->where('assigned_territory', $territory);
    }

    // Get high performers (accuracy >= 80%)
    public function scopeHighPerformers($query)
    {
        return $query->where('accuracy_rate', '>=', 80.00);
    }

    // Get recently submitted (last 30 days)
    public function scopeRecentlySubmitted($query)
    {
        return $query->where('submitted_at', '>=', now()->subDays(30));
    }

    /**
     * Approval Methods
     */

    public function approve($adminId)
    {
        $this->approval_status = 'approved';
        $this->reviewed_at = now();
        $this->reviewed_by = $adminId;
        $this->save();

        // Also update the user's approval status
        $this->user->is_approved = true;
        $this->user->approved_at = now();
        $this->user->approved_by = $adminId;
        $this->user->save();

        return $this;
    }

    public function reject($adminId, $reason = null)
    {
        $this->approval_status = 'rejected';
        $this->rejection_reason = $reason;
        $this->reviewed_at = now();
        $this->reviewed_by = $adminId;
        $this->save();

        // Also update the user's approval status
        $this->user->is_approved = false;
        $this->user->save();

        return $this;
    }

    public function markUnderReview($adminId)
    {
        $this->approval_status = 'under_review';
        $this->reviewed_at = now();
        $this->reviewed_by = $adminId;
        $this->save();

        return $this;
    }

    /**
     * Increment submission count
     */
    public function incrementSubmissions($isApproved = false)
    {
        $this->total_submissions++;

        if ($isApproved) {
            $this->approved_submissions++;
        }

        // Recalculate accuracy rate
        if ($this->total_submissions > 0) {
            $this->accuracy_rate = ($this->approved_submissions / $this->total_submissions) * 100;
        }

        $this->last_submission_at = now();
        $this->save();

        return $this;
    }

    /**
     * Check if documents are complete
     */
    public function hasCompleteDocuments()
    {
        return !empty($this->id_card_document) 
            && !empty($this->verification_document)
            && !empty($this->certificates);
    }

    /**
     * Get documents summary
     */
    public function getDocumentsSummaryAttribute()
    {
        $summary = [];

        if ($this->id_card_document) {
            $summary[] = 'ID Card';
        }

        if ($this->verification_document) {
            $summary[] = 'Verification Document';
        }

        if ($this->certificates && is_array($this->certificates)) {
            $summary[] = count($this->certificates) . ' Certificate(s)';
        }

        return implode(', ', $summary);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a profile, set default values
        static::creating(function ($profile) {
            if (!$profile->approval_status) {
                $profile->approval_status = 'pending';
            }

            if (!$profile->submitted_at) {
                $profile->submitted_at = now();
            }

            $profile->total_submissions = 0;
            $profile->approved_submissions = 0;
            $profile->accuracy_rate = 0.00;
        });
    }
}