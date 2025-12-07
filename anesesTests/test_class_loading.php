<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing MedicalSpecialtyController class loading...\n";

try {
    $reflection = new ReflectionClass('App\Http\Controllers\MedicalSpecialtyController');
    echo "✅ Class loaded successfully\n";
    echo "Number of methods: " . count($reflection->getMethods()) . "\n";

    // Check for specific methods
    $methods = ['statistics', 'filterStatistics', 'appointmentsList'];
    foreach ($methods as $method) {
        if ($reflection->hasMethod($method)) {
            echo "✅ Method '$method' exists\n";
        } else {
            echo "❌ Method '$method' missing\n";
        }
    }

} catch (ParseError $e) {
    echo "❌ Parse Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
