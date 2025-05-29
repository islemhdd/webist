<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing DE statistics AJAX endpoint...\n\n";

// Test the AJAX endpoint directly
$url = 'http://127.0.0.1:8000/de/statistics-data';

echo "Testing URL: $url\n";

// Test with different parameters
$testCases = [
    ['grade' => 'all'],
    ['grade' => '1'],
    ['grade' => '2'],
    ['matricule' => '2022002'],
    ['grade' => '1', 'matricule' => '2022'],
];

foreach ($testCases as $index => $params) {
    echo "\n" . str_repeat('-', 40) . "\n";
    echo "Test Case " . ($index + 1) . ": " . http_build_query($params) . "\n";

    $testUrl = $url . '?' . http_build_query($params);

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Requested-With: XMLHttpRequest'
            ],
            'timeout' => 10
        ]
    ]);

    try {
        $response = file_get_contents($testUrl, false, $context);

        if ($response === false) {
            echo "✗ Request failed\n";
            continue;
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "✗ Invalid JSON response\n";
            echo "Response: " . substr($response, 0, 200) . "...\n";
            continue;
        }

        echo "✓ Request successful\n";
        echo "Response data:\n";
        print_r($data);

    } catch (Exception $e) {
        echo "✗ Exception: " . $e->getMessage() . "\n";
    }
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "Test completed!\n";
