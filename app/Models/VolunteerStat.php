<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerStat extends Model
{
    protected $fillable = [
        'volunteer_id',
        'total_enrollments',
        'active_farmers',
        'total_points',
        'current_badge',
        'rank'
    ];

    protected $casts = [
        'total_enrollments' => 'integer',
        'active_farmers' => 'integer',
        'total_points' => 'integer',
        'rank' => 'integer'
    ];

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Update badge based on points
     */
    public function updateBadge(): void
    {
        $badge = match(true) {
            $this->total_points >= 1000 => 'platinum',
            $this->total_points >= 500 => 'gold',
            $this->total_points >= 200 => 'silver',
            default => 'bronze'
        };

        $this->update(['current_badge' => $badge]);
    }
}
