<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'iso2',
        'iso3',
        'phone_code',
        'region',
        'subregion',
        'latitude',
        'longitude',
    ];

    /**
     * Relationship: Country has many states
     */
    public function states()
    {
        return $this->hasMany(State::class);
    }

    /**
     * Relationship: Country has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope: Get active countries
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get country by code
     */
    public static function findByCode($code)
    {
        return static::where('code', $code)
            ->orWhere('iso2', $code)
            ->orWhere('iso3', $code)
            ->first();
    }
}