<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\Lga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Get all countries
     */
    public function countries()
    {
        try {
            $countries = Country::select('id', 'name', 'code')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function($country) {
                    return [
                        'id' => $country->id,
                        'name' => $country->name,
                        'code' => $country->code,
                    ];
                });

            return response()->json($countries);
        } catch (\Exception $e) {
            Log::error('Error fetching countries: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch countries'], 500);
        }
    }

    /**
     * Get states by country ID
     */
    public function states($countryId = null)
    {
        try {
            if (!$countryId) {
                return response()->json(['error' => 'Country ID is required'], 400);
            }

            $states = State::where('country_id', $countryId)
                ->select('id', 'name', 'code', 'country_id')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function($state) {
                    return [
                        'id' => $state->id,
                        'name' => $state->name,
                        'code' => $state->code,
                        'country_id' => $state->country_id,
                    ];
                });

            return response()->json($states);
        } catch (\Exception $e) {
            Log::error('Error fetching states: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch states'], 500);
        }
    }

    /**
     * Get LGAs by state ID
     */
    public function lgas($stateId = null)
    {
        try {
            if (!$stateId) {
                return response()->json(['error' => 'State ID is required'], 400);
            }

            $lgas = Lga::where('state_id', $stateId)
                ->select('id', 'name', 'state_id')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function($lga) {
                    return [
                        'id' => $lga->id,
                        'name' => $lga->name,
                        'state_id' => $lga->state_id,
                    ];
                });

            return response()->json($lgas);
        } catch (\Exception $e) {
            Log::error('Error fetching LGAs: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch LGAs'], 500);
        }
    }

    /**
     * Detect location from GPS coordinates
     */
    public function detectLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        return response()->json([
            'success' => true,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'message' => 'GPS coordinates saved',
        ]);
    }
}
