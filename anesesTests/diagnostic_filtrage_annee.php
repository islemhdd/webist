<?php
// Définir le chemin racine de Laravel
define('LARAVEL_START', microtime(true));

// Charger l'autoloader de Composer
require __DIR__.'/vendor/autoload.php';

// Bootstrapper l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Créer un kernel pour la console
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC DU PROBLÈME DE FILTRAGE PAR ANNÉE ===\n\n";

$today = \Carbon\Carbon::today();

// 1. Analyser le problème principal
echo "1. Analyse du problème:\n";
echo "   Le problème rapporté: quand on filtre par année (3ème année, etc.),\n";
echo "   le système affiche les nombres totaux au lieu des nombres spécifiques à la spécialité.\n\n";

// 2. Vérifier les données actuelles
echo "2. État actuel des données:\n";
$totalPatients = \DB::table('patients')->count();
$patientsWithType = \DB::table('patients')->whereNotNull('type_medecin')->count();
$patientsToday = \DB::table('patients')->whereDate('created_at', $today)->count();

echo "   - Total patients: $totalPatients\n";
echo "   - Patients avec type_medecin défini: $patientsWithType\n";
echo "   - Patients créés aujourd'hui: $patientsToday\n";

// Vérifier s'il y a des patients avec type_medecin défini
if ($patientsWithType === 0) {
    echo "   ⚠️  PROBLÈME IDENTIFIÉ: Aucun patient n'a de type_medecin défini!\n";
    echo "      C'est pourquoi le filtrage ne fonctionne pas.\n\n";
    
    // Mettre à jour quelques patients existants pour le test
    echo "3. Mise à jour de quelques patients existants pour le test:\n";
    
    // Prendre les 6 premiers patients et leur assigner des types
    $patients = \DB::table('patients')->limit(6)->get();
    $types = ['psycho', 'médecin générale', 'dentiste'];
    $typeIndex = 0;
    
    foreach ($patients as $patient) {
        $type = $types[$typeIndex % 3];
        \DB::table('patients')
            ->where('id', $patient->id)
            ->update(['type_medecin' => $type]);
        echo "   - Patient ID {$patient->id} (matricule {$patient->matricule}) → $type\n";
        $typeIndex++;
    }
    echo "   ✓ 6 patients mis à jour avec des types de médecin\n\n";
}

// 3. Test du filtrage maintenant
echo "4. Test du filtrage par spécialité:\n";
foreach (['psycho', 'médecin générale', 'dentiste'] as $specialty) {
    $count = \DB::table('patients')->where('type_medecin', $specialty)->count();
    echo "   - Patients $specialty: $count\n";
}

echo "\n5. Test du filtrage par spécialité ET par année:\n";
foreach (['psycho', 'médecin générale', 'dentiste'] as $specialty) {
    echo "   Spécialité: $specialty\n";
    
    $totalSpecialty = \DB::table('patients')->where('type_medecin', $specialty)->count();
    echo "     - Total: $totalSpecialty\n";
    
    for ($grade = 1; $grade <= 3; $grade++) {
        $matricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');
        $count = \DB::table('patients')
            ->where('type_medecin', $specialty)
            ->whereIn('matricule', $matricules)
            ->count();
        echo "     - Année $grade: $count patients\n";
    }
    echo "\n";
}

// 4. Simuler exactement l'appel AJAX
echo "6. Simulation d'appel AJAX pour un médecin spécialisé:\n";

// Test pour psychologue filtrant par grade "all"
echo "   A. Psychologue - Grade 'all':\n";
$allowedSpecialty = 'psycho';

$validPatientsToday = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->where('valider', 1)
    ->count();
    
$invalidPatientsToday = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->where('valider', 0)
    ->count();

echo "      - Patients psycho valides aujourd'hui: $validPatientsToday\n";
echo "      - Patients psycho invalides aujourd'hui: $invalidPatientsToday\n";

// Test pour psychologue filtrant par grade "3"
echo "   B. Psychologue - Grade '3':\n";
$grade = '3';
$studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');

$validPatientsGrade3 = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 1)
    ->count();

$invalidPatientsGrade3 = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 0)
    ->count();

echo "      - Patients psycho valides 3ème année aujourd'hui: $validPatientsGrade3\n";
echo "      - Patients psycho invalides 3ème année aujourd'hui: $invalidPatientsGrade3\n";

// Vérifier si le problème persiste
if ($validPatientsToday === $validPatientsGrade3 && $invalidPatientsToday === $invalidPatientsGrade3) {
    if ($validPatientsToday === 0 && $invalidPatientsToday === 0) {
        echo "      ℹ️  Résultats identiques mais normaux (pas de patients aujourd'hui)\n";
    } else {
        echo "      ⚠️  PROBLÈME CONFIRMÉ: Les résultats sont identiques alors qu'ils devraient être différents!\n";
    }
} else {
    echo "      ✅ Le filtrage fonctionne correctement (résultats différents)\n";
}

// 5. Test avec des données d'autres dates
echo "\n7. Test avec toutes les dates (pas seulement aujourd'hui):\n";

$validPatientsAll = \DB::table('patients')
    ->where('type_medecin', $allowedSpecialty)
    ->where('valider', 1)
    ->count();

$validPatientsGrade3All = \DB::table('patients')
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 1)
    ->count();

echo "   - Total patients psycho valides (toutes dates): $validPatientsAll\n";
echo "   - Patients psycho valides 3ème année (toutes dates): $validPatientsGrade3All\n";
echo "   - Différence: " . ($validPatientsAll - $validPatientsGrade3All) . "\n";

if ($validPatientsAll === $validPatientsGrade3All && $validPatientsAll > 0) {
    echo "   ⚠️  PROBLÈME: Tous les patients psycho sont en 3ème année (improbable)\n";
} else {
    echo "   ✅ Le filtrage semble fonctionner correctement\n";
}

// 6. Vérifier la répartition des étudiants par année
echo "\n8. Répartition des étudiants par année:\n";
for ($grade = 1; $grade <= 3; $grade++) {
    $count = \DB::table('students')->where('grade', $grade)->count();
    echo "   - Année $grade: $count étudiants\n";
}

// 7. Vérifier les patients par année
echo "\n9. Répartition des patients par année de leurs étudiants:\n";
for ($grade = 1; $grade <= 3; $grade++) {
    $matricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');
    $count = \DB::table('patients')->whereIn('matricule', $matricules)->count();
    echo "   - Patients d'étudiants année $grade: $count\n";
}

echo "\n=== DIAGNOSTIC TERMINÉ ===\n";
echo "Si le problème persiste dans l'interface web:\n";
echo "1. Vérifiez que les patients ont bien un type_medecin défini\n";
echo "2. Vérifiez que les appels AJAX utilisent les bons paramètres\n";
echo "3. Vérifiez qu'il n'y a pas de cache JavaScript\n";
echo "4. Activez les logs pour voir les requêtes SQL exactes\n";
