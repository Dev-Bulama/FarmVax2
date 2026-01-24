<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HerdGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'description',
        'purpose',
        'total_count',
        'healthy_count',
        'sick_count',
        'location',
        'color_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_count' => 'integer',
        'healthy_count' => 'integer',
        'sick_count' => 'integer',
    ];

    /**
     * Relationship with User (Owner)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Livestock
     */
    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'herd_group_id');
    }

    /**
     * Get healthy livestock in this herd
     */
    public function healthyLivestock()
    {
        return $this->livestock()->where('health_status', 'healthy');
    }

    /**
     * Get sick livestock in this herd
     */
    public function sickLivestock()
    {
        return $this->livestock()->whereIn('health_status', ['sick', 'under_treatment']);
    }

    /**
     * Update herd statistics
     */
    public function updateStatistics()
    {
        $this->total_count = $this->livestock()->count();
        $this->healthy_count = $this->healthyLivestock()->count();
        $this->sick_count = $this->sickLivestock()->count();
        $this->save();
    }

    /**
     * Get health percentage
     */
    public function getHealthPercentageAttribute()
    {
        if ($this->total_count == 0) {
            return 0;
        }
        return round(($this->healthy_count / $this->total_count) * 100, 2);
    }

    /**
     * Get vaccination coverage
     */
    public function getVaccinationCoverageAttribute()
    {
        if ($this->total_count == 0) {
            return 0;
        }
        
        $vaccinatedCount = $this->livestock()->where('is_vaccinated', true)->count();
        return round(($vaccinatedCount / $this->total_count) * 100, 2);
    }

    /**
     * Check if herd needs attention
     */
    public function getNeedsAttentionAttribute()
    {
        return $this->sick_count > 0 || $this->health_percentage < 80;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        if ($this->sick_count > 0) {
            return 'red';
        }
        
        if ($this->health_percentage >= 90) {
            return 'green';
        }
        
        if ($this->health_percentage >= 70) {
            return 'yellow';
        }
        
        return 'orange';
    }

    /**
     * Scope: Only active herds
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Herds by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Herds needing attention
     */
    public function scopeNeedingAttention($query)
    {
        return $query->where('sick_count', '>', 0)
                     ->orWhereRaw('(healthy_count / NULLIF(total_count, 0)) < 0.8');
    }

    /**
     * Get recent vaccinations for this herd
     */
    public function recentVaccinations($days = 30)
    {
        return VaccinationHistory::whereIn('livestock_id', $this->livestock()->pluck('id'))
            ->where('vaccination_date', '>=', now()->subDays($days))
            ->orderBy('vaccination_date', 'desc')
            ->get();
    }

    /**
     * Get average age of livestock in herd (in months)
     */
    public function getAverageAgeAttribute()
    {
        $livestock = $this->livestock;
        
        if ($livestock->isEmpty()) {
            return 0;
        }
        
        $totalMonths = 0;
        $count = 0;
        
        foreach ($livestock as $animal) {
            if ($animal->date_of_birth) {
                $ageInMonths = now()->diffInMonths($animal->date_of_birth);
                $totalMonths += $ageInMonths;
                $count++;
            }
        }
        
        return $count > 0 ? round($totalMonths / $count, 1) : 0;
    }

    /**
     * Get formatted statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->total_count,
            'healthy' => $this->healthy_count,
            'sick' => $this->sick_count,
            'health_percentage' => $this->health_percentage,
            'vaccination_coverage' => $this->vaccination_coverage,
            'average_age' => $this->average_age,
            'needs_attention' => $this->needs_attention,
        ];
    }
}