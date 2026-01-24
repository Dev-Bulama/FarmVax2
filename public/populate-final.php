<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>🌍 FINAL LOCATION POPULATE (Fixed for your schema)</h2>";
echo "<hr>";

$countryCount = DB::table('countries')->count();
$stateCount = DB::table('states')->count();
$lgaCount = DB::table('lgas')->count();

echo "<h3>Current Status:</h3>";
echo "<ul>";
echo "<li>Countries: <strong>{$countryCount}</strong></li>";
echo "<li>States: <strong>{$stateCount}</strong></li>";
echo "<li>LGAs: <strong>{$lgaCount}</strong></li>";
echo "</ul><hr>";

if (isset($_GET['populate']) && $_GET['populate'] == 'yes') {
    
    echo "<div style='background: #f0f0f0; padding: 20px; border-radius: 5px;'>";
    
    try {
        DB::beginTransaction();
        
        // Get Nigeria (should exist from previous attempt)
        $nigeria = DB::table('countries')->where('name', 'Nigeria')->first();
        $nigeriaId = $nigeria ? $nigeria->id : DB::table('countries')->insertGetId([
            'name' => 'Nigeria',
            'code' => 'NG',
            'phone_code' => '+234',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Nigeria (ID: {$nigeriaId})<br>";
        
        // Get Liberia
        $liberia = DB::table('countries')->where('name', 'Liberia')->first();
        $liberiaId = $liberia ? $liberia->id : DB::table('countries')->insertGetId([
            'name' => 'Liberia',
            'code' => 'LR',
            'phone_code' => '+231',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Liberia (ID: {$liberiaId})<br><br>";
        
        // Nigerian States
        echo "<p>📍 Adding Nigerian States...</p>";
        
        $nigerianStates = [
            'Lagos' => ['Alimosho', 'Ajeromi-Ifelodun', 'Kosofe', 'Mushin', 'Oshodi-Isolo', 'Ojo', 'Ikorodu', 'Surulere', 'Agege', 'Ifako-Ijaiye', 'Somolu', 'Amuwo-Odofin', 'Lagos Mainland', 'Ikeja', 'Eti-Osa', 'Badagry', 'Apapa', 'Lagos Island', 'Epe', 'Ibeju-Lekki'],
            'Kano' => ['Kano Municipal', 'Fagge', 'Dala', 'Gwale', 'Tarauni', 'Nassarawa', 'Kumbotso', 'Ungogo'],
            'Kaduna' => ['Kaduna North', 'Kaduna South', 'Chikun', 'Igabi', 'Zaria', 'Sabon Gari'],
            'Rivers' => ['Port Harcourt', 'Obio/Akpor', 'Okrika', 'Eleme'],
            'Oyo' => ['Ibadan North', 'Ibadan South-East', 'Ogbomosho North'],
            'Abuja' => ['Abuja Municipal', 'Gwagwalada', 'Kuje', 'Bwari'],
        ];
        
        $statesAdded = 0;
        $lgasAdded = 0;
        
        foreach ($nigerianStates as $stateName => $lgas) {
            // Insert state WITHOUT code column if it doesn't exist
            $stateData = [
                'country_id' => $nigeriaId,
                'name' => $stateName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Check if code column exists in states table
            try {
                DB::statement("SELECT code FROM states LIMIT 1");
                $stateData['code'] = strtoupper(substr($stateName, 0, 2));
            } catch (\Exception $e) {
                // code column doesn't exist, skip it
            }
            
            $stateId = DB::table('states')->insertGetId($stateData);
            $statesAdded++;
            
            foreach ($lgas as $lgaName) {
                // Insert LGA WITHOUT code column
                DB::table('lgas')->insert([
                    'state_id' => $stateId,
                    'name' => $lgaName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $lgasAdded++;
            }
        }
        
        echo "✅ Added {$statesAdded} Nigerian states<br>";
        echo "✅ Added {$lgasAdded} Nigerian LGAs<br><br>";
        
        DB::commit();
        
        echo "<hr>";
        echo "<h3 style='color: green; font-size: 24px;'>✅ SUCCESS!</h3>";
        
        $totalCountries = DB::table('countries')->count();
        $totalStates = DB::table('states')->count();
        $totalLgas = DB::table('lgas')->count();
        
        echo "<div style='background: white; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>📊 Final Summary:</h4>";
        echo "<ul style='font-size: 16px;'>";
        echo "<li><strong>Countries:</strong> {$totalCountries}</li>";
        echo "<li><strong>States:</strong> {$totalStates}</li>";
        echo "<li><strong>LGAs:</strong> {$totalLgas}</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<br><a href='/register-farmer' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px;'>✅ Test Registration Form</a>";
        
    } catch (\Exception $e) {
        DB::rollBack();
        echo "<p style='color: red; font-size: 18px;'>❌ ERROR: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
    
} else {
    
    echo "<br><a href='?populate=yes' style='padding: 20px 40px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 10px; font-size: 20px; font-weight: bold;'>🚀 POPULATE NOW</a>";
    
}

echo "<br><br><strong style='color: red; font-size: 18px;'>⚠️ DELETE ALL POPULATE FILES AFTER SUCCESS!</strong>";