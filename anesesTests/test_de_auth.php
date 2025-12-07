<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DEController;
use Illuminate\Http\Request;

echo "Testing DE Dashboard with Authentication...\n\n";

// Find the DE user
$deUser = User::whereHas('role', function($query) {
    $query->where('name', 'Directeur des etudes');
})->first();

if (!$deUser) {
    echo "No DE user found!\n";
    exit(1);
}

echo "Found DE user: {$deUser->nom} {$deUser->prenom} (ID: {$deUser->id})\n";

// Manually authenticate the user (simulate login)
Auth::login($deUser);

echo "User authenticated: " . (Auth::check() ? 'Yes' : 'No') . "\n";
echo "Current user: " . (Auth::user() ? Auth::user()->id : 'None') . "\n\n";

try {
    $controller = new DEController();

    // Test dashboard method with authentication
    echo "Testing dashboard with authentication...\n";
    $request = new Request(['grade' => 'all']);
    $response = $controller->dashboard($request);

    if ($response instanceof \Illuminate\View\View) {
        echo "✓ Dashboard loads successfully\n";
        $data = $response->getData();

        echo "Dashboard data:\n";
        foreach ($data['stats'] as $key => $value) {
            echo "  $key: " . (is_array($value) ? count($value) . " items" : $value) . "\n";
        }
    }

    echo "\nTesting AJAX endpoint with authentication...\n";
    $ajaxResponse = $controller->getStatisticsData(new Request(['grade' => 'all']));

    if ($ajaxResponse instanceof \Illuminate\Http\JsonResponse) {
        echo "✓ AJAX endpoint works with authentication\n";
        $ajaxData = $ajaxResponse->getData(true);
        echo "AJAX response structure: " . implode(', ', array_keys($ajaxData)) . "\n";
    }

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nAuthenticated dashboard test completed!\n";
