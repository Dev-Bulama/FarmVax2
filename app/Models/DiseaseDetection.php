<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiseaseDetection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'livestock_id', 'image_path', 'animal_type',
        'symptoms_reported', 'status', 'is_sick', 'confidence_score',
        'analysis_result', 'detected_conditions', 'recommendations',
        'urgency_level', 'ai_model', 'user_rating', 'user_comment',
    ];

    protected function casts(): array
    {
        return [
            'detected_conditions' => 'array',
            'is_sick'             => 'boolean',
            'confidence_score'    => 'decimal:2',
            'user_rating'         => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function getUrgencyBadgeAttribute(): string
    {
        return match($this->urgency_level) {
            'critical' => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-red-600 text-white">Critical</span>',
            'high'     => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">High</span>',
            'medium'   => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Medium</span>',
            'low'      => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Low</span>',
            default    => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">Unknown</span>',
        };
    }
}
