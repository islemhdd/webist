<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Testing DE Dashboard Direct Access...\n\n";

use App\Http\Controllers\DEController;
use Illuminate\Http\Request;

try {
    $controller = new DEController();

    // Test with different parameters to see filtering behavior
    $testParams = [
        ['grade' => 'all'],
        ['grade' => '1'],
        ['grade' => '2'],
        ['matricule' => '2022002'],
        ['grade' => '1', 'matricule' => '2022']
    ];

    foreach ($testParams as $index => $params) {
        echo "Test " . ($index + 1) . ": " . http_build_query($params) . "\n";
        echo str_repeat('-', 40) . "\n";

        $request = new Request($params);

        // Test getStatisticsData method
        $response = $controller->getStatisticsData($request);

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);

            echo "Statistics Data:\n";
            echo "- Total Infirmary: " . $data['infirmary']['total'] . "\n";
            echo "- RHP Validated: " . $data['infirmary']['validated'] . "\n";
            echo "- RHP Pending: " . $data['infirmary']['non_validated'] . "\n";
            echo "- Total Expulsions: " . $data['total_expulsions'] . "\n";

            if (!empty($data['expulsions'])) {
                echo "- Expulsions by reason:\n";
                foreach ($data['expulsions'] as $reason => $count) {
                    echo "  * $reason: $count\n";
                }
            }
        } else {
            echo "Unexpected response type\n";
        }

        echo "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "Direct controller test completed!\n";
