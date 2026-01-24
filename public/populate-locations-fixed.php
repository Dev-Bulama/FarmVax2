<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>🌍 POPULATE LOCATION DATA - FIXED VERSION</h2>";
echo "<hr>";

// Check current data
$countryCount = DB::table('countries')->count();
$stateCount = DB::table('states')->count();
$lgaCount = DB::table('lgas')->count();

echo "<h3>Current Status:</h3>";
echo "<ul>";
echo "<li>Countries: <strong>{$countryCount}</strong></li>";
echo "<li>States: <strong>{$stateCount}</strong></li>";
echo "<li>LGAs: <strong>{$lgaCount}</strong></li>";
echo "</ul>";

if ($countryCount == 0) {
    echo "<p style='color: red;'>⚠️ Countries table is EMPTY! Click below to populate.</p>";
}

echo "<hr>";

if (isset($_GET['populate']) && $_GET['populate'] == 'yes') {
    
    echo "<div style='background: #f0f0f0; padding: 20px; border-radius: 5px;'>";
    
    try {
        DB::beginTransaction();
        
        // Add Nigeria
        echo "<p>🇳🇬 Adding Nigeria...</p>";
        $nigeriaId = DB::table('countries')->insertGetId([
            'name' => 'Nigeria',
            'code' => 'NG',
            'phone_code' => '+234',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Nigeria added (ID: {$nigeriaId})<br>";
        
        // Add Liberia
        echo "<p>🇱🇷 Adding Liberia...</p>";
        $liberiaId = DB::table('countries')->insertGetId([
            'name' => 'Liberia',
            'code' => 'LR',
            'phone_code' => '+231',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Liberia added (ID: {$liberiaId})<br><br>";
        
        // Nigerian States
        echo "<p>📍 Adding Nigerian States...</p>";
        
        $nigerianStates = [
            'Lagos' => ['Alimosho', 'Ajeromi-Ifelodun', 'Kosofe', 'Mushin', 'Oshodi-Isolo', 'Ojo', 'Ikorodu', 'Surulere', 'Agege', 'Ifako-Ijaiye', 'Somolu', 'Amuwo-Odofin', 'Lagos Mainland', 'Ikeja', 'Eti-Osa', 'Badagry', 'Apapa', 'Lagos Island', 'Epe', 'Ibeju-Lekki'],
            'Kano' => ['Kano Municipal', 'Fagge', 'Dala', 'Gwale', 'Tarauni', 'Nassarawa', 'Kumbotso', 'Ungogo', 'Dawakin Tofa', 'Tofa'],
            'Kaduna' => ['Kaduna North', 'Kaduna South', 'Chikun', 'Igabi', 'Ikara', 'Zaria', 'Sabon Gari', 'Soba', 'Giwa', 'Kubau'],
            'Rivers' => ['Port Harcourt', 'Obio/Akpor', 'Okrika', 'Ogu/Bolo', 'Eleme', 'Tai', 'Gokana', 'Khana', 'Oyigbo', 'Opobo/Nkoro'],
            'Oyo' => ['Ibadan North', 'Ibadan North-East', 'Ibadan South-East', 'Ibadan South-West', 'Iseyin', 'Ogbomosho North', 'Ogbomosho South'],
            'Abuja' => ['Abuja Municipal', 'Gwagwalada', 'Kuje', 'Bwari', 'Abaji', 'Kwali'],
            'Katsina' => ['Katsina', 'Daura', 'Funtua', 'Dutsin-Ma', 'Malumfashi'],
            'Ogun' => ['Abeokuta South', 'Abeokuta North', 'Ijebu Ode', 'Sagamu', 'Ado-Odo/Ota'],
            'Anambra' => ['Awka South', 'Awka North', 'Onitsha South', 'Onitsha North', 'Nnewi North'],
            'Delta' => ['Warri South', 'Warri North', 'Ughelli North', 'Ughelli South', 'Sapele'],
        ];
        
        $statesAdded = 0;
        $lgasAdded = 0;
        
        foreach ($nigerianStates as $stateName => $lgas) {
            $stateId = DB::table('states')->insertGetId([
                'country_id' => $nigeriaId,
                'name' => $stateName,
                'code' => strtoupper(substr($stateName, 0, 2)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $statesAdded++;
            
            foreach ($lgas as $lgaName) {
                DB::table('lgas')->insert([
                    'state_id' => $stateId,
                    'name' => $lgaName,
                    'code' => strtoupper(substr($lgaName, 0, 3)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $lgasAdded++;
            }
        }
        
        echo "✅ Added {$statesAdded} Nigerian states<br>";
        echo "✅ Added {$lgasAdded} Nigerian LGAs<br><br>";
        
        // Liberian Counties
        echo "<p>📍 Adding Liberian Counties...</p>";
        
        $liberianCounties = [
            'Montserrado' => ['Greater Monrovia', 'Todee', 'St. Paul River', 'Commonwealth'],
            'Nimba' => ['Sanniquellie-Mahn', 'Tappita', 'Saclepea-Mahn'],
            'Grand Bassa' => ['Buchanan', 'Owensgrove', 'St. John River'],
            'Bong' => ['Gbarnga', 'Fuamah', 'Jorquelleh'],
            'Lofa' => ['Voinjama', 'Kolahun', 'Foya'],
        ];
        
        $countiesAdded = 0;
        $districtsAdded = 0;
        
        foreach ($liberianCounties as $countyName => $districts) {
            $countyId = DB::table('states')->insertGetId([
                'country_id' => $liberiaId,
                'name' => $countyName,
                'code' => strtoupper(substr($countyName, 0, 2)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $countiesAdded++;
            
            foreach ($districts as $districtName) {
                DB::table('lgas')->insert([
                    'state_id' => $countyId,
                    'name' => $districtName,
                    'code' => strtoupper(substr($districtName, 0, 3)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $districtsAdded++;
            }
        }
        
        echo "✅ Added {$countiesAdded} Liberian counties<br>";
        echo "✅ Added {$districtsAdded} Liberian districts<br><br>";
        
        DB::commit();
        
        echo "<hr>";
        echo "<h3 style='color: green; font-size: 24px;'>✅ SUCCESS! ALL LOCATION DATA ADDED!</h3>";
        
        // Final count
        $totalCountries = DB::table('countries')->count();
        $totalStates = DB::table('states')->count();
        $totalLgas = DB::table('lgas')->count();
        
        echo "<div style='background: white; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>📊 Summary:</h4>";
        echo "<ul style='font-size: 16px;'>";
        echo "<li><strong>Countries:</strong> {$totalCountries} (Nigeria, Liberia)</li>";
        echo "<li><strong>States/Counties:</strong> {$totalStates}</li>";
        echo "<li><strong>LGAs/Districts:</strong> {$totalLgas}</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<br><a href='/register-farmer' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>✅ Test Registration Form</a>";
        echo " <a href='/api/countries' style='padding: 15px 30px; background: #11455b; color: white; text-decoration: none; border-radius: 5px;' target='_blank'>View Countries API</a>";
        
    } catch (\Exception $e) {
        DB::rollBack();
        echo "<p style='color: red; font-size: 18px;'>❌ ERROR: " . $e->getMessage() . "</p>";
        echo "<p>Please try again or contact support.</p>";
    }
    
    echo "</div>";
    
} else {
    
    echo "<br><a href='?populate=yes' style='padding: 20px 40px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 10px; font-size: 20px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>🚀 POPULATE ALL LOCATION DATA NOW</a>";
    echo "<br><br><p style='color: gray; font-size: 14px;'>This will add Nigeria, Liberia, and all their states/counties and LGAs/districts</p>";
    
}

echo "<br><br><hr>";
echo "<p style='font-size: 12px; color: #666;'>After populating, test the dropdowns at: <a href='/register-farmer'>/register-farmer</a></p>";
echo "<br><strong style='color: red; font-size: 18px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</strong>";