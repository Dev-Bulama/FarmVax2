<?php
require __DIR__ . '/../vendor/autoload.php';

echo "<h2>Current web.php File</h2>";
echo "<hr>";

$webFile = __DIR__ . '/../routes/web.php';

if (file_exists($webFile)) {
    $content = file_get_contents($webFile);
    
    echo "<h3>File Contents:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 20px; overflow-x: auto;'>";
    echo htmlspecialchars($content);
    echo "</pre>";
    
    // Search for farmer routes
    if (strpos($content, "prefix('farmer')") !== false) {
        echo "<p style='color: green;'>✅ Found farmer routes section</p>";
    } else {
        echo "<p style='color: red;'>❌ No farmer routes section found</p>";
    }
    
    // Search for farm-records routes
    if (strpos($content, "farm-records") !== false) {
        echo "<p style='color: green;'>✅ Found farm-records routes</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No farm-records routes found - needs to be added</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ web.php file not found</p>";
}

echo "<br><br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";