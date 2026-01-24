<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lga extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'code',
        'latitude',
        'longitude',
    ];

    /**
     * Relationship: LGA belongs to state
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Relationship: LGA has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get country through state relationship
     */
    public function country()
    {
        return $this->state->country ?? null;
    }

    /**
     * Scope: Get LGAs by state
     */
    public function scopeByState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }
}