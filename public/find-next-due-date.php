<?php
echo "<h2>Finding all next_due_date references</h2>";
echo "<hr>";

$directories = [
    __DIR__ . '/../app/Http/Controllers',
    __DIR__ . '/../resources/views',
];

$foundFiles = [];

function searchDirectory($dir, &$foundFiles) {
    if (!is_dir($dir)) return;
    
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            searchDirectory($path, $foundFiles);
        } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            if (stripos($content, 'next_due_date') !== false) {
                // Count occurrences
                preg_match_all('/next_due_date/i', $content, $matches);
                $count = count($matches[0]);
                
                // Get line numbers
                $lines = explode("\n", $content);
                $lineNumbers = [];
                foreach ($lines as $num => $line) {
                    if (stripos($line, 'next_due_date') !== false) {
                        $lineNumbers[] = ($num + 1) . ': ' . trim($line);
                    }
                }
                
                $foundFiles[] = [
                    'file' => str_replace(__DIR__ . '/../', '', $path),
                    'count' => $count,
                    'lines' => $lineNumbers
                ];
            }
        }
    }
}

foreach ($directories as $dir) {
    searchDirectory($dir, $foundFiles);
}

if (empty($foundFiles)) {
    echo "<p style='color: green;'>✅ No files found with next_due_date!</p>";
} else {
    echo "<p style='color: red;'>❌ Found " . count($foundFiles) . " files with next_due_date:</p>";
    echo "<ol>";
    foreach ($foundFiles as $file) {
        echo "<li>";
        echo "<strong>" . $file['file'] . "</strong> (" . $file['count'] . " occurrences)<br>";
        echo "<ul style='font-size: 12px; font-family: monospace; background: #f5f5f5; padding: 10px;'>";
        foreach ($file['lines'] as $line) {
            echo "<li>" . htmlspecialchars($line) . "</li>";
        }
        echo "</ul>";
        echo "</li>";
    }
    echo "</ol>";
}

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";