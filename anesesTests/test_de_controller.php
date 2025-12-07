<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\DEController;
use Illuminate\Http\Request;

echo "Testing DEController methods...\n\n";

$controller = new DEController();

// Test 1: Dashboard method
echo "1. Testing dashboard method:\n";
try {
    $request = new Request(['grade' => 'all']);
    $response = $controller->dashboard($request);
    echo "✓ Dashboard method executed successfully\n";

    // Check if it returns a view
    if ($response instanceof \Illuminate\View\View) {
        echo "✓ Returns a view response\n";
        echo "View name: " . $response->getName() . "\n";

        // Get the data passed to the view
        $data = $response->getData();
        echo "Data keys: " . implode(', ', array_keys($data)) . "\n";

        if (isset($data['stats'])) {
            echo "Stats data:\n";
            print_r($data['stats']);
        }
    }
} catch (Exception $e) {
    echo "✗ Dashboard method failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n\n";

// Test 2: getStatisticsData method
echo "2. Testing getStatisticsData method:\n";
try {
    $request = new Request(['grade' => 'all', 'matricule' => '']);
    $response = $controller->getStatisticsData($request);
    echo "✓ getStatisticsData method executed successfully\n";

    // Check if it returns JSON
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        echo "✓ Returns a JSON response\n";
        $data = $response->getData(true);
        echo "JSON data structure:\n";
        print_r($data);
    }
} catch (Exception $e) {
    echo "✗ getStatisticsData method failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";
