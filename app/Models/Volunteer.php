<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'volunteers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'organization',
        'assigned_area',
        'motivation',
        'contact_phone',
        'contact_email',
        'farmers_enrolled',
        'is_active',
        'approval_status',
        'approved_by',
        'approved_at',
        'submitted_at',
        'notes',
        'referral_code',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'farmers_enrolled' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the volunteer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved the volunteer.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get farmers enrolled by this volunteer.
     */
    // public function enrolledFarmers()
    // {
    //     return $this->hasMany(FarmerEnrollment::class, 'enrolled_by', 'user_id');
    // }

    /**
     * Scope to get active volunteers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('approval_status', 'approved');
    }

    /**
     * Scope to get pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope to get approved volunteers.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Check if the volunteer is active.
     */
    public function isActive()
    {
        return $this->is_active && $this->approval_status === 'approved';
    }

    /**
     * Increment farmers enrolled count.
     */
    public function incrementFarmersEnrolled()
    {
        $this->increment('farmers_enrolled');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        if (!$this->is_active) {
            return 'gray';
        }

        return match($this->approval_status) {
            'approved' => 'green',
            'rejected' => 'red',
            'pending' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get the status display text.
     */
    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }

        return ucfirst($this->approval_status);
    }
    /**
 * Volunteer relationship
 */
public function volunteer()
{
    return $this->hasOne(Volunteer::class);
}

/**
 * Farmers enrolled by this volunteer
 */
public function enrolledFarmers()
{
    return $this->hasMany(FarmerEnrollment::class, 'enrolled_by');
}

/**
 * Volunteer stats
 */
public function volunteerStats()
{
    return $this->hasOne(VolunteerStat::class, 'volunteer_id');
}
protected static function boot()
{
    parent::boot();
    
    // Auto-generate referral code when volunteer is created
    static::creating(function ($volunteer) {
        if (empty($volunteer->referral_code)) {
            $volunteer->referral_code = 'FV-' . strtoupper(substr(md5($volunteer->user_id . time()), 0, 8));
        }
    });
}

// Add relationship
public function referredFarmers()
{
    return $this->hasMany(User::class, 'referred_by', 'id')->where('role', 'farmer');
}
}