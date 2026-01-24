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
        'total_recipients',
        'sent_count',
        'failed_count',
        'status',
        'scheduled_at',
        'sent_at',
        'created_by'
    ];

    protected $casts = [
        'target_roles' => 'array',
        'target_locations' => 'array',
        'specific_users' => 'array',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
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
}
