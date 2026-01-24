<?php
echo "<h2>Fixing Outbreak Alert Status References</h2>";
echo "<hr>";

$basePath = dirname(__DIR__);

$files = [
    $basePath . '/app/Http/Controllers/Farmer/DashboardController.php',
    $basePath . '/resources/views/farmer/dashboard.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Replace status with is_active
        $content = str_replace("where('status', 'active')", "where('is_active', 1)", $content);
        $content = str_replace('where("status", "active")', 'where("is_active", 1)', $content);
        
        // Remove location_type filters since we don't need them
        $content = str_replace("->where('location_type', 'country')", "", $content);
        $content = str_replace("->where('location_type', 'state')", "", $content);
        $content = str_replace("->where('location_type', 'lga')", "", $content);
        
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
echo "<li>Replaced <code>where('status', 'active')</code> with <code>where('is_active', 1)</code></li>";
echo "<li>Removed unnecessary location_type filters</li>";
echo "</ul>";
echo "<br><a href='/clear-all-caches.php'>Clear Cache</a> | ";
echo "<a href='/farmer/dashboard'>Test Dashboard</a>";
echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";