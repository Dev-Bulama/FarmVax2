<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'message_type',
        'attachment',
        'file_url',
        'file_type',
        'file_size',
        'duration',
        'metadata',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'file_size' => 'integer',
        'duration' => 'integer'
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(ChatMessageReaction::class, 'message_id');
    }

    /**
     * Check if message is multimedia
     */
    public function isMultimedia(): bool
    {
        return in_array($this->message_type, ['image', 'video', 'voice', 'file']);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get duration in human readable format
     */
    public function getDurationHumanAttribute(): string
    {
        if (!$this->duration) {
            return '';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Add reaction to message
     */
    public function addReaction(int $userId, string $emoji): void
    {
        ChatMessageReaction::updateOrCreate(
            [
                'message_id' => $this->id,
                'user_id' => $userId
            ],
            ['emoji' => $emoji]
        );
    }

    /**
     * Remove reaction from message
     */
    public function removeReaction(int $userId): void
    {
        ChatMessageReaction::where('message_id', $this->id)
            ->where('user_id', $userId)
            ->delete();
    }
}
