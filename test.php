<?php
echo "PHP is working\n";
echo "Current directory: " . getcwd() . "\n";
echo "Laravel bootstrap exists: " . (file_exists('bootstrap/app.php') ? 'Yes' : 'No') . "\n";

try {
    require_once 'vendor/autoload.php';
    echo "Composer autoload: OK\n";
    
    $app = require_once 'bootstrap/app.php';
    echo "Laravel bootstrap: OK\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
?>
