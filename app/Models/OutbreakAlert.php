<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutbreakAlert extends Model
{
    protected $fillable = [
        'disease_name',
        'severity',
        'affected_species',
        'location',
        'country_id',
        'state_id',
        'lga_id',
        'description',
        'preventive_measures',
        'symptoms',
        'is_active',
        'reported_by',
        'reported_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'reported_at' => 'datetime',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }
}