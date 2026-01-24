<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { color: #10b981; font-weight: bold; }
    .error { color: #ef4444; font-weight: bold; }
    .warning { color: #f59e0b; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    th { background: #11455b; color: white; }
    h2 { color: #11455b; border-bottom: 3px solid #2fcb6e; padding-bottom: 10px; }
    h3 { color: #11455b; margin-top: 20px; }
    .fix-button { background: #2fcb6e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
</style>";

echo "<h1 style='color: #11455b;'>🔍 FarmVax Diagnostic Tool - Livestock & Service Requests</h1>";
echo "<p style='color: #666;'>Checking all components for Farmer Livestock and Service Request features...</p>";

$errors = [];
$warnings = [];
$fixes = [];

// ==================== LIVESTOCK DIAGNOSTICS ====================
echo "<div class='section'>";
echo "<h2>1️⃣ LIVESTOCK SYSTEM DIAGNOSTICS</h2>";

// Check Routes
echo "<h3>Routes Check</h3>";
$routes = Route::getRoutes();
$livestockRoutes = [];
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'farmer/livestock')) {
        $livestockRoutes[] = [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'methods' => implode(', ', $route->methods()),
        ];
    }
}

if (count($livestockRoutes) > 0) {
    echo "<p class='success'>✅ Found " . count($livestockRoutes) . " livestock routes</p>";
    echo "<table><tr><th>URI</th><th>Name</th><th>Methods</th></tr>";
    foreach ($livestockRoutes as $r) {
        echo "<tr><td>{$r['uri']}</td><td>{$r['name']}</td><td>{$r['methods']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ No livestock routes found!</p>";
    $errors[] = "Livestock routes missing";
}

// Check Controller
echo "<h3>Controller Check</h3>";
$controllerPath = base_path('app/Http/Controllers/Farmer/LivestockController.php');
if (file_exists($controllerPath)) {
    echo "<p class='success'>✅ LivestockController exists</p>";
    
    // Check if store method exists
    $content = file_get_contents($controllerPath);
    if (str_contains($content, 'function store')) {
        echo "<p class='success'>✅ store() method exists</p>";
    } else {
        echo "<p class='error'>❌ store() method missing</p>";
        $errors[] = "LivestockController missing store method";
    }
} else {
    echo "<p class='error'>❌ LivestockController not found</p>";
    $errors[] = "LivestockController file missing";
}

// Check Database Table
echo "<h3>Database Table Check</h3>";
if (Schema::hasTable('livestock')) {
    echo "<p class='success'>✅ livestock table exists</p>";
    
    $columns = DB::select("SHOW COLUMNS FROM livestock");
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = ['id', 'user_id', 'livestock_type', 'breed', 'tag_number', 'gender', 'health_status', 'created_at', 'updated_at'];
    $missingColumns = array_diff($requiredColumns, $columnNames);
    
    if (empty($missingColumns)) {
        echo "<p class='success'>✅ All required columns present</p>";
    } else {
        echo "<p class='error'>❌ Missing columns: " . implode(', ', $missingColumns) . "</p>";
        $errors[] = "Livestock table missing columns";
    }
    
    echo "<details><summary>Show all columns (" . count($columns) . ")</summary>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>" . ($col->Default ?? 'NULL') . "</td></tr>";
    }
    echo "</table></details>";
} else {
    echo "<p class='error'>❌ livestock table does not exist!</p>";
    $errors[] = "Livestock table missing";
}

// Check Model
echo "<h3>Model Check</h3>";
$modelPath = base_path('app/Models/Livestock.php');
if (file_exists($modelPath)) {
    echo "<p class='success'>✅ Livestock model exists</p>";
    
    $modelContent = file_get_contents($modelPath);
    if (str_contains($modelContent, '$fillable')) {
        echo "<p class='success'>✅ \$fillable property exists</p>";
    } else {
        echo "<p class='warning'>⚠️ \$fillable property might be missing</p>";
        $warnings[] = "Livestock model may need \$fillable";
    }
} else {
    echo "<p class='error'>❌ Livestock model not found</p>";
    $errors[] = "Livestock model missing";
}

// Check View
echo "<h3>View Check</h3>";
$viewPath = base_path('resources/views/farmer/livestock/create.blade.php');
if (file_exists($viewPath)) {
    echo "<p class='success'>✅ Create view exists</p>";
    
    $viewContent = file_get_contents($viewPath);
    if (str_contains($viewContent, 'route(\'farmer.livestock.store\')') || str_contains($viewContent, "route('farmer.livestock.store')")) {
        echo "<p class='success'>✅ Form action points to correct route</p>";
    } else {
        echo "<p class='warning'>⚠️ Form action might be incorrect</p>";
        $warnings[] = "Livestock form action needs verification";
    }
} else {
    echo "<p class='error'>❌ Create view not found</p>";
    $errors[] = "Livestock create view missing";
}

echo "</div>";

// ==================== SERVICE REQUESTS DIAGNOSTICS ====================
echo "<div class='section'>";
echo "<h2>2️⃣ SERVICE REQUEST SYSTEM DIAGNOSTICS</h2>";

// Check Routes
echo "<h3>Routes Check</h3>";
$serviceRoutes = [];
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'farmer/service-request')) {
        $serviceRoutes[] = [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'methods' => implode(', ', $route->methods()),
        ];
    }
}

