<?php
// Load Laravel's autoloader first
$laravelAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($laravelAutoload)) {
    require_once $laravelAutoload;
}

// Load manual PhpSpreadsheet autoloader
$manualAutoload = __DIR__ . '/../vendor/phpspreadsheet-autoload.php';
if (file_exists($manualAutoload)) {
    require_once $manualAutoload;
}