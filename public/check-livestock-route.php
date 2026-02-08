<?php
/**
 * Livestock Route Diagnostic
 * Access via: https://farmvax.com/check-livestock-route.php
 * DELETE THIS FILE AFTER DEBUGGING
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Livestock Route Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #ce9178; }
        .info { color: #569cd6; }
        pre { background: #252526; padding: 15px; border-left: 3px solid #007acc; overflow-x: auto; }
        h1 { color: #4ec9b0; }
        h2 { color: #569cd6; border-bottom: 1px solid #404040; padding-bottom: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Livestock Route Diagnostic</h1>

    <?php
    $projectRoot = __DIR__ . '/..';

    // Test 1: Check if controller file exists
    echo "<h2>1. Controller File Check</h2>";
    $controllerPath = $projectRoot . '/app/Http/Controllers/Admin/LivestockController.php';
    if (file_exists($controllerPath)) {
        echo "<span class='success'>✅ LivestockController exists at:</span><br>";
        echo "<pre>$controllerPath</pre>";
        echo "<span class='info'>File size: " . filesize($controllerPath) . " bytes</span><br>";
        echo "<span class='info'>Last modified: " . date('Y-m-d H:i:s', filemtime($controllerPath)) . "</span><br>";
    } else {
        echo "<span class='error'>❌ LivestockController NOT FOUND</span><br>";
        echo "<pre>Expected at: $controllerPath</pre>";
    }

    // Test 2: Check if views exist
    echo "<h2>2. View Files Check</h2>";
    $viewsDir = $projectRoot . '/resources/views/admin/livestock';
    if (is_dir($viewsDir)) {
        echo "<span class='success'>✅ Livestock views directory exists</span><br>";
        $files = scandir($viewsDir);
        echo "<pre>";
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $viewsDir . '/' . $file;
                $size = filesize($filePath);
                $modified = date('Y-m-d H:i:s', filemtime($filePath));
                echo "$file ($size bytes, modified: $modified)\n";
            }
        }
        echo "</pre>";
    } else {
        echo "<span class='error'>❌ Livestock views directory NOT FOUND</span><br>";
    }

    // Test 3: Check routes file for livestock routes
    echo "<h2>3. Routes File Check</h2>";
    $routesFile = $projectRoot . '/routes/web.php';
    if (file_exists($routesFile)) {
        $routesContent = file_get_contents($routesFile);

        // Check for livestock routes
        if (strpos($routesContent, 'App\Http\Controllers\Admin\LivestockController') !== false) {
            echo "<span class='success'>✅ Livestock routes FOUND in web.php</span><br>";

            // Extract the livestock routes section
            preg_match('/\/\/ Livestock Management Routes.*?});/s', $routesContent, $matches);
            if (!empty($matches[0])) {
                echo "<pre>" . htmlspecialchars($matches[0]) . "</pre>";
            }
        } else {
            echo "<span class='error'>❌ Livestock routes NOT FOUND in web.php</span><br>";
        }

        // Check if routes are inside admin group
        $adminGroupPattern = '/Route::middleware.*?->prefix\([\'"]admin[\'"]\).*?->group\(function.*?\}\);/s';
        if (preg_match($adminGroupPattern, $routesContent, $adminMatch)) {
            if (strpos($adminMatch[0], 'LivestockController') !== false) {
                echo "<span class='success'>✅ Livestock routes are INSIDE admin middleware group</span><br>";
            } else {
                echo "<span class='error'>❌ Livestock routes are NOT inside admin middleware group</span><br>";
            }
        }
    }

    // Test 4: Check if Laravel can be loaded
    echo "<h2>4. Laravel Bootstrap Check</h2>";
    $autoloadPath = $projectRoot . '/vendor/autoload.php';
    if (file_exists($autoloadPath)) {
        echo "<span class='success'>✅ Composer autoload found</span><br>";

        // Try to load Laravel
        try {
            require $autoloadPath;
            $app = require_once $projectRoot . '/bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

            echo "<span class='success'>✅ Laravel bootstrapped successfully</span><br>";

            // Try to check routes
            $router = $app->make('router');
            $routes = $router->getRoutes();

            $livestockRoutes = [];
            foreach ($routes as $route) {
                $name = $route->getName();
                if ($name && strpos($name, 'admin.livestock') !== false) {
                    $livestockRoutes[] = [
                        'name' => $name,
                        'uri' => $route->uri(),
                        'methods' => implode('|', $route->methods()),
                        'action' => $route->getActionName(),
                    ];
                }
            }

            if (!empty($livestockRoutes)) {
                echo "<span class='success'>✅ Found " . count($livestockRoutes) . " admin livestock routes</span><br>";
                echo "<pre>";
                foreach ($livestockRoutes as $route) {
                    echo sprintf(
                        "%-40s %-10s %-30s\n",
                        $route['name'],
                        $route['methods'],
                        $route['uri']
                    );
                }
                echo "</pre>";
            } else {
                echo "<span class='error'>❌ NO admin livestock routes found in Laravel router</span><br>";
                echo "<span class='warning'>⚠️ This means routes are not being registered properly</span><br>";
            }

        } catch (Exception $e) {
            echo "<span class='error'>❌ Failed to bootstrap Laravel:</span><br>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    } else {
        echo "<span class='error'>❌ Composer autoload not found</span><br>";
        echo "<span class='warning'>Run: composer install</span><br>";
    }

    // Test 5: Check Livestock model
    echo "<h2>5. Livestock Model Check</h2>";
    $modelPath = $projectRoot . '/app/Models/Livestock.php';
    if (file_exists($modelPath)) {
        echo "<span class='success'>✅ Livestock model exists</span><br>";
        $modelContent = file_get_contents($modelPath);

        // Check for owner relationship
        if (strpos($modelContent, 'function owner()') !== false) {
            echo "<span class='success'>✅ owner() relationship found</span><br>";
        } else {
            echo "<span class='warning'>⚠️ owner() relationship not found</span><br>";
        }

        // Check for farmRecord relationship
        if (strpos($modelContent, 'function farmRecord()') !== false) {
            echo "<span class='success'>✅ farmRecord() relationship found</span><br>";
        } else {
            echo "<span class='warning'>⚠️ farmRecord() relationship not found</span><br>";
        }
    } else {
        echo "<span class='error'>❌ Livestock model NOT FOUND</span><br>";
    }

    // Test 6: Sidebar check
    echo "<h2>6. Sidebar File Check</h2>";
    $sidebarPath = $projectRoot . '/resources/views/admin/partials/sidebar.blade.php';
    if (file_exists($sidebarPath)) {
        $sidebarContent = file_get_contents($sidebarPath);
        if (strpos($sidebarContent, "route('admin.livestock.index')") !== false) {
            echo "<span class='success'>✅ Livestock link FOUND in sidebar</span><br>";
        } else {
            echo "<span class='error'>❌ Livestock link NOT FOUND in sidebar</span><br>";
        }
    }

    // Summary
    echo "<h2>📋 Summary & Next Steps</h2>";
    echo "<div style='background: #252526; padding: 15px; border-left: 3px solid #ce9178;'>";
    echo "<p><strong>If you see errors above, run these commands on your server:</strong></p>";
    echo "<pre>";
    echo "# Navigate to project\n";
    echo "cd /path/to/farmvax\n\n";
    echo "# Regenerate autoload\n";
    echo "composer dump-autoload\n\n";
    echo "# Clear and rebuild route cache\n";
    echo "php artisan route:clear\n";
    echo "php artisan route:cache\n\n";
    echo "# Clear other caches\n";
    echo "php artisan config:clear\n";
    echo "php artisan cache:clear\n";
    echo "php artisan view:clear\n\n";
    echo "# Check routes are registered\n";
    echo "php artisan route:list | grep livestock\n";
    echo "</pre>";
    echo "</div>";

    echo "<hr>";
    echo "<p style='color: #ce9178;'><strong>⚠️ DELETE THIS FILE AFTER DEBUGGING:</strong> <code>rm public/check-livestock-route.php</code></p>";
    ?>
</body>
</html>
