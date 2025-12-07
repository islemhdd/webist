<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configuration de la base de données
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'webist',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== TEST DES CORRECTIONS DES CONVOCATIONS ===\n\n";

// Test de la structure de la table convoncus
echo "1. Structure de la table convoncus:\n";
$columns = DB::select("DESCRIBE convoncus");
foreach ($columns as $column) {
    echo "   - {$column->Field} ({$column->Type})\n";
}

echo "\n2. Échantillon de données des convocations:\n";
$convocations = DB::table('convoncus')->limit(5)->get();
foreach ($convocations as $conv) {
    echo "   Matricule: {$conv->matricule}\n";
    echo "   - psy: " . ($conv->psy ?? 'NULL') . "\n";
    echo "   - medGen: " . ($conv->medGen ?? 'NULL') . "\n";
    echo "   - chirDent: " . ($conv->chirDent ?? 'NULL') . "\n";
    echo "   - avisSpe: " . ($conv->avisSpe ?? 'NULL') . "\n";
    echo "   ---\n";
}

echo "\n3. Test de la nouvelle logique des convocations:\n";

// Test pour le médecin chef (tous les champs doivent être non-null)
echo "\n   A. Pour le médecin chef:\n";
$convocationsValidesChef = DB::table('convoncus')
    ->whereNotNull('psy')
    ->whereNotNull('medGen')
    ->whereNotNull('chirDent')
    ->whereNotNull('avisSpe')
    ->count();

$convocationsInvalidesChef = DB::table('convoncus')
    ->where(function($q) {
        $q->whereNull('psy')
          ->orWhereNull('medGen')
          ->orWhereNull('chirDent')
          ->orWhereNull('avisSpe');
    })->count();

echo "      - Convocations VALIDES (tous champs non-null): {$convocationsValidesChef}\n";
echo "      - Convocations NON-VALIDES (au moins un champ null): {$convocationsInvalidesChef}\n";

// Test pour le psychologue (seulement psy)
echo "\n   B. Pour le psychologue:\n";
$convocationsValidesPsy = DB::table('convoncus')->whereNotNull('psy')->count();
$convocationsInvalidesPsy = DB::table('convoncus')->whereNull('psy')->count();

echo "      - Convocations VALIDES (psy non-null): {$convocationsValidesPsy}\n";
echo "      - Convocations NON-VALIDES (psy null): {$convocationsInvalidesPsy}\n";

// Test pour le médecin général (seulement medGen)
echo "\n   C. Pour le médecin général:\n";
$convocationsValidesMedGen = DB::table('convoncus')->whereNotNull('medGen')->count();
$convocationsInvalidesMedGen = DB::table('convoncus')->whereNull('medGen')->count();

echo "      - Convocations VALIDES (medGen non-null): {$convocationsValidesMedGen}\n";
echo "      - Convocations NON-VALIDES (medGen null): {$convocationsInvalidesMedGen}\n";

// Test pour le dentiste (seulement chirDent)
echo "\n   D. Pour le dentiste:\n";
$convocationsValidesDent = DB::table('convoncus')->whereNotNull('chirDent')->count();
$convocationsInvalidesDent = DB::table('convoncus')->whereNull('chirDent')->count();

echo "      - Convocations VALIDES (chirDent non-null): {$convocationsValidesDent}\n";
echo "      - Convocations NON-VALIDES (chirDent null): {$convocationsInvalidesDent}\n";

echo "\n4. Vérification du total:\n";
$totalConvocations = DB::table('convoncus')->count();
echo "   - Total des convocations: {$totalConvocations}\n";

// Vérification que la somme valides + invalides = total pour chaque spécialité
echo "   - Vérification psychologue: " . ($convocationsValidesPsy + $convocationsInvalidesPsy == $totalConvocations ? "✓" : "✗") . "\n";
echo "   - Vérification médecin général: " . ($convocationsValidesMedGen + $convocationsInvalidesMedGen == $totalConvocations ? "✓" : "✗") . "\n";
echo "   - Vérification dentiste: " . ($convocationsValidesDent + $convocationsInvalidesDent == $totalConvocations ? "✓" : "✗") . "\n";
echo "   - Vérification médecin chef: " . ($convocationsValidesChef + $convocationsInvalidesChef == $totalConvocations ? "✓" : "✗") . "\n";

echo "\n5. Test des filtres par grade:\n";
$grades = DB::table('students')->distinct()->pluck('grade');
foreach ($grades as $grade) {
    if ($grade) {
        $studentMatricules = DB::table('students')->where('grade', $grade)->pluck('matricule');
        $convocationsGrade = DB::table('convoncus')->whereIn('matricule', $studentMatricules)->count();
        echo "   - Grade {$grade}: {$convocationsGrade} convocations\n";
    }
}

echo "\n=== FIN DU TEST ===\n";
?>
