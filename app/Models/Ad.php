<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'image_path',
        'link_url',
        'type',
        'category',
        'target_type',
        'target_roles',
        'target_locations',
        'country_id',
        'state_id',
        'lga_id',
        'start_date',
        'end_date',
        'views',
        'clicks',
        'priority',
        'is_active',
        'status',
        'created_by',
    ];

    protected $casts = [
        'target_roles'     => 'array',
        'target_locations' => 'array',
        'start_date'       => 'date',
        'end_date'         => 'date',
        'is_active'        => 'boolean',
        'views'            => 'integer',
        'clicks'           => 'integer',
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
        if (!$this->views) {
            return 0;
        }

        return round(($this->clicks / $this->views) * 100, 2);
    }

    public function isCurrentlyActive()
    {
        return $this->is_active
            && $this->start_date <= now()
            && ($this->end_date === null || $this->end_date >= now());
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function incrementClicks()
    {
        $this->increment('clicks');
    }
}