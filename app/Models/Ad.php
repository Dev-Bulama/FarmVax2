<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'image_path',
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
        'created_by',
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

    /**
     * Get the correct image URL
     */
    public function getImageAttribute()
    {
        // Priority 1: Check image_url
        if ($this->image_url) {
            // If it starts with http, return as is
            if (str_starts_with($this->image_url, 'http')) {
                return $this->image_url;
            }
            
            // If it's a storage path
            if (str_starts_with($this->image_url, 'ads/')) {
                return asset('storage/' . $this->image_url);
            }
            
            return asset($this->image_url);
        }
        
        // Priority 2: Check image_path
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        return null;
    }

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function views()
    {
        return $this->hasMany(AdView::class);
    }

    /**
     * Helper Methods
     */
    public function getClickThroughRateAttribute()
    {
        if ($this->views_count == 0) {
            return 0;
        }
        
        return round(($this->clicks_count / $this->views_count) * 100, 2);
    }

    public function isCurrentlyActive()
    {
        return $this->is_active 
            && $this->start_date <= now() 
            && $this->end_date >= now();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
}