<?php
echo "<h2>COMPREHENSIVE DASHBOARD FIX</h2>";
echo "<hr>";
echo "<p><strong>This will simplify ALL queries to match your actual database structure.</strong></p>";
echo "<hr>";

$basePath = dirname(__DIR__);

// Fix DashboardController.php
$dashboardController = $basePath . '/app/Http/Controllers/Farmer/DashboardController.php';
if (file_exists($dashboardController)) {
    $content = file_get_contents($dashboardController);
    $originalContent = $content;
    
    // SIMPLE ADS QUERY
    $oldAdsQuery = <<<'EOD'
$ads = Ad::where('is_active', 1)
EOD;
    
    $newAdsQuery = <<<'EOD'
// Simplified Ads Query
        $ads = \App\Models\Ad::where('is_active', 1)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->limit(3)
            ->get()
EOD;
    
    // SIMPLE BULK MESSAGES QUERY  
    $oldBulkQuery = <<<'EOD'
$bulkMessages = BulkMessage::where('status', 'sent')
EOD;
    
    $newBulkQuery = <<<'EOD'
// Simplified Bulk Messages Query
        $bulkMessages = \App\Models\BulkMessage::where('status', 'sent')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
EOD;
    
    // SIMPLE OUTBREAK ALERTS QUERY
    $oldOutbreakQuery = <<<'EOD'
$outbreakAlerts = OutbreakAlert::where('is_active', 1)
EOD;
    
    $newOutbreakQuery = <<<'EOD'
// Simplified Outbreak Alerts Query
        $outbreakAlerts = \App\Models\OutbreakAlert::where('is_active', 1)
            ->orderBy('severity', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
EOD;
    
    // Apply fixes
    $content = preg_replace('/\$ads = Ad::where\(.*?\)[\s\S]*?->get\(\);/s', $newAdsQuery . ';', $content);
    $content = preg_replace('/\$bulkMessages = BulkMessage::where\(.*?\)[\s\S]*?->get\(\);/s', $newBulkQuery . ';', $content);
    $content = preg_replace('/\$outbreakAlerts = OutbreakAlert::where\(.*?\)[\s\S]*?->get\(\);/s', $newOutbreakQuery . ';', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($dashboardController, $content);
        echo "✅ Fixed: DashboardController.php<br>";
    } else {
        echo "⚠️ No changes: DashboardController.php<br>";
    }
}

// Fix dashboard.blade.php
$dashboardBlade = $basePath . '/resources/views/farmer/dashboard.blade.php';
if (file_exists($dashboardBlade)) {
    $content = file_get_contents($dashboardBlade);
    $originalContent = $content;
    
    // Remove all complex queries from blade files
    $content = preg_replace('/\$outbreakAlerts = .*?OutbreakAlert::where.*?->get\(\);/s', 
        '$outbreakAlerts = \App\Models\OutbreakAlert::where("is_active", 1)->orderBy("created_at", "desc")->limit(5)->get();', 
        $content);
    
    if ($content !== $originalContent) {
        file_put_contents($dashboardBlade, $content);
        echo "✅ Fixed: dashboard.blade.php<br>";
    }
}

echo "<br><hr>";
echo "<h3 style='color: green;'>✅ FIXES APPLIED!</h3>";
echo "<p><strong>Changes made:</strong></p>";
echo "<ul>";
echo "<li>Removed ALL complex targeting queries</li>";
echo "<li>Simplified ads to just show active ads</li>";
echo "<li>Simplified bulk messages to just show sent messages</li>";
echo "<li>Simplified outbreak alerts to just show active alerts</li>";
echo "</ul>";

echo "<br><p><strong style='color: orange;'>⚠️ IMPORTANT: Now update AdService.php</strong></p>";
echo "<p>You need to simplify the AdService class to work with your database structure.</p>";

echo "<br><a href='/clear-all-caches.php' style='padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px;'>Clear Cache</a>";
echo " <a href='/farmer/dashboard' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Test Dashboard</a>";

echo "<br><br><strong style='color: red; font-size: 18px;'>DELETE THIS FILE AFTER SUCCESS!</strong>";