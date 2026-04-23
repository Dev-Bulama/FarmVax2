<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
     * Get country by ISO code — auto-detects the actual column names
     * so it works regardless of which migration created the table.
     */
    public static function findByCode($code)
    {
        try {
            $fields = array_column(DB::select("SHOW COLUMNS FROM `countries`"), 'Field');

            // Common 2-letter and 3-letter code column names used by different packages
            $candidates = array_values(array_intersect(
                ['iso2', 'code', 'cca2', 'alpha2', 'iso3', 'code3', 'cca3', 'alpha3'],
                $fields
            ));

            if (empty($candidates)) {
                return null;
            }

            return static::where(function ($q) use ($code, $candidates) {
                foreach ($candidates as $col) {
                    $q->orWhere($col, $code);
                }
            })->first();
        } catch (\Exception $e) {
            // Last resort: match by name
            $nameMap = ['NG' => 'Nigeria', 'NGA' => 'Nigeria', 'US' => 'United States',
                        'GH' => 'Ghana', 'ZA' => 'South Africa', 'KE' => 'Kenya'];
            $name = $nameMap[$code] ?? null;
            return $name ? static::where('name', $name)->first() : null;
        }
    }
}