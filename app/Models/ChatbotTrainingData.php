<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTrainingData extends Model
{
    protected $table = 'chatbot_training_data';
    
    protected $fillable = [
        'title',
        'type',
        'content',
        'source_url',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}