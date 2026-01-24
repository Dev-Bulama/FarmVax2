<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "<h2>Professional Dashboard Diagnostic</h2>";
echo "<hr>";

// Find a professional user
$professional = User::where('role', 'animal_health_professional')->first();

if (!$professional) {
    echo "❌ No professional user found in database.<br>";
    echo "Creating test professional...<br>";
    
    $professional = User::create([
        'name' => 'Dr. Test Veterinarian',
        'email' => 'vet@test.com',
        'password' => bcrypt('password'),
        'phone' => '+234000000000',
        'role' => 'animal_health_professional',
        'country_id' => 1,
        'state_id' => 1,
        'lga_id' => 1,
        'is_active' => true,
        'status' => 'active',
        'account_status' => 'active',
    ]);
    
    echo "✅ Created test professional: {$professional->email}<br>";
}

echo "<h3>Testing Professional: {$professional->name} (ID: {$professional->id})</h3>";
echo "Email: <strong>{$professional->email}</strong><br>";
echo "Role: <strong>{$professional->role}</strong><br>";

echo "<hr>";

// Check professional profile
echo "<h3>1. Checking Professional Profile</h3>";
$profile = DB::table('animal_health_professionals')->where('user_id', $professional->id)->first();

if ($profile) {
    echo "✅ Professional profile exists<br>";
    echo "Status: <strong>{$profile->approval_status}</strong><br>";
    echo "Type: <strong>{$profile->professional_type}</strong><br>";
} else {
    echo "❌ No professional profile found<br>";
    echo "Creating professional profile...<br>";
    
    DB::table('animal_health_professionals')->insert([
        'user_id' => $professional->id,
        'professional_type' => 'veterinarian',
        'approval_status' => 'approved',
        'submitted_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✅ Professional profile created<br>";
}

echo "<hr>";

// Check dashboard files
echo "<h3>2. Checking Dashboard Files</h3>";

$files = [
    'resources/views/professional/dashboard.blade.php',
    'resources/views/professional/farm-records/index.blade.php',
    'resources/views/professional/service-requests/index.blade.php',
];

foreach ($files as $file) {
    $fullPath = base_path($file);
    if (file_exists($fullPath)) {
        echo "✅ Found: <strong>{$file}</strong><br>";
        
        // Check for common issues
        $content = file_get_contents($fullPath);
        if (strpos($content, 'next_due_date') !== false) {
            echo "   ⚠️ Contains 'next_due_date' column reference<br>";
        }
    } else {
        echo "❌ Missing: <strong>{$file}</strong><br>";
    }
}

echo "<hr>";

// Check routes
echo "<h3>3. Checking Routes</h3>";

try {
    $routes = [
        'professional.dashboard',
        'professional.farm-records.index',
        'professional.service-requests.index',
    ];
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "✅ Route exists: <strong>{$routeName}</strong> → {$url}<br>";
        } catch (\Exception $e) {
            echo "❌ Route missing: <strong>{$routeName}</strong><br>";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test AdService with professional
echo "<h3>4. Testing Ads for Professionals</h3>";

try {
    $adService = new \App\Services\AdService();
    
    $bannerAds = $adService->getBannerAds($professional);
    echo "Banner ads: <strong>{$bannerAds->count()}</strong><br>";
    
    $sidebarAds = $adService->getSidebarAds($professional);
    echo "Sidebar ads: <strong>{$sidebarAds->count()}</strong><br>";
    
    $inlineAds = $adService->getInlineAds($professional);
    echo "Inline ads: <strong>{$inlineAds->count()}</strong><br>";
    
    if ($bannerAds->count() > 0 || $sidebarAds->count() > 0 || $inlineAds->count() > 0) {
        echo "<br>✅ <strong style='color: green;'>Ads are working for professionals!</strong><br>";
    } else {
        echo "<br>⚠️ No ads found. Create ads targeting 'animal_health_professional'<br>";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check animal_health_professionals table structure
echo "<h3>5. Checking animal_health_professionals Table</h3>";

try {
    $columns = DB::select("SHOW COLUMNS FROM animal_health_professionals");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Column</th><th>Type</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td></tr>";
    }
    echo "</table>";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h3>📋 Summary:</h3>";
echo "<ol>";
echo "<li>Professional user: " . ($professional ? '✅' : '❌') . "</li>";
echo "<li>Professional profile: " . ($profile ? '✅' : '❌') . "</li>";
echo "<li>Dashboard files: Check above</li>";
echo "<li>Routes: Check above</li>";
echo "<li>Ads working: Check above</li>";
echo "</ol>";

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";