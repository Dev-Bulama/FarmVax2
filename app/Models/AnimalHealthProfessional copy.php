<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalHealthProfessional extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'animal_health_professionals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'professional_type_id',
        'professional_type',
        'license_number',
        'organization',
        'experience_years',
        'years_of_experience',
        'specialization_id',
        'specialization',
        'service_area_id',
        'assigned_territory',
        'contact_phone',
        'contact_email',
        'bio',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'submitted_at',
        'application_notes',
        'verification_documents',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'verification_documents' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['professional_type_text', 'status_badge_class'];

    /**
     * =========================================
     * RELATIONSHIPS
     * =========================================
     */

    /**
     * Get the user that owns the professional profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved this professional.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the professional type.
     */
    public function professionalType()
    {
        return $this->belongsTo(ProfessionalType::class, 'professional_type_id');
    }

    /**
     * Get the specialization.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    /**
     * Get the service area.
     */
    public function serviceArea()
    {
        return $this->belongsTo(ServiceArea::class, 'service_area_id');
    }

    /**
     * Get the verification documents for this professional.
     */
    public function verificationDocuments()
    {
        return $this->hasMany(VerificationDocument::class, 'user_id', 'user_id');
    }

    /**
     * Get farm records created by this professional.
     */
    public function farmRecords()
    {
        return $this->hasMany(FarmRecord::class, 'user_id', 'user_id');
    }

    /**
     * =========================================
     * ACCESSORS & HELPER METHODS
     * =========================================
     */

    /**
     * Get the professional type display text.
     */
    public function getProfessionalTypeTextAttribute()
    {
        // If relationship exists, use it
        if ($this->professionalType) {
            return $this->professionalType->name;
        }

        // Otherwise, fall back to direct field
        return match($this->professional_type) {
            'veterinarian' => 'Veterinarian',
            'paraveterinarian' => 'Paraveterinarian',
            'community_animal_health_worker' => 'Community Animal Health Worker',
            'others' => 'Other Professional',
            default => $this->professional_type ? ucfirst(str_replace('_', ' ', $this->professional_type)) : 'Not specified',
        };
    }

    /**
     * Get the status badge CSS class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->approval_status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if the professional is approved.
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if the professional is pending.
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if the professional is rejected.
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * =========================================
     * SCOPES
     * =========================================
     */

    /**
     * Scope a query to only include approved professionals.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope a query to only include pending professionals.
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope a query to only include rejected professionals.
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }
}