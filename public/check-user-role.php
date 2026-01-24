<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Checking User Role & Routes</h2>";
echo "<hr>";

// Get authenticated user
$user = \Illuminate\Support\Facades\Auth::user();

if (!$user) {
    echo "<p style='color: red;'>❌ No user is currently logged in</p>";
    echo "<p>Please <a href='/login'>login</a> first, then run this script again.</p>";
} else {
    echo "<h3>Current User Info:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>ID</td><td>" . $user->id . "</td></tr>";
    echo "<tr><td>Name</td><td>" . $user->name . "</td></tr>";
    echo "<tr><td>Email</td><td>" . $user->email . "</td></tr>";
    echo "<tr><td>Role</td><td><strong style='color: blue; font-size: 18px;'>" . $user->role . "</strong></td></tr>";
    echo "</table>";
    
    echo "<br><h3>Route Access Check:</h3>";
    
    // Check what routes should work
    $expectedRoutes = [
        'farmer' => [
            '/farmer/dashboard' => 'Farmer Dashboard',
            '/farmer/profile' => 'Farmer Profile',
            '/farmer/farm-records' => 'Farm Records',
        ],
        'individual' => [
            '/individual/dashboard' => 'Individual Dashboard',
            '/individual/profile' => 'Individual Profile',
            '/individual/farm-records' => 'Farm Records',
        ],
    ];
    
    if (isset($expectedRoutes[$user->role])) {
        echo "<p style='color: green;'>✅ User role '<strong>{$user->role}</strong>' is valid</p>";
        echo "<p>These routes should work for you:</p>";
        echo "<ul>";
        foreach ($expectedRoutes[$user->role] as $route => $label) {
            echo "<li><a href='{$route}'>{$label}</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ User role '<strong>{$user->role}</strong>' is NOT configured in routes</p>";
        echo "<p>Available roles: " . implode(', ', array_keys($expectedRoutes)) . "</p>";
    }
    
    // Check route middleware
    echo "<br><h3>Checking Route Middleware:</h3>";
    try {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $farmerProfileRoute = null;
        
        foreach ($routes as $route) {
            if ($route->getName() == 'farmer.profile' || $route->uri() == 'farmer/profile') {
                $farmerProfileRoute = $route;
                break;
            }
        }
        
        if ($farmerProfileRoute) {
            echo "<p>Farmer Profile Route Found:</p>";
            echo "<ul>";
            echo "<li><strong>URI:</strong> " . $farmerProfileRoute->uri() . "</li>";
            echo "<li><strong>Name:</strong> " . $farmerProfileRoute->getName() . "</li>";
            echo "<li><strong>Middleware:</strong> " . implode(', ', $farmerProfileRoute->middleware()) . "</li>";
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>❌ Farmer profile route NOT found in routes</p>";
        }
        
    } catch (\Exception $e) {
        echo "<p style='color: red;'>Error checking routes: " . $e->getMessage() . "</p>";
    }
}

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";