<?php
echo "<h2>Current PHP Upload Settings</h2>";

$uploadMaxFilesize = ini_get('upload_max_filesize');
$postMaxSize = ini_get('post_max_size');
$maxExecutionTime = ini_get('max_execution_time');
$maxInputTime = ini_get('max_input_time');
$memoryLimit = ini_get('memory_limit');

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Setting</th><th>Current Value</th><th>Recommended</th></tr>";
echo "<tr><td>upload_max_filesize</td><td><strong>$uploadMaxFilesize</strong></td><td>10M or higher</td></tr>";
echo "<tr><td>post_max_size</td><td><strong>$postMaxSize</strong></td><td>12M or higher</td></tr>";
echo "<tr><td>max_execution_time</td><td><strong>$maxExecutionTime</strong></td><td>120 seconds</td></tr>";
echo "<tr><td>max_input_time</td><td><strong>$maxInputTime</strong></td><td>120 seconds</td></tr>";
echo "<tr><td>memory_limit</td><td><strong>$memoryLimit</strong></td><td>256M</td></tr>";
echo "</table>";

echo "<br><h3>What to do:</h3>";
echo "<p>If the values are too low, you need to update them in your <strong>.htaccess</strong> file or contact your hosting provider.</p>";

echo "<br><strong style='color: red;'>DELETE THIS FILE!</strong>";