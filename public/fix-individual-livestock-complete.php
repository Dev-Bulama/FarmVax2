<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Complete Individual Livestock Fix</h2>";
echo "<hr>";

// Step 1: Check controller exists and has correct content
echo "<h3>Step 1: Checking Controller...</h3>";
$controllerPath = base_path('app/Http/Controllers/Individual/LivestockController.php');

if (!file_exists($controllerPath)) {
    echo "<p style='color: red;'>❌ Controller missing! Creating it...</p>";
    
    $controllerContent = <<<'PHP'
<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivestockController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $livestock = Livestock::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $stats = [
            'total' => Livestock::where('user_id', $user->id)->count(),
            'healthy' => Livestock::where('user_id', $user->id)->where('health_status', 'healthy')->count(),
            'sick' => Livestock::where('user_id', $user->id)->whereIn('health_status', ['sick', 'under_treatment'])->count(),
            'vaccinated' => Livestock::where('user_id', $user->id)->where('is_vaccinated', true)->count(),
        ];
        
        return view('individual.livestock.index', compact('livestock', 'stats'));
    }

    public function show($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);
        return view('individual.livestock.show', compact('livestock'));
    }

    public function edit($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);
        $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())->get();
        return view('individual.livestock.edit', compact('livestock', 'herdGroups'));
    }

    public function create()
    {
        $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())->get();
        return view('individual.livestock.create', compact('herdGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'livestock_type' => 'required|string',
            'breed' => 'nullable|string',
            'tag_number' => 'nullable|string',
            'name' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'health_status' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $livestock = Livestock::create($validated);

        return redirect()->route('individual.livestock.show', $livestock->id)
            ->with('success', 'Livestock added successfully!');
    }

    public function update(Request $request, $id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);
        $livestock->update($request->all());

        return redirect()->route('individual.livestock.show', $livestock->id)
            ->with('success', 'Livestock updated successfully!');
    }

    public function destroy($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);
        $livestock->delete();

        return redirect()->route('individual.livestock.index')
            ->with('success', 'Livestock deleted successfully!');
    }
}
PHP;

    // Create directory if doesn't exist
    $dir = dirname($controllerPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($controllerPath, $controllerContent);
    echo "<p style='color: green;'>✅ Controller created!</p>";
} else {
    echo "<p style='color: green;'>✅ Controller exists</p>";
    
    // Check if it has the index method with $stats
    $content = file_get_contents($controllerPath);
    if (strpos($content, '$stats') === false) {
        echo "<p style='color: orange;'>⚠️ Controller missing \$stats! Updating...</p>";
        file_put_contents($controllerPath, $controllerContent);
        echo "<p style='color: green;'>✅ Controller updated!</p>";
    } else {
        echo "<p style='color: green;'>✅ Controller has \$stats variable</p>";
    }
}

// Step 2: Clear all caches
echo "<br><h3>Step 2: Clearing Caches...</h3>";
try {
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "<p style='color: green;'>✅ Route cache cleared</p>";
    
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "<p style='color: green;'>✅ Application cache cleared</p>";
    
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "<p style='color: green;'>✅ View cache cleared</p>";
    
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "<p style='color: green;'>✅ Config cache cleared</p>";
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error clearing cache: " . $e->getMessage() . "</p>";
}

// Step 3: Check routes again
echo "<br><h3>Step 3: Verifying Routes...</h3>";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $indexRoute = null;
    
    foreach ($routes as $route) {
        if ($route->getName() == 'individual.livestock.index') {
            $indexRoute = $route;
            break;
        }
    }
    
    if ($indexRoute) {
        $action = $indexRoute->getActionName();
        if (strpos($action, 'Individual\LivestockController') !== false) {
            echo "<p style='color: green;'>✅ Routes are using Individual\LivestockController</p>";
            echo "<p>Action: {$action}</p>";
        } else {
            echo "<p style='color: red;'>❌ Routes still using wrong controller: {$action}</p>";
            echo "<p><strong>You need to manually update routes/web.php</strong></p>";
            echo "<p>Change:</p>";
            echo "<pre>Route::resource('livestock', \\App\\Http\\Controllers\\Farmer\\LivestockController::class);</pre>";
            echo "<p>To:</p>";
            echo "<pre>Route::resource('livestock', \\App\\Http\\Controllers\\Individual\\LivestockController::class);</pre>";
        }
    } else {
        echo "<p style='color: red;'>❌ individual.livestock.index route not found!</p>";
    }
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><hr>";
echo "<h3 style='color: green;'>✅ FIX COMPLETE!</h3>";
echo "<br><a href='/individual/livestock' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px;'>Test Livestock Page</a>";
echo "<br><br><strong style='color: red;'>DELETE THIS FILE AFTER SUCCESS!</strong>";