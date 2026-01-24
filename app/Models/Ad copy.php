<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'type',
        'target_audience',
        'target_location',
        'start_date',
        'end_date',
        'views_count',
        'clicks_count',
        'budget',
        'cost_per_click',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'target_audience' => 'array',
        'target_location' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'clicks_count' => 'integer',
        'budget' => 'decimal:2',
        'cost_per_click' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function views(): HasMany
    {
        return $this->hasMany(AdView::class);
    }

    /**
     * Get click-through rate
     */
    public function getClickThroughRateAttribute()
    {
        if ($this->views_count == 0) return 0;
        return round(($this->clicks_count / $this->views_count) * 100, 2);
    }

    /**
     * Check if ad is currently active
     */
    public function isCurrentlyActive()
    {
        if (!$this->is_active) return false;
        
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment click count
     */
    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
}