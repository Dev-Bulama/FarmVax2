<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'code',
        'state_code',
        'country_code',
        'latitude',
        'longitude',
    ];

    /**
     * Relationship: State belongs to country
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relationship: State has many LGAs
     */
    public function lgas()
    {
        return $this->hasMany(Lga::class);
    }

    /**
     * Relationship: State has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope: Get states by country
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
}