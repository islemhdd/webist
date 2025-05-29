<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\ExpulsionController;
use App\Http\Controllers\DEController;
use Illuminate\Http\Request;

echo "🔧 VALIDATION FINALE DES FILTRES DE - Direction d'Études\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Test des différents scénarios de filtrage
$testScenarios = [
    ['name' => 'Toutes les expulsions', 'grade' => 'all', 'matricule' => ''],
    ['name' => 'Expulsions 1ère année', 'grade' => '1', 'matricule' => ''],
    ['name' => 'Expulsions 2ème année', 'grade' => '2', 'matricule' => ''],
    ['name' => 'Expulsions 3ème année', 'grade' => '3', 'matricule' => ''],
    ['name' => 'Recherche par matricule', 'grade' => 'all', 'matricule' => 'EN'],
];

echo "🧪 Test 1: Contrôleur ExpulsionController\n";
echo "-" . str_repeat("-", 40) . "\n";

$expulsionController = new ExpulsionController();
$testResults = [];

foreach ($testScenarios as $scenario) {
    try {
        $request = new Request([
            'grade' => $scenario['grade'],
            'matricule' => $scenario['matricule']
        ]);
        
        $result = $expulsionController->index($request);
        
        // Vérifier que la vue est retournée correctement
        if ($result instanceof \Illuminate\View\View) {
            $data = $result->getData();
            $expulsionsCount = $data['expulsions']->count();
            $statistics = $data['statistics'];
            
            echo "✅ {$scenario['name']}: {$expulsionsCount} expulsions trouvées\n";
            echo "   📊 Stats: Total={$statistics['total']}, G1={$statistics['grade_1']}, G2={$statistics['grade_2']}, G3={$statistics['grade_3']}\n";
            
            $testResults[] = [
                'scenario' => $scenario['name'],
                'status' => 'SUCCESS',
                'count' => $expulsionsCount,
                'stats' => $statistics
            ];
        } else {
            echo "❌ {$scenario['name']}: Résultat inattendu\n";
            $testResults[] = ['scenario' => $scenario['name'], 'status' => 'FAILED'];
        }
        
    } catch (\Exception $e) {
        echo "❌ {$scenario['name']}: {$e->getMessage()}\n";
        $testResults[] = ['scenario' => $scenario['name'], 'status' => 'ERROR', 'error' => $e->getMessage()];
    }
}

echo "\n🧪 Test 2: Contrôleur DEController (Infirmerie)\n";
echo "-" . str_repeat("-", 40) . "\n";

$deController = new DEController();

foreach ($testScenarios as $scenario) {
    try {
        $request = new Request([
            'grade' => $scenario['grade'],
            'matricule' => $scenario['matricule'],
            'status' => ''
        ]);
        
        $result = $deController->infirmerie($request);
        
        if ($result instanceof \Illuminate\View\View) {
            $data = $result->getData();
            $patientsCount = $data['patients']->count();
            $stats = $data['stats'];
            
            echo "✅ {$scenario['name']}: {$patientsCount} patients trouvés\n";
            echo "   📊 Stats: Total={$stats['total']}, Validés={$stats['validated']}, En attente={$stats['pending']}\n";
            
        } else {
            echo "❌ {$scenario['name']}: Résultat inattendu\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ {$scenario['name']}: {$e->getMessage()}\n";
    }
}

echo "\n📊 Test 3: Vérification des Données de Base\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    $today = now()->toDateString();
    
    // Statistiques générales
    $totalExpulsions = \App\Models\Expulsion::whereDate('date_expulsion', $today)->count();
    $totalPatients = \App\Models\Patient::whereDate('created_at', $today)->count();
    
    echo "📅 Données du jour ($today):\n";
    echo "   🚪 Expulsions: $totalExpulsions\n";
    echo "   🏥 Patients infirmerie: $totalPatients\n";
    
    // Statistiques par grade
    echo "\n👥 Étudiants par grade:\n";
    for ($grade = 1; $grade <= 3; $grade++) {
        $studentsCount = \App\Models\Student::where('grade', $grade)->count();
        $expulsionsGrade = \App\Models\Expulsion::whereDate('date_expulsion', $today)
            ->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            })->count();
        $patientsGrade = \App\Models\Patient::whereDate('created_at', $today)
            ->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            })->count();
            
        echo "   🎓 Grade $grade: $studentsCount étudiants | $expulsionsGrade expulsions | $patientsGrade patients\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la vérification: {$e->getMessage()}\n";
}

echo "\n🎯 Résumé des Tests\n";
echo "=" . str_repeat("=", 20) . "\n";

$successCount = 0;
$totalTests = count($testResults);

foreach ($testResults as $result) {
    if ($result['status'] === 'SUCCESS') {
        $successCount++;
    }
}

echo "✅ Tests réussis: $successCount/$totalTests\n";

if ($successCount === $totalTests) {
    echo "🎉 TOUS LES TESTS SONT RÉUSSIS!\n";
    echo "🚀 Les filtres DE sont maintenant opérationnels.\n";
} else {
    echo "⚠️  Certains tests ont échoué. Vérifiez les erreurs ci-dessus.\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Test terminé le " . date('d/m/Y à H:i:s') . "\n";
