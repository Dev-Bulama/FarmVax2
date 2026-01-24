<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Ad;
use App\Models\User;

echo "<h2>Ad Display Diagnostic Tool</h2>";
echo "<hr>";

// Get current logged-in farmer (or use a test farmer)
$farmer = User::where('role', 'farmer')->first();

if (!$farmer) {
    echo "❌ No farmer user found in database.<br>";
    exit;
}

echo "<h3>Testing for User: {$farmer->name} (ID: {$farmer->id})</h3>";
echo "Role: <strong>{$farmer->role}</strong><br>";
echo "Country: <strong>" . ($farmer->country->name ?? 'N/A') . "</strong><br>";
echo "State: <strong>" . ($farmer->state->name ?? 'N/A') . "</strong><br>";
echo "LGA: <strong>" . ($farmer->lga->name ?? 'N/A') . "</strong><br>";

echo "<hr>";

// Check ads table structure
echo "<h3>1. Checking Ads Table Structure</h3>";
$columns = DB::select("SHOW COLUMNS FROM ads");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Default</th></tr>";
foreach ($columns as $col) {
    echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>{$col->Default}</td></tr>";
}
echo "</table><br>";

echo "<hr>";

// Get all ads from database
echo "<h3>2. All Ads in Database</h3>";
$allAds = DB::table('ads')->get();
echo "Total ads in database: <strong>{$allAds->count()}</strong><br><br>";

if ($allAds->count() > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Title</th><th>Type</th><th>Active</th><th>Start Date</th><th>End Date</th><th>Target Audience</th><th>Target Location</th></tr>";
    foreach ($allAds as $ad) {
        echo "<tr>";
        echo "<td>{$ad->id}</td>";
        echo "<td>{$ad->title}</td>";
        echo "<td>{$ad->type}</td>";
        echo "<td>" . ($ad->is_active ? '✅ Yes' : '❌ No') . "</td>";
        echo "<td>{$ad->start_date}</td>";
        echo "<td>{$ad->end_date}</td>";
        echo "<td>" . ($ad->target_audience ?? 'NULL') . "</td>";
        echo "<td>" . ($ad->target_location ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
} else {
    echo "⚠️ No ads found in database.<br>";
}

echo "<hr>";

// Test AdService query
echo "<h3>3. Testing AdService Query</h3>";

try {
    // Simulate what AdService does
    $query = Ad::where('is_active', true);
    echo "Query 1 - Active ads: <strong>" . $query->count() . "</strong><br>";
    
    $query = Ad::where('is_active', true)
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now());
    echo "Query 2 - Active + Date range: <strong>" . $query->count() . "</strong><br>";
    
    // Check target_audience
    $query = Ad::where('is_active', true)
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->where(function($q) use ($farmer) {
            $q->whereJsonContains('target_audience', 'all')
              ->orWhereJsonContains('target_audience', $farmer->role);
        });
    echo "Query 3 - Active + Date + Target Audience: <strong>" . $query->count() . "</strong><br>";
    
    $finalAds = $query->get();
    
    if ($finalAds->count() > 0) {
        echo "<br>✅ <strong style='color: green;'>Found {$finalAds->count()} matching ads!</strong><br><br>";
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Title</th><th>Type</th><th>Description</th><th>Image</th><th>Link</th></tr>";
        foreach ($finalAds as $ad) {
            echo "<tr>";
            echo "<td>{$ad->id}</td>";
            echo "<td>{$ad->title}</td>";
            echo "<td>{$ad->type}</td>";
            echo "<td>" . substr($ad->description, 0, 50) . "...</td>";
            echo "<td>" . ($ad->image_url ? '✅ Yes' : '❌ No') . "</td>";
            echo "<td>" . ($ad->link_url ? '✅ Yes' : '❌ No') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "<br>❌ <strong style='color: red;'>No ads match the criteria!</strong><br>";
        
        // Debug why
        echo "<br><h4>Debugging:</h4>";
        
        // Check each condition
        $activeAds = Ad::where('is_active', true)->get();
        echo "- Active ads (is_active = 1): <strong>{$activeAds->count()}</strong><br>";
        
        $dateAds = Ad::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
        echo "- Active ads within date range: <strong>{$dateAds->count()}</strong><br>";
        
        if ($dateAds->count() === 0 && $activeAds->count() > 0) {
            echo "<br>⚠️ <strong>Issue: Date range problem</strong><br>";
            echo "Current date: " . now()->format('Y-m-d H:i:s') . "<br><br>";
            
            foreach ($activeAds as $ad) {
                echo "Ad '{$ad->title}':<br>";
                echo "  - Start: {$ad->start_date}<br>";
                echo "  - End: {$ad->end_date}<br>";
                echo "  - Current date is " . (now() >= $ad->start_date ? '✅' : '❌') . " after start<br>";
                echo "  - Current date is " . (now() <= $ad->end_date ? '✅' : '❌') . " before end<br><br>";
            }
        }
        
        // Check target audience
        echo "<br>Checking target_audience field:<br>";
        foreach ($activeAds as $ad) {
            $targetAudience = json_decode($ad->target_audience, true);
            echo "Ad '{$ad->title}': ";
            echo "target_audience = " . ($ad->target_audience ?? 'NULL') . "<br>";
            
            if (is_array($targetAudience)) {
                echo "  Decoded: " . implode(', ', $targetAudience) . "<br>";
                echo "  Contains 'all': " . (in_array('all', $targetAudience) ? '✅ Yes' : '❌ No') . "<br>";
                echo "  Contains 'farmer': " . (in_array('farmer', $targetAudience) ? '✅ Yes' : '❌ No') . "<br>";
            } else {
                echo "  ❌ Not valid JSON array<br>";
            }
            echo "<br>";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error testing query: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";

// Test if AdService class exists
echo "<h3>4. Checking AdService Class</h3>";
if (class_exists('\App\Services\AdService')) {
    echo "✅ AdService class exists<br>";
    
    try {
        $adService = new \App\Services\AdService();
        $bannerAds = $adService->getBannerAds($farmer);
        echo "✅ getBannerAds() returned: <strong>{$bannerAds->count()}</strong> ads<br>";
        
        $sidebarAds = $adService->getSidebarAds($farmer);
        echo "✅ getSidebarAds() returned: <strong>{$sidebarAds->count()}</strong> ads<br>";
        
        $inlineAds = $adService->getInlineAds($farmer);
        echo "✅ getInlineAds() returned: <strong>{$inlineAds->count()}</strong> ads<br>";
    } catch (\Exception $e) {
        echo "❌ Error calling AdService: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ AdService class NOT FOUND at App\\Services\\AdService<br>";
    echo "Expected location: app/Services/AdService.php<br>";
}

echo "<hr>";

echo "<h3>📝 Summary & Recommendations:</h3>";
echo "<ol>";
echo "<li>Check if ads are marked as active (is_active = 1)</li>";
echo "<li>Check if ads have valid date ranges (start_date <= today <= end_date)</li>";
echo "<li>Check if target_audience is properly stored as JSON array</li>";
echo "<li>Ensure AdService.php exists in app/Services/AdService.php</li>";
echo "</ol>";

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER TESTING!</strong>";