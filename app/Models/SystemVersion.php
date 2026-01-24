<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemVersion extends Model
{
    protected $fillable = [
        'version',
        'release_name',
        'description',
        'changelog',
        'update_file_path',
        'update_file_size',
        'status',
        'applied_at',
        'applied_by',
        'error_log',
        'requires_migration',
        'requires_cache_clear',
        'requires_restart',
        'backup_info',
        'is_current',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'requires_migration' => 'boolean',
        'requires_cache_clear' => 'boolean',
        'requires_restart' => 'boolean',
        'backup_info' => 'array',
        'is_current' => 'boolean',
    ];

    /**
     * Get the user who applied this version
     */
    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    /**
     * Get the current active version
     */
    public static function getCurrentVersion()
    {
        return static::where('is_current', true)->first();
    }

    /**
     * Mark this version as current and unmark all others
     */
    public function markAsCurrent()
    {
        static::where('is_current', true)->update(['is_current' => false]);
        $this->update(['is_current' => true]);
        return $this;
    }

    /**
     * Get version badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'applied' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'rolled_back' => 'bg-gray-100 text-gray-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }
}
