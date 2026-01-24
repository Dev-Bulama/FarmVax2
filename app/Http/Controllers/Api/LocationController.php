<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\Lga;

class LocationController extends Controller
{
    /**
     * Get all countries
     */
    public function getCountries()
    {
        $countries = Country::orderBy('name')->get(['id', 'name', 'code']);
        return response()->json($countries);
    }

    /**
     * Get states by country
     */
    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)
                      ->orderBy('name')
                      ->get(['id', 'name', 'country_id']);
        
        return response()->json($states);
    }

    /**
     * Get LGAs by state
     */
    public function getLgasByState($stateId)
    {
        $lgas = Lga::where('state_id', $stateId)
                   ->orderBy('name')
                   ->get(['id', 'name', 'state_id']);
        
        return response()->json($lgas);
    }

    /**
     * Reverse geocode coordinates to location
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Use a geocoding service (example with OpenStreetMap Nominatim)
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&addressdetails=1";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'FarmVax/1.0');
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($response, true);
            
            if (isset($data['address'])) {
                $address = $data['address'];
                
                // Extract location details
                $country = $address['country'] ?? null;
                $state = $address['state'] ?? $address['region'] ?? null;
                $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? null;
                $lga = $address['county'] ?? $address['state_district'] ?? null;
                
                // Try to match with database
                $countryMatch = null;
                $stateMatch = null;
                $lgaMatch = null;
                
                if ($country) {
                    $countryMatch = Country::where('name', 'LIKE', "%{$country}%")->first();
                }
                
                if ($countryMatch && $state) {
                    $stateMatch = State::where('country_id', $countryMatch->id)
                                      ->where('name', 'LIKE', "%{$state}%")
                                      ->first();
                }
                
                if ($stateMatch && $lga) {
                    $lgaMatch = Lga::where('state_id', $stateMatch->id)
                                  ->where('name', 'LIKE', "%{$lga}%")
                                  ->first();
                }
                
                return response()->json([
                    'success' => true,
                    'address' => [
                        'country' => $country,
                        'state' => $state,
                        'city' => $city,
                        'lga' => $lga,
                        'formatted' => $data['display_name'] ?? null
                    ],
                    'matches' => [
                        'country_id' => $countryMatch->id ?? null,
                        'country_name' => $countryMatch->name ?? null,
                        'state_id' => $stateMatch->id ?? null,
                        'state_name' => $stateMatch->name ?? null,
                        'lga_id' => $lgaMatch->id ?? null,
                        'lga_name' => $lgaMatch->name ?? null,
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Could not determine location from coordinates'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geocoding service error: ' . $e->getMessage()
            ], 500);
        }
    }
}