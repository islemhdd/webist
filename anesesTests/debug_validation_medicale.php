<?php

echo "🔍 DEBUG AVANCÉ - VALIDATION CHAMPS MÉDICAUX\n";
echo "============================================\n\n";

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

try {
    echo "1. 🔎 Analyse des données brutes dans la base\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $convocations = Convoncu::limit(5)->get();

    foreach ($convocations as $conv) {
        echo "📋 Matricule: {$conv->matricule}\n";

        // Valeurs brutes
        $psyRaw = $conv->psy;
        $medGenRaw = $conv->medGen;
        $chirDentRaw = $conv->chirDent;
        $avisRaw = $conv->avisSpe;

        echo "   🧠 PSY:\n";
        echo "      - Valeur: " . ($psyRaw === null ? 'NULL' : "'{$psyRaw}'") . "\n";
        echo "      - Type: " . gettype($psyRaw) . "\n";
        echo "      - Longueur: " . (is_string($psyRaw) ? strlen($psyRaw) : 'N/A') . "\n";
        echo "      - Après trim: '" . trim($psyRaw ?? '') . "'\n";
        echo "      - Est vide (empty): " . (empty($psyRaw) ? 'OUI' : 'NON') . "\n";
        echo "      - Après trim est vide: " . (empty(trim($psyRaw ?? '')) ? 'OUI' : 'NON') . "\n";

        echo "   🩺 MEDGEN:\n";
        echo "      - Valeur: " . ($medGenRaw === null ? 'NULL' : "'{$medGenRaw}'") . "\n";
        echo "      - Type: " . gettype($medGenRaw) . "\n";
        echo "      - Longueur: " . (is_string($medGenRaw) ? strlen($medGenRaw) : 'N/A') . "\n";
        echo "      - Après trim: '" . trim($medGenRaw ?? '') . "'\n";
        echo "      - Est vide (empty): " . (empty($medGenRaw) ? 'OUI' : 'NON') . "\n";
        echo "      - Après trim est vide: " . (empty(trim($medGenRaw ?? '')) ? 'OUI' : 'NON') . "\n";

        echo "   🦷 CHIRDENT:\n";
        echo "      - Valeur: " . ($chirDentRaw === null ? 'NULL' : "'{$chirDentRaw}'") . "\n";
        echo "      - Type: " . gettype($chirDentRaw) . "\n";
        echo "      - Longueur: " . (is_string($chirDentRaw) ? strlen($chirDentRaw) : 'N/A') . "\n";
        echo "      - Après trim: '" . trim($chirDentRaw ?? '') . "'\n";
        echo "      - Est vide (empty): " . (empty($chirDentRaw) ? 'OUI' : 'NON') . "\n";
        echo "      - Après trim est vide: " . (empty(trim($chirDentRaw ?? '')) ? 'OUI' : 'NON') . "\n";

        // Test de la logique de validation comme dans la vue
        $psyCompleted = $conv && !empty(trim($conv->psy ?? ''));
        $medGenCompleted = $conv && !empty(trim($conv->medGen ?? ''));
        $chirDentCompleted = $conv && !empty(trim($conv->chirDent ?? ''));
        $allCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;

        echo "   ✅ VALIDATION LOGIQUE:\n";
        echo "      - Psy complété: " . ($psyCompleted ? 'OUI ✅' : 'NON ❌') . "\n";
        echo "      - MedGen complété: " . ($medGenCompleted ? 'OUI ✅' : 'NON ❌') . "\n";
        echo "      - ChirDent complété: " . ($chirDentCompleted ? 'OUI ✅' : 'NON ❌') . "\n";
        echo "      - TOUS complétés: " . ($allCompleted ? 'OUI ✅' : 'NON ❌') . "\n";

        echo "   " . str_repeat("-", 40) . "\n\n";
    }

    echo "2. 🧪 Test des fonctions de validation PHP\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $testValues = [
        'null' => null,
        'empty_string' => '',
        'spaces' => '   ',
        'tab' => "\t",
        'newline' => "\n",
        'mixed_spaces' => " \t \n ",
        'single_char' => 'a',
        'short_text' => 'ab',
        'good_text' => 'Diagnostic valide',
        'with_spaces' => '  Diagnostic avec espaces  '
    ];

    foreach ($testValues as $label => $value) {
        echo "🔸 Test '{$label}': ";
        echo "Valeur='" . ($value === null ? 'NULL' : $value) . "' ";
        echo "| empty(): " . (empty($value) ? 'true' : 'false') . " ";
        echo "| trim(): '" . trim($value ?? '') . "' ";
        echo "| empty(trim()): " . (empty(trim($value ?? '')) ? 'true' : 'false') . " ";
        echo "| strlen(trim()): " . strlen(trim($value ?? '')) . "\n";
    }

    echo "\n3. 📊 Résumé des convocations par état\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $total = Convoncu::count();
    echo "Total convocations: {$total}\n\n";

    $psyOk = Convoncu::where(function($q) {
        $q->whereNotNull('psy')->where('psy', '!=', '');
    })->count();

    $medGenOk = Convoncu::where(function($q) {
        $q->whereNotNull('medGen')->where('medGen', '!=', '');
    })->count();

    $chirDentOk = Convoncu::where(function($q) {
        $q->whereNotNull('chirDent')->where('chirDent', '!=', '');
    })->count();

    echo "Diagnostics psycho complétés: {$psyOk}/{$total}\n";
    echo "Diagnostics médGen complétés: {$medGenOk}/{$total}\n";
    echo "Diagnostics chirDent complétés: {$chirDentOk}/{$total}\n";

    // Chercher les cas problématiques
    echo "\n4. 🚨 Détection des cas problématiques\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $problematic = Convoncu::where(function($q) {
        $q->where('psy', '')->orWhere('medGen', '')->orWhere('chirDent', '');
    })->get();

    if ($problematic->count() > 0) {
        echo "❌ Convocations avec champs vides (chaînes vides au lieu de NULL):\n";
        foreach ($problematic as $prob) {
            echo "   Matricule {$prob->matricule}: ";
            if ($prob->psy === '') echo "psy='' ";
            if ($prob->medGen === '') echo "medGen='' ";
            if ($prob->chirDent === '') echo "chirDent='' ";
            echo "\n";
        }

        echo "\n🔧 SOLUTION: Convertir les chaînes vides en NULL\n";
        echo "UPDATE convoncus SET psy = NULL WHERE psy = '';\n";
        echo "UPDATE convoncus SET medGen = NULL WHERE medGen = '';\n";
        echo "UPDATE convoncus SET chirDent = NULL WHERE chirDent = '';\n";
    } else {
        echo "✅ Aucun cas problématique détecté\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
