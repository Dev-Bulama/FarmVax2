<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutbreakAlert extends Model
{
    protected $fillable = [
        'disease_name',
        'description',
        'severity',
        'location_state',
        'location_lga',
        'location_village',
        'latitude',
        'longitude',
        'radius_km',
        'outbreak_date',
        'status',
        'precautions',
        'symptoms',
        'affected_animals',
        'confirmed_cases',
        'deaths',
        'reported_by'
    ];

    protected $casts = [
        'affected_animals' => 'array',
        'outbreak_date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'confirmed_cases' => 'integer',
        'deaths' => 'integer',
        'radius_km' => 'integer'
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(OutbreakAlertNotification::class);
    }

    /**
     * Check if user is within alert radius
     */
    public function isUserInRadius(User $user): bool
    {
        if (!$user->latitude || !$user->longitude || !$this->latitude || !$this->longitude) {
            // Fallback to location matching
            return $user->state_id && (
                $user->alert_location_state === $this->location_state ||
                $user->state->name === $this->location_state
            );
        }

        // Calculate distance using Haversine formula
        $earthRadius = 6371; // km

        $latFrom = deg2rad($user->latitude);
        $lonFrom = deg2rad($user->longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $distance = $angle * $earthRadius;

        return $distance <= $this->radius_km;
    }
}
