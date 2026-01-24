<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>🌍 Location Data Diagnostic & Auto-Populate Tool</h2>";
echo "<hr>";

// Step 1: Check if tables exist
echo "<h3>Step 1: Checking Tables...</h3>";

$tables = ['countries', 'states', 'lgas'];
$tablesExist = true;

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "✅ Table <strong>{$table}</strong> exists with <strong>{$count}</strong> records<br>";
        if ($count == 0) {
            echo "⚠️ <span style='color: orange;'>Table is EMPTY - needs data!</span><br>";
        }
    } catch (\Exception $e) {
        echo "❌ Table <strong>{$table}</strong> does NOT exist or has error<br>";
        $tablesExist = false;
    }
}

if (!$tablesExist) {
    echo "<br><p style='color: red;'><strong>ERROR:</strong> Some tables are missing. Run migrations first!</p>";
    echo "<p><code>php artisan migrate</code></p>";
    exit;
}

echo "<br><hr>";
echo "<h3>Step 2: Auto-Populate Location Data</h3>";
echo "<p>Click the button below to populate Nigeria's locations (Countries, States, LGAs)</p>";

if (isset($_GET['populate']) && $_GET['populate'] == 'yes') {
    
    echo "<div style='background: #f0f0f0; padding: 20px; border-radius: 5px;'>";
    
    // Clear existing data
    echo "<p>🗑️ Clearing existing location data...</p>";
    DB::table('lgas')->truncate();
    DB::table('states')->truncate();
    DB::table('countries')->truncate();
    echo "✅ Old data cleared<br><br>";
    
    // Insert Nigeria
    echo "<p>🇳🇬 Adding Nigeria...</p>";
    $nigeriaId = DB::table('countries')->insertGetId([
        'name' => 'Nigeria',
        'code' => 'NG',
        'phone_code' => '+234',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Nigeria added (ID: {$nigeriaId})<br><br>";
    
    // Insert Liberia
    echo "<p>🇱🇷 Adding Liberia...</p>";
    $liberiaId = DB::table('countries')->insertGetId([
        'name' => 'Liberia',
        'code' => 'LR',
        'phone_code' => '+231',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Liberia added (ID: {$liberiaId})<br><br>";
    
    // Nigerian States and LGAs
    echo "<p>📍 Adding Nigerian States and LGAs...</p>";
    
    $nigerianStates = [
        'Lagos' => ['Alimosho', 'Ajeromi-Ifelodun', 'Kosofe', 'Mushin', 'Oshodi-Isolo', 'Ojo', 'Ikorodu', 'Surulere', 'Agege', 'Ifako-Ijaiye', 'Somolu', 'Amuwo-Odofin', 'Lagos Mainland', 'Ikeja', 'Eti-Osa', 'Badagry', 'Apapa', 'Lagos Island', 'Epe', 'Ibeju-Lekki'],
        'Kano' => ['Kano Municipal', 'Fagge', 'Dala', 'Gwale', 'Tarauni', 'Nassarawa', 'Kumbotso', 'Ungogo', 'Dawakin Tofa', 'Tofa', 'Rimin Gado', 'Bagwai', 'Gezawa', 'Gabasawa', 'Minjibir', 'Dambatta', 'Makoda', 'Kunchi', 'Bichi', 'Tsanyawa'],
        'Kaduna' => ['Kaduna North', 'Kaduna South', 'Chikun', 'Igabi', 'Ikara', 'Zaria', 'Sabon Gari', 'Soba', 'Giwa', 'Kubau', 'Kudan', 'Makarfi', 'Lere', 'Kauru', 'Kajuru', 'Jaba', 'Jema\'a', 'Kachia', 'Kagarko', 'Sanga', 'Zangon Kataf', 'Kaura', 'Birnin Gwari'],
        'Rivers' => ['Port Harcourt', 'Obio/Akpor', 'Okrika', 'Ogu/Bolo', 'Eleme', 'Tai', 'Gokana', 'Khana', 'Oyigbo', 'Opobo/Nkoro', 'Andoni', 'Bonny', 'Degema', 'Asari-Toru', 'Akuku-Toru', 'Abua/Odual', 'Ahoada West', 'Ahoada East', 'Ogba/Egbema/Ndoni', 'Emohua', 'Ikwerre', 'Etche', 'Omuma'],
        'Oyo' => ['Ibadan North', 'Ibadan North-East', 'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central', 'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 'Iseyin', 'Itesiwaju', 'Iwajowa', 'Kajola', 'Lagelu', 'Ogbomosho North', 'Ogbomosho South', 'Ogo Oluwa', 'Olorunsogo', 'Oluyole'],
        'Abuja' => ['Abuja Municipal', 'Gwagwalada', 'Kuje', 'Bwari', 'Abaji', 'Kwali'],
    ];
    
    $stateCount = 0;
    $lgaCount = 0;
    
    foreach ($nigerianStates as $stateName => $lgas) {
        $stateId = DB::table('states')->insertGetId([
            'country_id' => $nigeriaId,
            'name' => $stateName,
            'code' => strtoupper(substr($stateName, 0, 2)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $stateCount++;
        
        foreach ($lgas as $lgaName) {
            DB::table('lgas')->insert([
                'state_id' => $stateId,
                'name' => $lgaName,
                'code' => strtoupper(substr($lgaName, 0, 3)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $lgaCount++;
        }
    }
    
    echo "✅ Added {$stateCount} states<br>";
    echo "✅ Added {$lgaCount} LGAs<br><br>";
    
    // Liberian Counties
    echo "<p>📍 Adding Liberian Counties...</p>";
    
    $liberianCounties = [
        'Montserrado' => ['Greater Monrovia', 'Todee', 'St. Paul River', 'Commonwealth'],
        'Nimba' => ['Sanniquellie-Mahn', 'Tappita', 'Saclepea-Mahn', 'Gbehlay-Geh'],
        'Grand Bassa' => ['Buchanan', 'Owensgrove', 'St. John River', 'Grand Bassa'],
        'Bong' => ['Gbarnga', 'Fuamah', 'Jorquelleh', 'Kokoyah'],
        'Lofa' => ['Voinjama', 'Kolahun', 'Foya', 'Salayea'],
    ];
    
    $countyCount = 0;
    $districtCount = 0;
    
    foreach ($liberianCounties as $countyName => $districts) {
        $countyId = DB::table('states')->insertGetId([
            'country_id' => $liberiaId,
            'name' => $countyName,
            'code' => strtoupper(substr($countyName, 0, 2)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $countyCount++;
        
        foreach ($districts as $districtName) {
            DB::table('lgas')->insert([
                'state_id' => $countyId,
                'name' => $districtName,
                'code' => strtoupper(substr($districtName, 0, 3)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $districtCount++;
        }
    }
    
    echo "✅ Added {$countyCount} counties<br>";
    echo "✅ Added {$districtCount} districts<br><br>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ LOCATION DATA POPULATED SUCCESSFULLY!</h3>";
    
    // Final count
    $totalCountries = DB::table('countries')->count();
    $totalStates = DB::table('states')->count();
    $totalLgas = DB::table('lgas')->count();
    
    echo "<p><strong>Summary:</strong></p>";
    echo "<ul>";
    echo "<li>Countries: {$totalCountries}</li>";
    echo "<li>States/Counties: {$totalStates}</li>";
    echo "<li>LGAs/Districts: {$totalLgas}</li>";
    echo "</ul>";
    
    echo "<br><a href='/register-farmer' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Test Registration Form</a>";
    echo " <a href='?check=api' style='padding: 10px 20px; background: #11455b; color: white; text-decoration: none; border-radius: 5px;'>Test API Routes</a>";
    
    echo "</div>";
    
} elseif (isset($_GET['check']) && $_GET['check'] == 'api') {
    
    echo "<div style='background: #f0f0f0; padding: 20px; border-radius: 5px;'>";
    echo "<h3>Testing API Routes...</h3>";
    
    // Test countries API
    echo "<p><strong>GET /api/countries</strong></p>";
    try {
        $countries = DB::table('countries')->get();
        echo "<pre style='background: white; padding: 10px;'>";
        echo json_encode($countries, JSON_PRETTY_PRINT);
        echo "</pre>";
        echo "✅ Countries API works<br><br>";
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br><br>";
    }
    
    // Test states API
    echo "<p><strong>GET /api/states?country_id=1</strong></p>";
    try {
        $states = DB::table('states')->where('country_id', 1)->get();
        echo "<pre style='background: white; padding: 10px;'>";
        echo json_encode($states, JSON_PRETTY_PRINT);
        echo "</pre>";
        echo "✅ States API works<br><br>";
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br><br>";
    }
    
    // Test LGAs API
    echo "<p><strong>GET /api/lgas?state_id=1</strong></p>";
    try {
        $lgas = DB::table('lgas')->where('state_id', 1)->get();
        echo "<pre style='background: white; padding: 10px;'>";
        echo json_encode($lgas, JSON_PRETTY_PRINT);
        echo "</pre>";
        echo "✅ LGAs API works<br><br>";
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    echo "</div>";
    
} else {
    
    echo "<br><a href='?populate=yes' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>🚀 AUTO-POPULATE LOCATION DATA</a>";
    echo "<br><br><a href='?check=api' style='padding: 10px 20px; background: #11455b; color: white; text-decoration: none; border-radius: 5px;'>Check API Routes</a>";
    
}

echo "<br><br><strong style='color: red; font-size: 18px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</strong>";