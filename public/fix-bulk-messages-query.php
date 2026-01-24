<?php
echo "<h2>Fixing Bulk Messages Query</h2>";
echo "<hr>";

$basePath = dirname(__DIR__);

$files = [
    $basePath . '/app/Http/Controllers/Farmer/DashboardController.php',
    $basePath . '/resources/views/farmer/dashboard.blade.php',
    $basePath . '/app/Http/Controllers/Individual/DashboardController.php',
    $basePath . '/resources/views/individual/dashboard.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Replace target_type with target_audience
        $content = str_replace('target_type', 'target_audience', $content);
        
        // Also replace targeting_data with target_roles or recipient_data
        $content = str_replace('targeting_data', 'target_roles', $content);
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            echo "✅ Fixed: " . basename($file) . "<br>";
        } else {
            echo "⚠️ No changes: " . basename($file) . "<br>";
        }
    } else {
        echo "❌ Not found: " . basename($file) . "<br>";
    }
}

echo "<br><hr>";
echo "<h3 style='color: green;'>✅ FIX COMPLETE!</h3>";
echo "<p>Changes made:</p>";
echo "<ul>";
echo "<li>Replaced <code>target_type</code> with <code>target_audience</code></li>";
echo "<li>Replaced <code>targeting_data</code> with <code>target_roles</code></li>";
echo "</ul>";

echo "<br><p><strong>Now simplify the query in DashboardController.php:</strong></p>";
echo "<p>Look for BulkMessage query and replace it with:</p>";
echo "<pre style='background: #f5f5f5; padding: 15px;'>";
echo htmlspecialchars('$bulkMessages = BulkMessage::where("status", "sent")
    ->where(function($query) {
        $query->where("target_audience", "all")
              ->orWhere("target_audience", "farmers");
    })
    ->orderBy("created_at", "desc")
    ->limit(3)
    ->get();');
echo "</pre>";

echo "<br><a href='/clear-all-caches.php'>Clear Cache</a> | ";
echo "<a href='/farmer/dashboard'>Test Dashboard</a>";
echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";