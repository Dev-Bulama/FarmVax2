<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkMessage extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'target_roles',
        'target_locations',
        'specific_users',
        'recipient_data',
        'total_recipients',
        'sent_count',
        'failed_count',
        'success_count',
        'status',
        'scheduled_at',
        'sent_at',
        'created_by'
    ];

    protected $casts = [
        'target_roles' => 'array',
        'target_locations' => 'array',
        'specific_users' => 'array',
        'recipient_data' => 'array',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
        'success_count' => 'integer',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BulkMessageLog::class);
    }
    
    /**
     * Get formatted recipient count
     */
    public function getFormattedRecipientsAttribute()
    {
        return number_format($this->total_recipients);
    }
    
    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_recipients == 0) return 0;
        return round(($this->success_count / $this->total_recipients) * 100, 2);
    }
}