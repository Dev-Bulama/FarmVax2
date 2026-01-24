<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutbreakAlertNotification extends Model
{
    protected $fillable = [
        'outbreak_alert_id',
        'user_id',
        'channel',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function outbreakAlert(): BelongsTo
    {
        return $this->belongsTo(OutbreakAlert::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
