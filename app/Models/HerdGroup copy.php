<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HerdGroup extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'animal_type',
        'total_animals',
        'average_health_score'
    ];

    protected $casts = [
        'total_animals' => 'integer',
        'average_health_score' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function livestock(): HasMany
    {
        return $this->hasMany(Livestock::class);
    }

    /**
     * Recalculate group statistics
     */
    public function recalculateStats(): void
    {
        $this->update([
            'total_animals' => $this->livestock()->count(),
            'average_health_score' => $this->livestock()->avg('health_score') ?? 0
        ]);
    }
}
