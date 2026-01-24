<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Checking Individual Livestock Setup</h2>";
echo "<hr>";

// Check if controller exists
$controllerPath = base_path('app/Http/Controllers/Individual/LivestockController.php');
$controllerExists = file_exists($controllerPath);

echo "<h3>1. Controller File:</h3>";
if ($controllerExists) {
    echo "<p style='color: green;'>✅ Individual/LivestockController.php exists</p>";
} else {
    echo "<p style='color: red;'>❌ Individual/LivestockController.php NOT FOUND</p>";
    echo "<p>Path checked: {$controllerPath}</p>";
}

// Check routes
echo "<br><h3>2. Routes Check:</h3>";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $livestockRoutes = [];
    
    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'individual/livestock')) {
            $livestockRoutes[] = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'methods' => implode(', ', $route->methods()),
            ];
        }
    }
    
    if (count($livestockRoutes) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>URI</th><th>Route Name</th><th>Controller</th><th>Methods</th></tr>";
        foreach ($livestockRoutes as $route) {
            echo "<tr>";
            echo "<td>{$route['uri']}</td>";
            echo "<td>{$route['name']}</td>";
            echo "<td style='font-size: 11px;'>{$route['action']}</td>";
            echo "<td>{$route['methods']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check if using correct controller
        $indexRoute = collect($livestockRoutes)->firstWhere('name', 'individual.livestock.index');
        if ($indexRoute) {
            if (str_contains($indexRoute['action'], 'Individual\LivestockController')) {
                echo "<p style='color: green;'>✅ Routes are using Individual\LivestockController</p>";
            } else {
                echo "<p style='color: red;'>❌ Routes are NOT using Individual\LivestockController</p>";
                echo "<p>Current: {$indexRoute['action']}</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>❌ No individual livestock routes found!</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Check view file
echo "<br><h3>3. View File:</h3>";
$viewPath = base_path('resources/views/individual/livestock/index.blade.php');
if (file_exists($viewPath)) {
    echo "<p style='color: green;'>✅ View file exists</p>";
    
    // Check if it uses $stats
    $viewContent = file_get_contents($viewPath);
    if (str_contains($viewContent, '$stats')) {
        echo "<p style='color: green;'>✅ View references \$stats variable</p>";
    }
} else {
    echo "<p style='color: red;'>❌ View file NOT FOUND</p>";
    echo "<p>Path: {$viewPath}</p>";
}

echo "<br><hr>";
echo "<h3>Solutions:</h3>";

if (!$controllerExists) {
    echo "<p><strong>1. Create the controller file</strong></p>";
    echo "<p>Create file: <code>app/Http/Controllers/Individual/LivestockController.php</code></p>";
}

echo "<p><strong>2. Clear all caches</strong></p>";
echo "<a href='/clear-all-caches.php' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Clear Caches</a>";

echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";