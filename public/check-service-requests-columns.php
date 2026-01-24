<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Service Requests Table Structure</h2>";
echo "<hr>";

try {
    $columns = DB::select("SHOW COLUMNS FROM service_requests");
    
    echo "<h3>Columns in service_requests table:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Column Name</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>{$col->Field}</strong></td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>" . ($col->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Sample data
    echo "<hr>";
    echo "<h3>Sample Service Requests:</h3>";
    $requests = DB::table('service_requests')->limit(5)->get();
    
    if ($requests->count() > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Service Type</th><th>Status</th><th>Assigned To</th><th>Created</th></tr>";
        foreach ($requests as $req) {
            echo "<tr>";
            echo "<td>{$req->id}</td>";
            echo "<td>{$req->user_id}</td>";
            echo "<td>{$req->service_type}</td>";
            echo "<td>{$req->status}</td>";
            echo "<td>" . ($req->assigned_to ?? 'NULL') . "</td>";
            echo "<td>{$req->created_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No service requests found.";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";