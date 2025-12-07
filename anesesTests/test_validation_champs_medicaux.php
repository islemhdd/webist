<?php

echo "🔍 TEST DE VALIDATION DES CHAMPS MÉDICAUX\n";
echo "========================================\n\n";

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

try {
    echo "1. 🧪 Création de données de test avec différents scénarios\n";
    echo "-" . str_repeat("-", 60) . "\n";

    // Vérifier si l'étudiant existe
    $matriculeTest = '2022TEST';
    $student = Student::where('matricule', $matriculeTest)->first();

    if (!$student) {
        echo "   📝 Création de l'étudiant de test...\n";
        Student::create([
            'matricule' => $matriculeTest,
            'nom' => 'Test',
            'prenom' => 'Validation',
            'section_id' => '1A',
            'grade' => '1'
        ]);
    }

    // Scénario 1: Tous les champs vides
    echo "\n2. 📋 Scénario 1: Convocation avec tous les champs vides\n";
    echo "-" . str_repeat("-", 60) . "\n";

    Convoncu::updateOrCreate(
        ['matricule' => $matriculeTest],
        [
            'psy' => null,
            'medGen' => null,
            'chirDent' => null,
            'avisSpe' => null
        ]
    );

    $convoncu = Convoncu::where('matricule', $matriculeTest)->first();
    $psyCompleted = $convoncu && !empty(trim($convoncu->psy ?? ''));
    $medGenCompleted = $convoncu && !empty(trim($convoncu->medGen ?? ''));
    $chirDentCompleted = $convoncu && !empty(trim($convoncu->chirDent ?? ''));
    $allCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;

    echo "   État: Psy(" . ($psyCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "MedGen(" . ($medGenCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "ChirDent(" . ($chirDentCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "Tous: " . ($allCompleted ? 'PRÊT' : 'PAS PRÊT') . "\n";

    // Scénario 2: Champs avec espaces seulement
    echo "\n3. 📋 Scénario 2: Champs remplis avec espaces seulement\n";
    echo "-" . str_repeat("-", 60) . "\n";

    Convoncu::updateOrCreate(
        ['matricule' => $matriculeTest],
        [
            'psy' => '   ',  // Espaces seulement
            'medGen' => '',  // Chaîne vide
            'chirDent' => '    ',  // Plus d'espaces
            'avisSpe' => null
        ]
    );

    $convoncu = Convoncu::where('matricule', $matriculeTest)->first();
    $psyCompleted = $convoncu && !empty(trim($convoncu->psy ?? ''));
    $medGenCompleted = $convoncu && !empty(trim($convoncu->medGen ?? ''));
    $chirDentCompleted = $convoncu && !empty(trim($convoncu->chirDent ?? ''));
    $allCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;

    echo "   Valeurs brutes:\n";
    echo "   - Psy: '" . ($convoncu->psy ?? 'NULL') . "' (longueur: " . strlen($convoncu->psy ?? '') . ")\n";
    echo "   - MedGen: '" . ($convoncu->medGen ?? 'NULL') . "' (longueur: " . strlen($convoncu->medGen ?? '') . ")\n";
    echo "   - ChirDent: '" . ($convoncu->chirDent ?? 'NULL') . "' (longueur: " . strlen($convoncu->chirDent ?? '') . ")\n";

    echo "   État après trim(): Psy(" . ($psyCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "MedGen(" . ($medGenCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "ChirDent(" . ($chirDentCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "Tous: " . ($allCompleted ? 'PRÊT' : 'PAS PRÊT') . "\n";

    // Scénario 3: Diagnostic psychologue rempli seulement
    echo "\n4. 📋 Scénario 3: Seulement le psychologue a rempli\n";
    echo "-" . str_repeat("-", 60) . "\n";

    Convoncu::updateOrCreate(
        ['matricule' => $matriculeTest],
        [
            'psy' => 'Patient présente des signes d\'anxiété légère. Recommande suivi hebdomadaire.',
            'medGen' => null,
            'chirDent' => null,
            'avisSpe' => null
        ]
    );

    $convoncu = Convoncu::where('matricule', $matriculeTest)->first();
    $psyCompleted = $convoncu && !empty(trim($convoncu->psy ?? ''));
    $medGenCompleted = $convoncu && !empty(trim($convoncu->medGen ?? ''));
    $chirDentCompleted = $convoncu && !empty(trim($convoncu->chirDent ?? ''));
    $allCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;

    echo "   État: Psy(" . ($psyCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "MedGen(" . ($medGenCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "ChirDent(" . ($chirDentCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "Tous: " . ($allCompleted ? 'PRÊT' : 'PAS PRÊT') . "\n";

    // Scénario 4: Tous les diagnostics remplis
    echo "\n5. 📋 Scénario 4: Tous les diagnostics remplis\n";
    echo "-" . str_repeat("-", 60) . "\n";

    Convoncu::updateOrCreate(
        ['matricule' => $matriculeTest],
        [
            'psy' => 'Patient présente des signes d\'anxiété légère. Recommande suivi hebdomadaire.',
            'medGen' => 'Examen médical général normal. Aucune pathologie détectée.',
            'chirDent' => 'Hygiène dentaire correcte. Carie sur molaire droite à traiter.',
            'avisSpe' => null
        ]
    );

    $convoncu = Convoncu::where('matricule', $matriculeTest)->first();
    $psyCompleted = $convoncu && !empty(trim($convoncu->psy ?? ''));
    $medGenCompleted = $convoncu && !empty(trim($convoncu->medGen ?? ''));
    $chirDentCompleted = $convoncu && !empty(trim($convoncu->chirDent ?? ''));
    $allCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;

    echo "   État: Psy(" . ($psyCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "MedGen(" . ($medGenCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "ChirDent(" . ($chirDentCompleted ? 'OK' : 'VIDE') . ") | ";
    echo "Tous: " . ($allCompleted ? 'PRÊT' : 'PAS PRÊT') . "\n";

    if ($allCompleted) {
        echo "   ✅ Le médecin chef peut maintenant enregistrer son avis spécialisé!\n";
    } else {
        echo "   ❌ Le médecin chef ne peut pas encore enregistrer son avis spécialisé.\n";
    }

    echo "\n6. 🧪 Test de la logique de validation avec empty() vs trim()\n";
    echo "-" . str_repeat("-", 60) . "\n";

    $testValues = [
        'null' => null,
        'empty string' => '',
        'spaces only' => '   ',
        'text with spaces' => '  diagnostic valide  ',
        'valid text' => 'diagnostic valide'
    ];

    foreach ($testValues as $label => $value) {
        $emptyResult = empty($value);
        $trimEmptyResult = empty(trim($value ?? ''));

        echo "   {$label}: empty()=" . ($emptyResult ? 'true' : 'false') .
             " | empty(trim())=" . ($trimEmptyResult ? 'true' : 'false') . "\n";
    }

    echo "\n✅ Test de validation terminé. La logique avec trim() est plus fiable!\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
