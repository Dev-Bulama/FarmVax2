<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

echo "<style>
    body { font-family: Arial; padding: 20px; background: #f5f5f5; }
    .success { color: #10b981; font-weight: bold; }
    .error { color: #ef4444; font-weight: bold; }
    .warning { color: #f59e0b; font-weight: bold; }
    h1 { color: #11455b; }
    h2 { color: #2fcb6e; border-bottom: 2px solid #2fcb6e; padding-bottom: 10px; }
</style>";

echo "<h1>🌍 FarmVax Location Data Import</h1>";

// Step 1: Check if JSON file exists
$jsonPath = storage_path('app/nigerian-states.json');
$uploadPath = base_path('public/uploads/nigerian-states.json');

if (!File::exists($jsonPath)) {
    // Try to copy from uploads
    if (File::exists($uploadPath)) {
        File::copy($uploadPath, $jsonPath);
        echo "<p class='success'>✅ Copied JSON file from uploads to storage</p>";
    } else {
        echo "<p class='error'>❌ Nigerian states JSON not found!</p>";
        echo "<p>Please upload <strong>nigerian-states.json</strong> to <code>storage/app/</code></p>";
        exit;
    }
}

echo "<h2>Step 1: JSON File Check</h2>";
echo "<p class='success'>✅ Nigerian states JSON found</p>";

// Step 2: Run seeder
echo "<h2>Step 2: Running Location Seeder</h2>";
echo "<pre>";

try {
    Artisan::call('db:seed', [
        '--class' => 'Database\\Seeders\\LocationDataSeeder',
        '--force' => true,
    ]);
    
    echo Artisan::output();
    echo "</pre>";
    echo "<p class='success'>✅ Location data seeded successfully!</p>";
    
    // Step 3: Show counts
    echo "<h2>Step 3: Database Summary</h2>";
    $countries = \DB::table('countries')->count();
    $states = \DB::table('states')->count();
    $lgas = \DB::table('lgas')->count();
    
    echo "<table style='width: 100%; border-collapse: collapse; background: white;'>";
    echo "<tr style='background: #11455b; color: white;'><th style='padding: 10px; border: 1px solid #ddd;'>Type</th><th style='padding: 10px; border: 1px solid #ddd;'>Count</th></tr>";
    echo "<tr><td style='padding: 10px; border: 1px solid #ddd;'>Countries</td><td style='padding: 10px; border: 1px solid #ddd;'><strong>{$countries}</strong></td></tr>";
    echo "<tr><td style='padding: 10px; border: 1px solid #ddd;'>States/Counties</td><td style='padding: 10px; border: 1px solid #ddd;'><strong>{$states}</strong></td></tr>";
    echo "<tr><td style='padding: 10px; border: 1px solid #ddd;'>LGAs/Districts</td><td style='padding: 10px; border: 1px solid #ddd;'><strong>{$lgas}</strong></td></tr>";
    echo "</table>";
    
    // Step 4: Sample data
    echo "<h2>Step 4: Sample Data</h2>";
    echo "<h3>Nigerian States (First 5)</h3>";
    $nigerianStates = \DB::table('states')
        ->join('countries', 'states.country_id', '=', 'countries.id')
        ->where('countries.name', 'Nigeria')
        ->select('states.name', 'states.code')
        ->limit(5)
        ->get();
    
    echo "<table style='width: 100%; border-collapse: collapse; background: white;'>";
    echo "<tr style='background: #11455b; color: white;'><th style='padding: 10px; border: 1px solid #ddd;'>State</th><th style='padding: 10px; border: 1px solid #ddd;'>Code</th></tr>";
    foreach ($nigerianStates as $state) {
        echo "<tr><td style='padding: 10px; border: 1px solid #ddd;'>{$state->name}</td><td style='padding: 10px; border: 1px solid #ddd;'>{$state->code}</td></tr>";
    }
    echo "</table>";
    
    // Step 5: Test links
    echo "<h2>Step 5: Test Your Location System</h2>";
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='/test-location-cascade.html' style='display: inline-block; padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Test Cascading Dropdowns</a>";
    echo "<a href='/check-location-data.php' style='display: inline-block; padding: 15px 30px; background: #11455b; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>View All Location Data</a>";
    echo "<a href='/register' style='display: inline-block; padding: 15px 30px; background: #f59e0b; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Test Registration Form</a>";
    echo "</div>";
    
} catch (\Exception $e) {
    echo "</pre>";
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><br><p style='color: red; font-weight: bold; font-size: 18px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</p>";