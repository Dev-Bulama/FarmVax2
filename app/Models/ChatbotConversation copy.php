<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotConversation extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'visitor_ip',
        'status',
        'handed_to',
        'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handed_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id');
    }
}
