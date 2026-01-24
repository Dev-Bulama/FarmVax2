<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class LocationDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lgas')->truncate();
        DB::table('states')->truncate();
        DB::table('countries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "✅ Cleared existing location data\n";

        // Get actual columns from countries table
        $countryColumns = $this->getTableColumns('countries');
        
        // Insert Nigeria
        $nigeriaData = [
            'name' => 'Nigeria',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Add optional columns if they exist
        if (in_array('code', $countryColumns)) $nigeriaData['code'] = 'NG';
        if (in_array('phone_code', $countryColumns)) $nigeriaData['phone_code'] = '+234';
        if (in_array('region', $countryColumns)) $nigeriaData['region'] = 'Africa';
        if (in_array('subregion', $countryColumns)) $nigeriaData['subregion'] = 'Western Africa';
        
        $nigeriaId = DB::table('countries')->insertGetId($nigeriaData);

        // Insert Liberia
        $liberiaData = [
            'name' => 'Liberia',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        if (in_array('code', $countryColumns)) $liberiaData['code'] = 'LR';
        if (in_array('phone_code', $countryColumns)) $liberiaData['phone_code'] = '+231';
        if (in_array('region', $countryColumns)) $liberiaData['region'] = 'Africa';
        if (in_array('subregion', $countryColumns)) $liberiaData['subregion'] = 'Western Africa';
        
        $liberiaId = DB::table('countries')->insertGetId($liberiaData);

        echo "✅ Inserted 2 countries\n";

        // Nigerian States and LGAs from JSON
        $this->seedNigerianData($nigeriaId);

        // Liberian Counties and Districts
        $this->seedLiberianData($liberiaId);

        echo "\n✅ Location seeding completed successfully!\n";
        echo "📊 Final counts:\n";
        echo "   - Countries: " . DB::table('countries')->count() . "\n";
        echo "   - States: " . DB::table('states')->count() . "\n";
        echo "   - LGAs: " . DB::table('lgas')->count() . "\n";
    }

    /**
     * Get columns from a table
     */
    private function getTableColumns($tableName)
    {
        $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
        return array_column($columns, 'Field');
    }

    /**
     * Seed Nigerian states and LGAs from JSON
     */
    private function seedNigerianData($countryId)
    {
        $jsonPath = storage_path('app/nigerian-states.json');
        
        if (!File::exists($jsonPath)) {
            echo "⚠️  Nigerian states JSON not found at: $jsonPath\n";
            echo "   Trying to copy from uploads...\n";
            
            $uploadPath = public_path('uploads/nigerian-states.json');
            if (File::exists($uploadPath)) {
                File::copy($uploadPath, $jsonPath);
                echo "✅ Copied JSON from uploads\n";
            } else {
                echo "❌ JSON file not found. Please upload nigerian-states.json\n";
                return;
            }
        }

        $nigerianData = json_decode(File::get($jsonPath), true);
        
        $stateColumns = $this->getTableColumns('states');
        $lgaColumns = $this->getTableColumns('lgas');

        $stateCount = 0;
        $lgaCount = 0;

        foreach ($nigerianData as $stateName => $lgas) {
            // Prepare state data
            $stateData = [
                'country_id' => $countryId,
                'name' => $stateName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Add code if column exists
            if (in_array('code', $stateColumns)) {
                $stateData['code'] = $this->generateStateCode($stateName);
            }
            
            // Insert state
            $stateId = DB::table('states')->insertGetId($stateData);
            $stateCount++;

            // Insert LGAs for this state
            foreach ($lgas as $lgaName) {
                $lgaData = [
                    'state_id' => $stateId,
                    'name' => trim($lgaName),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                DB::table('lgas')->insert($lgaData);
                $lgaCount++;
            }
        }

        echo "✅ Inserted {$stateCount} Nigerian states\n";
        echo "✅ Inserted {$lgaCount} Nigerian LGAs\n";
    }

    /**
     * Seed Liberian counties and districts
     */
    private function seedLiberianData($countryId)
    {
        $liberianData = [
            'Bomi' => ['Dewoin District', 'Klay District', 'Mecca District', 'Senjeh District'],
            'Bong' => ['Fuamah District', 'Jorquelleh District', 'Kokoyah District', 'Panta-Kpa District', 'Salala District', 'Sanoyea District', 'Suakoko District', 'Tukpahblee District', 'Yeallequelleh District', 'Zota District'],
            'Gbarpolu' => ['Belle Yalla District', 'Bokomu District', 'Gbarma District', 'Kongba District'],
            'Grand Bassa' => ['District 1', 'District 2', 'District 3', 'District 4', 'Owensgrove District', 'St. John River District'],
            'Grand Cape Mount' => ['Commonwealth District', 'Garwula District', 'Gola Konneh District', 'Porkpa District', 'Tewor District'],
            'Grand Gedeh' => ['B\'hai District', 'Cavalla District', 'Gbao District', 'Gbeapo District', 'Konobo District', 'Putu District', 'Tchien District', 'Zwedru District'],
            'Grand Kru' => ['Barclayville District', 'Buah District', 'Dorbor District', 'Forpoh District', 'Garraway District', 'Gbeazon District', 'Picnicess District', 'Sasstown District', 'Trenbo District', 'Upper Sasstown District'],
            'Lofa' => ['Foya District', 'Kolahun District', 'Salayea District', 'Vahun District', 'Voinjama District', 'Zorzor District'],
            'Margibi' => ['Firestone District', 'Gibi District', 'Kakata District', 'Mambah-Kaba District', 'Todee District'],
            'Maryland' => ['Barrobo District', 'Karloken District', 'Pleebo/Sodeken District', 'Whojah District'],
            'Montserrado' => ['Careysburg District', 'Commonwealth District', 'Greater Monrovia District', 'St. Paul River District', 'Todee District'],
            'Nimba' => ['Boe and Quilla District', 'Doe District', 'Gbehlay-Geh District', 'Saclepea District', 'Sanniquellie-Mahn District', 'Tappita District', 'Yarpea-Mahn District', 'Yarwin District', 'Zoegeh District'],
            'River Cess' => ['Cestos District', 'Doedain District', 'Jo River District', 'Sam District', 'Timbo District'],
            'River Gee' => ['Buah District', 'Chedepo District', 'Fish Town District', 'Gbeapo District', 'Glaro District', 'Karforh District', 'Nanee District', 'Nyenawliken District', 'Parluken District', 'Potupo District', 'Sarbo District', 'Tuobo District', 'Webbo District'],
            'Sinoe' => ['Bokon District', 'Bodae District', 'Dugbe River District', 'Greenville District', 'Jaedae District', 'Jeadepo District', 'Kpayan District', 'Plahn Nyarwleh District', 'Pynes Town District', 'Sanquin District #1', 'Sanquin District #2', 'Sanquin District #3', 'Seekon District', 'Wedjah District'],
        ];

        $stateColumns = $this->getTableColumns('states');
        $stateCount = 0;
        $lgaCount = 0;

        foreach ($liberianData as $countyName => $districts) {
            // Prepare county data
            $countyData = [
                'country_id' => $countryId,
                'name' => $countyName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            if (in_array('code', $stateColumns)) {
                $countyData['code'] = strtoupper(substr($countyName, 0, 2));
            }
            
            // Insert county as state
            $stateId = DB::table('states')->insertGetId($countyData);
            $stateCount++;

            // Insert districts for this county
            foreach ($districts as $districtName) {
                DB::table('lgas')->insert([
                    'state_id' => $stateId,
                    'name' => $districtName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $lgaCount++;
            }
        }

        echo "✅ Inserted {$stateCount} Liberian counties\n";
        echo "✅ Inserted {$lgaCount} Liberian districts\n";
    }

    /**
     * Generate a 2-letter state code
     */
    private function generateStateCode($stateName)
    {
        // Special cases
        $specialCodes = [
            'FCT' => 'FC',
            'Akwa Ibom' => 'AK',
            'Cross River' => 'CR',
        ];

        if (isset($specialCodes[$stateName])) {
            return $specialCodes[$stateName];
        }

        // Generate from first letters of words
        $words = explode(' ', $stateName);
        if (count($words) > 1) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($stateName, 0, 2));
    }
}