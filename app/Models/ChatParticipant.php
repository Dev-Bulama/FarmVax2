<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'joined_at',
        'left_at',
        'is_admin',
        'notifications_enabled',
        'last_read_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_admin' => 'boolean',
        'notifications_enabled' => 'boolean',
        'last_read_at' => 'datetime'
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if participant has unread messages
     */
    public function hasUnreadMessages(): bool
    {
        if (!$this->last_read_at) {
            return true;
        }

        return $this->conversation->messages()
            ->where('created_at', '>', $this->last_read_at)
            ->where('sender_id', '!=', $this->user_id)
            ->exists();
    }

    /**
     * Get unread message count
     */
    public function unreadCount(): int
    {
        if (!$this->last_read_at) {
            return $this->conversation->messages()
                ->where('sender_id', '!=', $this->user_id)
                ->count();
        }

        return $this->conversation->messages()
            ->where('created_at', '>', $this->last_read_at)
            ->where('sender_id', '!=', $this->user_id)
            ->count();
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }
}
