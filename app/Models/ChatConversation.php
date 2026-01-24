<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatConversation extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'title',
        'participants',
        'conversation_type',
        'status',
        'last_message_at'
    ];

    protected $casts = [
        'participants' => 'array',
        'last_message_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latestOfMany();
    }

    public function chatParticipants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'conversation_id');
    }

    public function participantUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'conversation_id', 'user_id')
            ->withPivot(['joined_at', 'left_at', 'is_admin', 'notifications_enabled', 'last_read_at'])
            ->withTimestamps();
    }

    /**
     * Check if user is participant
     */
    public function hasParticipant(int $userId): bool
    {
        return in_array($userId, $this->participants ?? []);
    }

    /**
     * Add participant to conversation
     */
    public function addParticipant(int $userId, bool $isAdmin = false): void
    {
        if (!$this->hasParticipant($userId)) {
            $participants = $this->participants ?? [];
            $participants[] = $userId;
            $this->update(['participants' => $participants]);

            ChatParticipant::create([
                'conversation_id' => $this->id,
                'user_id' => $userId,
                'joined_at' => now(),
                'is_admin' => $isAdmin
            ]);
        }
    }

    /**
     * Remove participant from conversation
     */
    public function removeParticipant(int $userId): void
    {
        $participants = array_diff($this->participants ?? [], [$userId]);
        $this->update(['participants' => array_values($participants)]);

        ChatParticipant::where('conversation_id', $this->id)
            ->where('user_id', $userId)
            ->update(['left_at' => now()]);
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCountForUser(int $userId): int
    {
        $participant = $this->chatParticipants()
            ->where('user_id', $userId)
            ->first();

        return $participant ? $participant->unreadCount() : 0;
    }
}
