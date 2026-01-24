<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdView extends Model
{
    protected $fillable = [
        'ad_id',
        'user_id',
        'ip_address',
        'user_agent',
        'clicked',
        'clicked_at',
        'viewed_at'
    ];

    protected $casts = [
        'clicked' => 'boolean',
        'clicked_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark as clicked
     */
    public function markAsClicked()
    {
        $this->update([
            'clicked' => true,
            'clicked_at' => now()
        ]);

        // Increment ad clicks count
        $this->ad->incrementClicks();
    }
}