if (count($serviceRoutes) > 0) {
    echo "<p class='success'>✅ Found " . count($serviceRoutes) . " service request routes</p>";
    echo "<table><tr><th>URI</th><th>Name</th><th>Methods</th></tr>";
    foreach ($serviceRoutes as $r) {
        echo "<tr><td>{$r['uri']}</td><td>{$r['name']}</td><td>{$r['methods']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ No service request routes found!</p>";
    $errors[] = "Service request routes missing";
}

// Check Controller
echo "<h3>Controller Check</h3>";
$srControllerPath = base_path('app/Http/Controllers/Farmer/ServiceRequestController.php');
if (file_exists($srControllerPath)) {
    echo "<p class='success'>✅ ServiceRequestController exists</p>";
    
    $srContent = file_get_contents($srControllerPath);
    if (str_contains($srContent, 'function store')) {
        echo "<p class='success'>✅ store() method exists</p>";
    } else {
        echo "<p class='error'>❌ store() method missing</p>";
        $errors[] = "ServiceRequestController missing store method";
    }
} else {
    echo "<p class='error'>❌ ServiceRequestController not found</p>";
    $errors[] = "ServiceRequestController file missing";
}

// Check Database Table
echo "<h3>Database Table Check</h3>";
if (Schema::hasTable('service_requests')) {
    echo "<p class='success'>✅ service_requests table exists</p>";
    
    $srColumns = DB::select("SHOW COLUMNS FROM service_requests");
    $srColumnNames = array_column($srColumns, 'Field');
    
    $requiredSRColumns = ['id', 'user_id', 'service_type', 'description', 'status', 'created_at', 'updated_at'];
    $missingSRColumns = array_diff($requiredSRColumns, $srColumnNames);
    
    if (empty($missingSRColumns)) {
        echo "<p class='success'>✅ All required columns present</p>";
    } else {
        echo "<p class='error'>❌ Missing columns: " . implode(', ', $missingSRColumns) . "</p>";
        $errors[] = "Service requests table missing columns";
    }
    
    echo "<details><summary>Show all columns (" . count($srColumns) . ")</summary>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($srColumns as $col) {
        echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>" . ($col->Default ?? 'NULL') . "</td></tr>";
    }
    echo "</table></details>";
} else {
    echo "<p class='error'>❌ service_requests table does not exist!</p>";
    $errors[] = "Service requests table missing";
}

// Check Model
echo "<h3>Model Check</h3>";
$srModelPath = base_path('app/Models/ServiceRequest.php');
if (file_exists($srModelPath)) {
    echo "<p class='success'>✅ ServiceRequest model exists</p>";
} else {
    echo "<p class='error'>❌ ServiceRequest model not found</p>";
    $errors[] = "ServiceRequest model missing";
}

echo "</div>";

// ==================== SUMMARY ====================
echo "<div class='section'>";
echo "<h2>📊 DIAGNOSTIC SUMMARY</h2>";

if (count($errors) === 0 && count($warnings) === 0) {
    echo "<p class='success' style='font-size: 18px;'>✅ ALL CHECKS PASSED! Systems are configured correctly.</p>";
    echo "<p>If you're still experiencing issues, the problem might be with validation or business logic.</p>";
} else {
    if (count($errors) > 0) {
        echo "<h3 style='color: #ef4444;'>❌ Critical Errors Found (" . count($errors) . ")</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color: #ef4444;'>{$error}</li>";
        }
        echo "</ul>";
    }
    
    if (count($warnings) > 0) {
        echo "<h3 style='color: #f59e0b;'>⚠️ Warnings (" . count($warnings) . ")</h3>";
        echo "<ul>";
        foreach ($warnings as $warning) {
            echo "<li style='color: #f59e0b;'>{$warning}</li>";
        }
        echo "</ul>";
    }
}

echo "</div>";

// ==================== TEST DATA ====================
echo "<div class='section'>";
echo "<h2>🧪 TEST DATA</h2>";

echo "<h3>Recent Livestock Entries</h3>";
try {
    $recentLivestock = DB::table('livestock')->orderBy('created_at', 'desc')->limit(5)->get();
    if ($recentLivestock->count() > 0) {
        echo "<table><tr><th>ID</th><th>User ID</th><th>Type</th><th>Tag Number</th><th>Created</th></tr>";
        foreach ($recentLivestock as $item) {
            echo "<tr><td>{$item->id}</td><td>{$item->user_id}</td><td>{$item->livestock_type}</td><td>{$item->tag_number}</td><td>{$item->created_at}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠️ No livestock entries found</p>";
    }
} catch (\Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "<h3>Recent Service Requests</h3>";
try {
    $recentSR = DB::table('service_requests')->orderBy('created_at', 'desc')->limit(5)->get();
    if ($recentSR->count() > 0) {
        echo "<table><tr><th>ID</th><th>User ID</th><th>Service Type</th><th>Status</th><th>Created</th></tr>";
        foreach ($recentSR as $item) {
            echo "<tr><td>{$item->id}</td><td>{$item->user_id}</td><td>{$item->service_type}</td><td>{$item->status}</td><td>{$item->created_at}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠️ No service requests found</p>";
    }
} catch (\Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";

echo "<div class='section'>";
echo "<h2>🔧 NEXT STEPS</h2>";
echo "<p>Based on the diagnostics above, I will now create fix files for any issues found.</p>";
echo "<a href='/clear-all-caches.php' class='fix-button'>Clear All Caches</a>";
echo "<a href='/farmer/livestock/create' class='fix-button'>Test Livestock Form</a>";
echo "<a href='/farmer/service-requests/create' class='fix-button'>Test Service Request Form</a>";
echo "</div>";

echo "<br><br><p style='color: red; font-weight: bold;'>⚠️ DELETE THIS FILE AFTER DIAGNOSIS!</p>";