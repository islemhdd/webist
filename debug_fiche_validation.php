<?php

echo "🔍 DEBUG VALIDATION FICHE MÉDICALE\n";
echo "==================================\n\n";

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Convoncu;
use App\Models\User;
use Illuminate\Support\Facades\DB;

try {
    echo "1. 📊 Vérification des données de convocation\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Prendre un exemple de convocation avec des données
    $conv = Convoncu::whereNotNull('psy')
        ->whereNotNull('medGen')
        ->whereNotNull('chirDent')
        ->first();

    if (!$conv) {
        echo "❌ Aucune convocation complète trouvée. Créons-en une...\n";

        // Créer une convocation test
        $conv = Convoncu::updateOrCreate(
            ['matricule' => '2022064'],
            [
                'psy' => 'Diagnostic psychologique test - plus de 10 caractères',
                'medGen' => 'Diagnostic médecin général test - plus de 10 caractères',
                'chirDent' => 'Diagnostic dentaire test - plus de 10 caractères',
                'avisSpe' => null
            ]
        );
        echo "✅ Convocation test créée\n";
    }

    echo "\n📋 Données de la convocation {$conv->matricule}:\n";
    echo "   - Psy: '{$conv->psy}' (longueur: " . strlen($conv->psy) . ")\n";
    echo "   - MedGen: '{$conv->medGen}' (longueur: " . strlen($conv->medGen) . ")\n";
    echo "   - ChirDent: '{$conv->chirDent}' (longueur: " . strlen($conv->chirDent) . ")\n";
    echo "   - AvisSpe: '{$conv->avisSpe}' (longueur: " . strlen($conv->avisSpe ?: '') . ")\n";

    echo "\n2. 🧪 Test de la logique de validation (simulation)\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Simuler la logique du médecin chef
    $psyValue = $conv->psy ?: '';
    $medGenValue = $conv->medGen ?: '';
    $chirDentValue = $conv->chirDent ?: '';

    $psyClean = trim($psyValue);
    $medGenClean = trim($medGenValue);
    $chirDentClean = trim($chirDentValue);

    $psyCompleted = $psyClean !== '' && strlen($psyClean) > 2;
    $medGenCompleted = $medGenClean !== '' && strlen($medGenClean) > 2;
    $chirDentCompleted = $chirDentClean !== '' && strlen($chirDentClean) > 2;
    $allDiagnosticsCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;

    echo "   ✅ Psy complété: " . ($psyCompleted ? 'OUI' : 'NON') . " ('{$psyClean}')\n";
    echo "   ✅ MedGen complété: " . ($medGenCompleted ? 'OUI' : 'NON') . " ('{$medGenClean}')\n";
    echo "   ✅ ChirDent complété: " . ($chirDentCompleted ? 'OUI' : 'NON') . " ('{$chirDentClean}')\n";
    echo "   🎯 Tous complétés: " . ($allDiagnosticsCompleted ? '✅ OUI' : '❌ NON') . "\n";

    echo "\n3. 👨‍⚕️ Test de validation pour médecin chef\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Simuler la validation côté contrôleur
    $userRole = 'Medecin';
    $editableFields = ['avisSpe'];

    echo "   - Rôle utilisateur: {$userRole}\n";
    echo "   - Champs modifiables: " . implode(', ', $editableFields) . "\n";

    // Test de la logique de validation pré-soumission
    if (!$conv) {
        echo "   ❌ Aucune convocation trouvée\n";
    } else {
        $psyMissing = $psyClean === '' || strlen($psyClean) < 3;
        $medGenMissing = $medGenClean === '' || strlen($medGenClean) < 3;
        $chirDentMissing = $chirDentClean === '' || strlen($chirDentClean) < 3;

        if ($psyMissing || $medGenMissing || $chirDentMissing) {
            $missing = [];
            if ($psyMissing) $missing[] = "Psychologue";
            if ($medGenMissing) $missing[] = "Médecin Général";
            if ($chirDentMissing) $missing[] = "Dentiste";

            echo "   ❌ Validation ÉCHEC - Manque: " . implode(', ', $missing) . "\n";
        } else {
            echo "   ✅ Validation SUCCÈS - Peut soumettre avis spécialisé\n";
        }
    }

    echo "\n4. 🔧 Suggestion de correction\n";
    echo "-" . str_repeat("-", 50) . "\n";

    if ($allDiagnosticsCompleted) {
        echo "   ✅ Les données semblent correctes\n";
        echo "   🔍 Le problème est probablement dans la validation Laravel\n";
        echo "   💡 Solution: Utiliser request->only() pour valider uniquement 'avisSpe'\n";
    } else {
        echo "   ❌ Les diagnostics ne sont pas tous complétés\n";
        echo "   📝 Compléter les diagnostics manquants d'abord\n";
    }

    echo "\n5. 📝 Test de données pour debugging\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Simuler ce que le formulaire enverrait
    $simulatedFormData = [
        'avisSpe' => 'Test avis spécialisé du médecin chef - diagnostic complet'
    ];

    echo "   📤 Données simulées du formulaire (médecin chef):\n";
    foreach ($simulatedFormData as $key => $value) {
        echo "      - {$key}: '{$value}'\n";
    }

    echo "\n   🔍 Test de validation Laravel simulé:\n";
    $rules = ['avisSpe' => 'required|string|min:10'];

    foreach ($rules as $field => $rule) {
        $value = $simulatedFormData[$field] ?? '';
        $isValid = strlen(trim($value)) >= 10;
        echo "      - {$field}: " . ($isValid ? '✅ VALIDE' : '❌ INVALIDE') . " ('{$value}')\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
