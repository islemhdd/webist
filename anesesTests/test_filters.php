<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\ExpulsionController;
use App\Http\Controllers\DEController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

echo "=== Test des filtres DE ===\n\n";

// Test 1: Vérifier le contrôleur des expulsions avec différents filtres
echo "1. Test du contrôleur ExpulsionController:\n";

try {
    $request = new Request([
        'grade' => 'all',
        'matricule' => ''
    ]);
    
    $controller = new ExpulsionController();
    
    // Pour tester, nous devons simuler une requête HTTP
    $result = $controller->index($request);
    
    echo "✓ Test basique réussi\n";
    
    // Test avec grade spécifique
    $request = new Request([
        'grade' => '1',
        'matricule' => ''
    ]);
    
    $result = $controller->index($request);
    echo "✓ Test avec grade=1 réussi\n";
    
    // Test avec matricule
    $request = new Request([
        'grade' => 'all',
        'matricule' => 'test'
    ]);
    
    $result = $controller->index($request);
    echo "✓ Test avec recherche matricule réussi\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur dans ExpulsionController: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n2. Test du contrôleur DEController (infirmerie):\n";

try {
    $request = new Request([
        'grade' => 'all',
        'matricule' => '',
        'status' => ''
    ]);
    
    $controller = new DEController();
    
    $result = $controller->infirmerie($request);
    echo "✓ Test infirmerie basique réussi\n";
    
    // Test avec grade spécifique
    $request = new Request([
        'grade' => '2',
        'matricule' => '',
        'status' => ''
    ]);
    
    $result = $controller->infirmerie($request);
    echo "✓ Test infirmerie avec grade=2 réussi\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur dans DEController: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Test 3: Vérifier les données de base
echo "\n3. Vérification des données:\n";

try {
    // Compter les expulsions d'aujourd'hui
    $today = now()->toDateString();
    $expulsions = \App\Models\Expulsion::whereDate('date_expulsion', $today)->count();
    echo "Expulsions d'aujourd'hui: $expulsions\n";
    
    // Compter les patients d'aujourd'hui
    $patients = \App\Models\Patient::whereDate('created_at', $today)->count();
    echo "Patients d'aujourd'hui: $patients\n";
    
    // Compter les étudiants par grade
    for ($i = 1; $i <= 3; $i++) {
        $studentsGrade = \App\Models\Student::where('grade', $i)->count();
        echo "Étudiants grade $i: $studentsGrade\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la vérification des données: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
