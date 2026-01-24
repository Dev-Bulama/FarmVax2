<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerEnrollment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'farmer_enrollments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'farmer_id',
        'enrolled_by',
        'enrollment_method',
        'location',
        'notes',
    ];

    /**
     * Get the farmer user.
     */
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Get the user who enrolled the farmer (volunteer, professional, or admin).
     */
    public function enrolledBy()
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    /**
     * Scope to get enrollments by volunteers.
     */
    public function scopeByVolunteers($query)
    {
        return $query->where('enrollment_method', 'volunteer');
    }

    /**
     * Scope to get self-enrollments.
     */
    public function scopeSelfEnrolled($query)
    {
        return $query->where('enrollment_method', 'self');
    }

    /**
     * Get the enrollment method display text.
     */
    public function getEnrollmentMethodTextAttribute()
    {
        return match($this->enrollment_method) {
            'self' => 'Self Registered',
            'volunteer' => 'Enrolled by Volunteer',
            'professional' => 'Enrolled by Health Professional',
            'admin' => 'Enrolled by Admin',
            default => ucfirst($this->enrollment_method),
        };
    }
}