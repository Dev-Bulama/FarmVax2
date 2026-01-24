<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'content',
        'image',
        'metadata',
        'order',
        'is_active'
    ];

    protected $casts = [
        'metadata' => 'array',
        'order' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get content for a specific section
     */
    public static function getSection(string $section, $default = null)
    {
        $content = self::where('section', $section)
            ->where('is_active', true)
            ->first();

        return $content ?? $default;
    }
}
