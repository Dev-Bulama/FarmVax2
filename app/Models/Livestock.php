<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livestock extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'livestock';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'farm_record_id',
        'user_id',
        'owner_id',  // Added for farmer livestock management
        'recorded_by',
        'tag_number',
        'name',
        'livestock_type',
        'other_type',
        'breed',
        'breed_purity',
        'breed_origin',
        'gender',
        'color',
        'markings',
        'weight',
        'weight_unit',
        'height',
        'date_of_birth',
        'age_years',
        'age_months',
        'age_category',
        'acquisition_date',
        'acquisition_method',
        'purchase_price',
        'previous_owner',
        'mother_id',
        'father_id',
        'is_breeding_animal',
        'offspring_count',
        'production_purpose',
        'daily_milk_production',
        'monthly_egg_production',
        'last_production_date',
        'health_status',
        'last_health_check',
        'current_conditions',
        'medical_history',
        'quarantine_status',
        'quarantine_start_date',
        'quarantine_end_date',
        'is_vaccinated',
        'last_vaccination_date',
        'due_vaccinations',
        'total_vaccinations',
        'feed_types',
        'daily_feed_amount',
        'feeding_schedule',
        'dietary_notes',
        'housing_type',
        'housing_location',
        'pen_number',
        'status',
        'status_change_date',
        'status_notes',
        'death_date',
        'death_cause',
        'death_notes',
        'sale_date',
        'sale_price',
        'buyer_name',
        'buyer_contact',
        'images',
        'primary_image',
        'notes',
        'custom_fields',
        'herd_group_id',  // Added for herd group assignment
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'acquisition_date' => 'date',
            'last_production_date' => 'date',
            'last_health_check' => 'date',
            'quarantine_start_date' => 'date',
            'quarantine_end_date' => 'date',
            'last_vaccination_date' => 'date',
            'status_change_date' => 'date',
            'death_date' => 'date',
            'sale_date' => 'date',
            'medical_history' => 'array',
            'due_vaccinations' => 'array',
            'feed_types' => 'array',
            'images' => 'array',
            'custom_fields' => 'array',
            'is_breeding_animal' => 'boolean',
            'quarantine_status' => 'boolean',
            'is_vaccinated' => 'boolean',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'daily_milk_production' => 'decimal:2',
            'daily_feed_amount' => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    /**
     * Relationships
     */

    // Farm record this animal belongs to (Belongs To)
    public function farmRecord()
    {
        return $this->belongsTo(FarmRecord::class);
    }

    // Owner of the animal (Belongs To)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Data collector who recorded the animal (Belongs To)
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Mother (Self-referencing - Belongs To)
    public function mother()
    {
        return $this->belongsTo(Livestock::class, 'mother_id');
    }

    // Father (Self-referencing - Belongs To)
    public function father()
    {
        return $this->belongsTo(Livestock::class, 'father_id');
    }

    // Offspring (Self-referencing - One-to-Many)
    public function offspring()
    {
        return $this->hasMany(Livestock::class, 'mother_id')
                    ->orWhere('father_id', $this->id);
    }

    // Vaccination history for this animal (One-to-Many)
    public function vaccinationHistory()
    {
        return $this->hasMany(VaccinationHistory::class);
    }

    // Service requests for this animal (One-to-Many)
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Status Checking Methods
     */

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isSold()
    {
        return $this->status === 'sold';
    }

    public function isDeceased()
    {
        return $this->status === 'deceased';
    }

    public function isTransferred()
    {
        return $this->status === 'transferred';
    }

    public function isMissing()
    {
        return $this->status === 'missing';
    }

    /**
     * Health Status Checking Methods
     */

    public function isHealthy()
    {
        return $this->health_status === 'healthy';
    }

    public function isSick()
    {
        return $this->health_status === 'sick';
    }

    public function isRecovering()
    {
        return $this->health_status === 'recovering';
    }

    public function isInQuarantine()
    {
        return $this->quarantine_status === true;
    }

    /**
     * Get age in readable format
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            if ($this->age_years || $this->age_months) {
                $parts = [];
                if ($this->age_years) {
                    $parts[] = $this->age_years . ' year' . ($this->age_years > 1 ? 's' : '');
                }
                if ($this->age_months) {
                    $parts[] = $this->age_months . ' month' . ($this->age_months > 1 ? 's' : '');
                }
                return implode(', ', $parts);
            }
            return 'Unknown';
        }

        $years = now()->diffInYears($this->date_of_birth);
        $months = now()->diffInMonths($this->date_of_birth) % 12;

        if ($years == 0) {
            return $months . ' month' . ($months > 1 ? 's' : '');
        }

        if ($months == 0) {
            return $years . ' year' . ($years > 1 ? 's' : '');
        }

        return "$years year" . ($years > 1 ? 's' : '') . ", $months month" . ($months > 1 ? 's' : '');
    }

    /**
     * Get primary image URL
     */
    public function getPrimaryImageUrlAttribute()
    {
        if ($this->primary_image) {
            return asset('storage/' . $this->primary_image);
        }

        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }

        return asset('images/default-livestock.png');
    }

    /**
     * Get all image URLs
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
     * Get vaccination status badge
     */
    public function getVaccinationStatusBadgeAttribute()
    {
        if (!$this->is_vaccinated || !$this->last_vaccination_date) {
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
     * Get health status badge color
     */
    public function getHealthBadgeColorAttribute()
    {
        return match($this->health_status) {
            'healthy' => 'green',
            'sick' => 'red',
            'recovering' => 'yellow',
            'deceased' => 'gray',
            default => 'blue',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'sold' => 'blue',
            'deceased' => 'gray',
            'transferred' => 'purple',
            'missing' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        if ($this->name) {
            return $this->name . ' (' . $this->tag_number . ')';
        }

        if ($this->tag_number) {
            return ucfirst($this->livestock_type) . ' - ' . $this->tag_number;
        }

        return ucfirst($this->livestock_type) . ' #' . $this->id;
    }

    /**
     * Check if due for vaccination
     */
    public function isDueForVaccination()
    {
        if (!$this->last_vaccination_date) {
            return true; // Never vaccinated
        }

        // Check if overdue (more than 180 days since last vaccination)
        return now()->diffInDays($this->last_vaccination_date) > 180;
    }

    /**
     * Check if due for health check
     */
    public function isDueForHealthCheck()
    {
        if (!$this->last_health_check) {
            return true; // Never checked
        }

        // Check if more than 90 days since last health check
        return now()->diffInDays($this->last_health_check) > 90;
    }

    /**
     * Scope Queries
     */

    // Get by livestock type
    public function scopeByType($query, $type)
    {
        return $query->where('livestock_type', $type);
    }

    // Get active animals
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get healthy animals
    public function scopeHealthy($query)
    {
        return $query->where('health_status', 'healthy');
    }

    // Get sick animals
    public function scopeSick($query)
    {
        return $query->where('health_status', 'sick');
    }

    // Get animals in quarantine
    public function scopeInQuarantine($query)
    {
        return $query->where('quarantine_status', true);
    }

    // Get vaccinated animals
    public function scopeVaccinated($query)
    {
        return $query->where('is_vaccinated', true);
    }

    // Get animals due for vaccination
    public function scopeDueForVaccination($query)
    {
        return $query->where(function($q) {
            $q->whereNull('last_vaccination_date')
              ->orWhere('last_vaccination_date', '<', now()->subDays(180));
        });
    }

    // Get breeding animals
    public function scopeBreeding($query)
    {
        return $query->where('is_breeding_animal', true);
    }

    // Get by gender
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    // Get by age category
    public function scopeByAgeCategory($query, $category)
    {
        return $query->where('age_category', $category);
    }

    // Get sold animals
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    // Get deceased animals
    public function scopeDeceased($query)
    {
        return $query->where('status', 'deceased');
    }

    // Get recently acquired (last 30 days)
    public function scopeRecentlyAcquired($query)
    {
        return $query->where('acquisition_date', '>=', now()->subDays(30));
    }

    /**
     * Lifecycle Methods
     */

    public function markAsSold($price, $buyerName, $buyerContact = null)
    {
        $this->status = 'sold';
        $this->sale_date = now();
        $this->sale_price = $price;
        $this->buyer_name = $buyerName;
        $this->buyer_contact = $buyerContact;
        $this->status_change_date = now();
        $this->save();

        return $this;
    }

    public function markAsDeceased($cause, $notes = null)
    {
        $this->status = 'deceased';
        $this->health_status = 'deceased';
        $this->death_date = now();
        $this->death_cause = $cause;
        $this->death_notes = $notes;
        $this->status_change_date = now();
        $this->save();

        return $this;
    }

    public function markAsTransferred($notes = null)
    {
        $this->status = 'transferred';
        $this->status_notes = $notes;
        $this->status_change_date = now();
        $this->save();

        return $this;
    }

    public function markAsMissing($notes = null)
    {
        $this->status = 'missing';
        $this->status_notes = $notes;
        $this->status_change_date = now();
        $this->save();

        return $this;
    }

    public function putInQuarantine($startDate = null, $endDate = null)
    {
        $this->quarantine_status = true;
        $this->quarantine_start_date = $startDate ?? now();
        $this->quarantine_end_date = $endDate;
        $this->save();

        return $this;
    }

    public function releaseFromQuarantine()
    {
        $this->quarantine_status = false;
        $this->quarantine_end_date = now();
        $this->save();

        return $this;
    }

    /**
     * Calculate age category automatically
     */
    public function updateAgeCategory()
    {
        if (!$this->date_of_birth) {
            return $this;
        }

        $years = now()->diffInYears($this->date_of_birth);

        if ($years < 1) {
            $this->age_category = 'young';
        } elseif ($years <= 5) {
            $this->age_category = 'adult';
        } else {
            $this->age_category = 'old';
        }

        $this->age_years = $years;
        $this->age_months = now()->diffInMonths($this->date_of_birth) % 12;
        $this->save();

        return $this;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When creating an animal, set default values
        static::creating(function ($livestock) {
            if (!$livestock->status) {
                $livestock->status = 'active';
            }

            if (!$livestock->health_status) {
                $livestock->health_status = 'healthy';
            }

            if (!$livestock->weight_unit) {
                $livestock->weight_unit = 'kg';
            }

            if (!$livestock->gender) {
                $livestock->gender = 'unknown';
            }

            if (!$livestock->age_category) {
                $livestock->age_category = 'unknown';
            }
        });

        // After creating/updating, update age category if date_of_birth exists
        static::saved(function ($livestock) {
            if ($livestock->date_of_birth && $livestock->age_category === 'unknown') {
                $livestock->updateAgeCategory();
            }
        });
    }
    /**
 * Relationship with HerdGroup
 */
public function herdGroup()
{
    return $this->belongsTo(HerdGroup::class, 'herd_group_id');
}
}