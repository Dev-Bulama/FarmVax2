<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTrainingData extends Model
{
    protected $table = 'chatbot_training_data';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'usage_count',
        'is_active'
    ];

    protected $casts = [
        'usage_count' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
