<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\Lga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Reverse geocode coordinates to address and match with database
     */
    public function reverseGeocode(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if (!$latitude || !$longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Coordinates required',
                'matches' => null
            ]);
        }

        // Use OpenStreetMap Nominatim for reverse geocoding
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10&addressdetails=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'FarmVax/1.0 (https://farmvax.com)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::warning('Geocoding curl error: ' . $curlError);
                throw new \Exception('Network error');
            }

            if ($httpCode !== 200 || !$response) {
                Log::warning('Geocoding HTTP error: ' . $httpCode);
                throw new \Exception('Geocoding service returned HTTP ' . $httpCode);
            }

            $data = json_decode($response, true);

            if (!$data || !isset($data['address'])) {
                Log::warning('Invalid geocoding response', ['data' => $data]);
                throw new \Exception('Invalid response from geocoding service');
            }

            $address = $data['address'];

            // Extract location components with multiple fallbacks
            $countryName = $address['country'] ?? null;
            $stateName = $address['state'] ?? $address['region'] ?? $address['province'] ?? null;
            $lgaName = $address['county'] ??
                       $address['state_district'] ??
                       $address['local_government_area'] ??
                       $address['municipality'] ??
                       $address['city'] ??
                       $address['town'] ?? null;

            Log::info('Geocoding extracted names', [
                'country' => $countryName,
                'state' => $stateName,
                'lga' => $lgaName
            ]);

            // Match to database with improved fuzzy matching
            $matches = [
                'country_id' => null,
                'state_id' => null,
                'lga_id' => null,
            ];

            if ($countryName) {
                $country = $this->fuzzyMatchCountry($countryName);

                if ($country) {
                    $matches['country_id'] = $country->id;

                    // Try to match state
                    if ($stateName) {
                        $state = $this->fuzzyMatchState($stateName, $country->id);

                        if ($state) {
                            $matches['state_id'] = $state->id;

                            // Try to match LGA
                            if ($lgaName) {
                                $lga = $this->fuzzyMatchLga($lgaName, $state->id);

                                if ($lga) {
                                    $matches['lga_id'] = $lga->id;
                                }
                            }
                        }
                    }
                }
            }

            Log::info('Geocoding matches', $matches);

            return response()->json([
                'success' => true,
                'matches' => $matches,
                'address' => [
                    'formatted' => $data['display_name'] ?? '',
                    'country' => $countryName,
                    'state' => $stateName,
                    'lga' => $lgaName,
                    'city' => $address['city'] ?? $address['town'] ?? $address['village'] ?? '',
                    'postcode' => $address['postcode'] ?? '',
                ],
                'coordinates' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ],
                'debug' => [
                    'raw_address' => $address,
                    'extracted' => [
                        'country' => $countryName,
                        'state' => $stateName,
                        'lga' => $lgaName,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Reverse geocoding error', [
                'error' => $e->getMessage(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not determine address: ' . $e->getMessage(),
                'matches' => [
                    'country_id' => null,
                    'state_id' => null,
                    'lga_id' => null,
                ]
            ]);
        }
    }

    /**
     * Fuzzy match country name
     */
    protected function fuzzyMatchCountry($name)
    {
        // Clean and normalize
        $name = $this->normalizeName($name);

        // Try exact match first
        $country = Country::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
        if ($country) return $country;

        // Try LIKE match
        $country = Country::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])->first();
        if ($country) return $country;

        // Try reverse LIKE
        $country = Country::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(name), "%")', [$name])->first();
        if ($country) return $country;

        return null;
    }

    /**
     * Fuzzy match state name
     */
    protected function fuzzyMatchState($name, $countryId)
    {
        // Clean and normalize
        $name = $this->normalizeName($name);

        $query = State::where('country_id', $countryId);

        // Try exact match first
        $state = (clone $query)->whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
        if ($state) return $state;

        // Try LIKE match
        $state = (clone $query)->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])->first();
        if ($state) return $state;

        // Try reverse LIKE
        $state = (clone $query)->whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(name), "%")', [$name])->first();
        if ($state) return $state;

        // Remove "State" suffix and try again (e.g., "Lagos State" -> "Lagos")
        $nameWithoutState = preg_replace('/\s+state$/i', '', $name);
        if ($nameWithoutState !== $name) {
            $state = (clone $query)->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($nameWithoutState) . '%'])->first();
            if ($state) return $state;
        }

        return null;
    }

    /**
     * Fuzzy match LGA name
     */
    protected function fuzzyMatchLga($name, $stateId)
    {
        // Clean and normalize
        $name = $this->normalizeName($name);

        $query = Lga::where('state_id', $stateId);

        // Try exact match first
        $lga = (clone $query)->whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
        if ($lga) return $lga;

        // Try LIKE match
        $lga = (clone $query)->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])->first();
        if ($lga) return $lga;

        // Try reverse LIKE
        $lga = (clone $query)->whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(name), "%")', [$name])->first();
        if ($lga) return $lga;

        return null;
    }

    /**
     * Normalize location name for better matching
     */
    protected function normalizeName($name)
    {
        // Remove common prefixes/suffixes
        $name = preg_replace('/\s+(state|province|region|county|lga|local government area)$/i', '', $name);

        // Trim and clean
        $name = trim($name);
        $name = preg_replace('/\s+/', ' ', $name);  // Remove extra spaces

        return $name;
    }
}
