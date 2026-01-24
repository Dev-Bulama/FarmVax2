<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['role_display_name'];

    /**
     * =========================================
     * SYSTEM ACCESS METHODS
     * =========================================
     */

    /**
     * Check if user can access the system.
     */
    public function canAccessSystem()
    {
        // Admin always has access
        if ($this->isAdmin()) {
            return true;
        }

        // Farmers always have access
        if ($this->isFarmer()) {
            return true;
        }

        // Volunteers always have access (auto-approved)
        if ($this->isVolunteer()) {
            return $this->volunteer && $this->volunteer->is_active;
        }

        // Animal health professionals need approval
        if ($this->isAnimalHealthProfessional()) {
            return $this->hasApprovedProfessionalProfile();
        }

        return false;
    }

    /**
     * =========================================
     * ROLE CHECK METHODS
     * =========================================
     */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isFarmer()
    {
        return $this->role === 'farmer';
    }

    public function isAnimalHealthProfessional()
    {
        return $this->role === 'animal_health_professional';
    }

    public function isVolunteer()
    {
        return $this->role === 'volunteer';
    }

    // Legacy methods
    public function isDataCollector()
    {
        return $this->isAnimalHealthProfessional();
    }

    public function isIndividual()
    {
        return $this->isFarmer();
    }

    /**
     * =========================================
     * RELATIONSHIPS - PROFILES
     * =========================================
     */

    public function animalHealthProfessional()
    {
        return $this->hasOne(AnimalHealthProfessional::class);
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class);
    }

    // Legacy
    public function dataCollectorProfile()
    {
        return $this->animalHealthProfessional();
    }

    public function enrollmentRecord()
    {
        return $this->hasOne(FarmerEnrollment::class, 'farmer_id');
    }

    /**
     * =========================================
     * RELATIONSHIPS - LIVESTOCK & FARM DATA
     * =========================================
     */

    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'owner_id');
    }

    public function farmRecords()
    {
        return $this->hasMany(FarmRecord::class, 'user_id');
    }

    public function farmerRecords()
    {
        return $this->hasMany(FarmRecord::class, 'farmer_id');
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function vaccinationHistory()
    {
        return $this->hasMany(VaccinationHistory::class);
    }

    /**
     * =========================================
     * RELATIONSHIPS - VOLUNTEER ACTIVITIES
     * =========================================
     */

    public function enrolledFarmers()
    {
        return $this->hasMany(FarmerEnrollment::class, 'enrolled_by');
    }

    /**
     * =========================================
     * HELPER METHODS & ACCESSORS
     * =========================================
     */

    /**
     * Get the user's role display name.
     */
    public function getRoleDisplayNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'farmer' => 'Farmer',
            'animal_health_professional' => 'Animal Health Professional',
            'volunteer' => 'Volunteer',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get the user's dashboard route based on role.
     */
    public function getDashboardRouteAttribute()
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'farmer' => 'individual.dashboard',
            'animal_health_professional' => 'professional.dashboard',
            'volunteer' => 'volunteer.dashboard',
            default => 'dashboard',
        };
    }

    /**
     * Check if user has an approved professional profile.
     */
    public function hasApprovedProfessionalProfile()
    {
        if (!$this->isAnimalHealthProfessional()) {
            return false;
        }

        return $this->animalHealthProfessional()
            ->where('approval_status', 'approved')
            ->exists();
    }

    /**
     * Check if user has an approved volunteer profile.
     */
    public function hasApprovedVolunteerProfile()
    {
        if (!$this->isVolunteer()) {
            return false;
        }

        return $this->volunteer()
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get count of farmers enrolled by this user.
     */
    public function getFarmersEnrolledCountAttribute()
    {
        return $this->enrolledFarmers()->count();
    }
}