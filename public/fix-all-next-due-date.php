<?php
echo "<h2>Fixing ALL next_due_date References</h2>";
echo "<hr>";

$basePath = dirname(__DIR__);

// Step 1: Delete all copy files
echo "<h3>Step 1: Deleting duplicate copy files...</h3>";
$copyFiles = [
    $basePath . '/app/Http/Controllers/Farmer/DashboardController copy 2.php',
    $basePath . '/resources/views/farmer/dashboard.blade copy 2.php',
    $basePath . '/resources/views/farmer/dashboard.blade copy.php',
    $basePath . '/resources/views/individual/vaccinations/index.blade copy.php',
    $basePath . '/resources/views/individual/vaccinations/index.blade(1).php',
];

foreach ($copyFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✅ Deleted: " . basename($file) . "<br>";
    } else {
        echo "⚠️ Not found: " . basename($file) . "<br>";
    }
}

// Step 2: Fix Farmer DashboardController.php
echo "<br><h3>Step 2: Fixing DashboardController.php...</h3>";
$dashboardController = $basePath . '/app/Http/Controllers/Farmer/DashboardController.php';
if (file_exists($dashboardController)) {
    $content = file_get_contents($dashboardController);
    $originalContent = $content;
    
    // Replace the problematic query
    $content = str_replace(
        "->where('next_due_date', '>=', Carbon::now())",
        "->where(function(\$query) {
            \$query->where('next_booster_due_date', '>=', Carbon::now())
                  ->orWhere('next_dose_due_date', '>=', Carbon::now());
        })",
        $content
    );
    
    $content = str_replace(
        "->where('next_due_date', '<=', Carbon::now()->addDays(30))",
        "->where(function(\$query) {
            \$query->where('next_booster_due_date', '<=', Carbon::now()->addDays(30))
                  ->orWhere('next_dose_due_date', '<=', Carbon::now()->addDays(30));
        })",
        $content
    );
    
    $content = str_replace(
        "->where('next_due_date', '<', Carbon::now())",
        "->where(function(\$query) {
            \$query->where('next_booster_due_date', '<', Carbon::now())
                  ->orWhere('next_dose_due_date', '<', Carbon::now());
        })",
        $content
    );
    
    $content = str_replace(
        "->orderBy('next_due_date', 'asc')",
        "->orderBy('next_booster_due_date', 'asc')",
        $content
    );
    
    if ($content !== $originalContent) {
        file_put_contents($dashboardController, $content);
        echo "✅ Fixed: DashboardController.php<br>";
    } else {
        echo "⚠️ No changes needed in DashboardController.php<br>";
    }
} else {
    echo "❌ File not found: DashboardController.php<br>";
}

// Step 3: Fix farmer/dashboard.blade.php
echo "<br><h3>Step 3: Fixing farmer/dashboard.blade.php...</h3>";
$farmerDashboard = $basePath . '/resources/views/farmer/dashboard.blade.php';
if (file_exists($farmerDashboard)) {
    $content = file_get_contents($farmerDashboard);
    $originalContent = $content;
    
    // Replace all next_due_date references
    $content = str_replace("'next_due_date'", "'next_booster_due_date'", $content);
    $content = str_replace('$vaccination->next_due_date', '($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date)', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($farmerDashboard, $content);
        echo "✅ Fixed: farmer/dashboard.blade.php<br>";
    } else {
        echo "⚠️ No changes needed in farmer/dashboard.blade.php<br>";
    }
} else {
    echo "❌ File not found: farmer/dashboard.blade.php<br>";
}

// Step 4: Fix individual/vaccinations/index.blade.php
echo "<br><h3>Step 4: Fixing individual/vaccinations/index.blade.php...</h3>";
$vaccinationIndex = $basePath . '/resources/views/individual/vaccinations/index.blade.php';
if (file_exists($vaccinationIndex)) {
    $content = file_get_contents($vaccinationIndex);
    $originalContent = $content;
    
    // Replace all next_due_date references
    $content = str_replace("'next_due_date'", "'next_booster_due_date'", $content);
    $content = str_replace('$vaccination->next_due_date', '($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date)', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($vaccinationIndex, $content);
        echo "✅ Fixed: individual/vaccinations/index.blade.php<br>";
    } else {
        echo "⚠️ No changes needed in individual/vaccinations/index.blade.php<br>";
    }
} else {
    echo "❌ File not found: individual/vaccinations/index.blade.php<br>";
}

echo "<br><hr>";
echo "<h3 style='color: green;'>✅ FIX COMPLETE!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Clear cache by visiting: <a href='/clear-all-caches.php'>Clear All Caches</a></li>";
echo "<li>Test Farmer Dashboard: <a href='/farmer/dashboard'>Farmer Dashboard</a></li>";
echo "<li>Test Vaccinations: <a href='/individual/vaccinations'>Vaccinations Page</a></li>";
echo "</ol>";
echo "<br><strong style='color: red; font-size: 20px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</strong>";