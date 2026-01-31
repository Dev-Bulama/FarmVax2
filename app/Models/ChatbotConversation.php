<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotConversation extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'human_requested',
        'human_requested_at',
        'human_takeover',
        'human_takeover_at',
        'handled_by_admin_id',
        'notification_sent',
    ];

    protected $casts = [
        'human_requested' => 'boolean',
        'human_takeover' => 'boolean',
        'notification_sent' => 'boolean',
        'human_requested_at' => 'datetime',
        'human_takeover_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'handled_by_admin_id');
    }

    public function scopeHumanRequested($query)
    {
        return $query->where('human_requested', true)->where('human_takeover', false);
    }

    public function scopeActiveTakeovers($query)
    {
        return $query->where('human_takeover', true)->where('status', 'active');
    }
}