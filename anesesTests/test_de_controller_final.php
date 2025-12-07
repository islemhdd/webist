<?php

// Test final du contrôleur DEController avec les nouvelles statistiques
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\DEController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

try {
    echo "=== Test final du contrôleur DEController ===\n\n";

    // Créer une instance du contrôleur
    $controller = new DEController();

    // Créer une requête mock
    $request = new Request();
    $request->merge(['grade' => 'all']);

    // Tester la méthode getStatisticsData
    echo "Test de getStatisticsData...\n";
    $response = $controller->getStatisticsData($request);
    $data = json_decode($response->getContent(), true);

    echo "Réponse JSON:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

    // Vérifications
    if (isset($data['infirmary']['total'])) {
        echo "✓ Total des patients de l'infirmerie: " . $data['infirmary']['total'] . "\n";
    } else {
        echo "✗ Donnée 'total' manquante\n";
    }

    if (isset($data['infirmary']['validated'])) {
        echo "✓ Patients validés RHP: " . $data['infirmary']['validated'] . "\n";
    } else {
        echo "✗ Donnée 'validated' manquante\n";
    }

    if (isset($data['infirmary']['non_validated'])) {
        echo "✓ Patients en attente RHP: " . $data['infirmary']['non_validated'] . "\n";
    } else {
        echo "✗ Donnée 'non_validated' manquante\n";
    }

    // Vérifier que le total inclut bien tous les patients (valider = 0, 1, 2)
    echo "\n=== Vérification de la logique ===\n";
    $today = now()->toDateString();

    $totalDB = DB::table('patients')
        ->whereIn('valider', [0, 1, 2])
        ->whereDate('created_at', '=', $today)
        ->count();

    echo "Total dans la BD (valider 0,1,2) aujourd'hui: $totalDB\n";
    echo "Total retourné par le contrôleur: " . ($data['infirmary']['total'] ?? 'N/A') . "\n";

    if ($totalDB == ($data['infirmary']['total'] ?? 0)) {
        echo "✓ Les totaux correspondent !\n";
    } else {
        echo "✗ Les totaux ne correspondent pas.\n";
    }

    echo "\n=== Test terminé avec succès ===\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
