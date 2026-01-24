<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Complete Ad System Fix</h2>";
echo "<hr>";

// STEP 1: Create storage link
echo "<h3>Step 1: Creating Storage Link</h3>";
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "✅ Storage link already exists!<br>";
} else {
    try {
        symlink($target, $link);
        echo "✅ Storage link created successfully!<br>";
    } catch (Exception $e) {
        echo "❌ Error creating symlink: " . $e->getMessage() . "<br>";
        echo "⚠️ Try creating it manually or via hosting control panel<br>";
    }
}

echo "<hr>";

// STEP 2: Fix image_url paths in database
echo "<h3>Step 2: Fixing Image URLs in Database</h3>";

$ads = DB::table('ads')->get();
$fixed = 0;

foreach ($ads as $ad) {
    $needsUpdate = false;
    $newImageUrl = $ad->image_url;
    
    // Remove duplicate /storage/ if exists
    if ($ad->image_url && strpos($ad->image_url, '/storage/storage/') !== false) {
        $newImageUrl = str_replace('/storage/storage/', 'ads/', $ad->image_url);
        $needsUpdate = true;
    }
    
    // Remove leading /storage/ and keep just ads/filename
    if ($ad->image_url && strpos($ad->image_url, '/storage/ads/') === 0) {
        $newImageUrl = str_replace('/storage/', '', $ad->image_url);
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        DB::table('ads')->where('id', $ad->id)->update(['image_url' => $newImageUrl]);
        echo "✅ Fixed ad #{$ad->id}: {$ad->title}<br>";
        echo "   Old: {$ad->image_url}<br>";
        echo "   New: {$newImageUrl}<br><br>";
        $fixed++;
    }
}

echo $fixed > 0 ? "✅ Fixed {$fixed} ad image paths<br>" : "✅ All image paths are correct<br>";

echo "<hr>";

// STEP 3: Check if dashboard is updated
echo "<h3>Step 3: Testing Ad Display</h3>";

$farmer = \App\Models\User::where('role', 'farmer')->first();

if (!$farmer) {
    echo "❌ No farmer user found<br>";
} else {
    echo "Testing with farmer: <strong>{$farmer->name}</strong><br><br>";
    
    try {
        $adService = new \App\Services\AdService();
        
        $bannerAds = $adService->getBannerAds($farmer);
        echo "Banner ads found: <strong>{$bannerAds->count()}</strong><br>";
        
        $sidebarAds = $adService->getSidebarAds($farmer);
        echo "Sidebar ads found: <strong>{$sidebarAds->count()}</strong><br>";
        
        $inlineAds = $adService->getInlineAds($farmer);
        echo "Inline ads found: <strong>{$inlineAds->count()}</strong><br>";
        
        if ($bannerAds->count() > 0 || $sidebarAds->count() > 0 || $inlineAds->count() > 0) {
            echo "<br>✅ <strong style='color: green;'>Ads are being fetched correctly!</strong><br>";
            
            echo "<br><h4>Sample Banner Ad:</h4>";
            if ($bannerAds->count() > 0) {
                $ad = $bannerAds->first();
                echo "Title: <strong>{$ad->title}</strong><br>";
                echo "Type: <strong>{$ad->type}</strong><br>";
                echo "Image URL: <code>{$ad->image_url}</code><br>";
                
                // Generate correct URL
                $correctUrl = asset('storage/' . $ad->image_url);
                echo "Full URL: <code>{$correctUrl}</code><br>";
                echo "Preview: <img src='{$correctUrl}' style='max-width: 300px; margin-top: 10px;'><br>";
            }
        } else {
            echo "<br>❌ <strong style='color: red;'>No ads found!</strong><br>";
        }
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";

// STEP 4: Show updated ad URLs
echo "<h3>Step 4: Current Ad Image URLs</h3>";
$ads = DB::table('ads')->get();

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Title</th><th>Type</th><th>Image URL</th><th>Full URL</th><th>Preview</th></tr>";

foreach ($ads as $ad) {
    echo "<tr>";
    echo "<td>{$ad->id}</td>";
    echo "<td>{$ad->title}</td>";
    echo "<td>{$ad->type}</td>";
    echo "<td><code>{$ad->image_url}</code></td>";
    
    $fullUrl = asset('storage/' . $ad->image_url);
    echo "<td><small>{$fullUrl}</small></td>";
    echo "<td><img src='{$fullUrl}' style='max-width: 100px; max-height: 60px;' onerror=\"this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%2260%22%3E%3Crect fill=%22%23ddd%22 width=%22100%22 height=%2260%22/%3E%3Ctext fill=%22%23999%22 font-size=%2210%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3EError%3C/text%3E%3C/svg%3E';\"></td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";

// Clear caches
Artisan::call('cache:clear');
Artisan::call('view:clear');
Artisan::call('route:clear');
Artisan::call('config:clear');

echo "<h3>✅ Caches Cleared</h3>";

echo "<hr>";

echo "<h3>📋 Next Steps:</h3>";
echo "<ol>";
echo "<li>✅ Storage link created (if it didn't exist)</li>";
echo "<li>✅ Image URLs fixed in database</li>";
echo "<li>✅ Caches cleared</li>";
echo "<li><strong>Now go to: <a href='/farmer/dashboard' target='_blank'>Farmer Dashboard</a></strong></li>";
echo "<li>You should see ads displayed</li>";
echo "<li>If ads still don't show, check the dashboard blade file was updated</li>";
echo "</ol>";

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER SUCCESS!</strong>";