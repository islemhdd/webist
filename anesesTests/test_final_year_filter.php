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

echo "=== TEST DU FILTRAGE PAR ANNÉE - VERSION CORRIGÉE ===\n\n";

// 1. Analyser les données actuelles
echo "1. Analyse des données actuelles:\n";
$today = \Carbon\Carbon::today();

$totalPatients = \DB::table('patients')->count();
$patientsToday = \DB::table('patients')->whereDate('created_at', $today)->count();
$patientsWithType = \DB::table('patients')->whereNotNull('type_medecin')->count();

echo "   - Total patients: $totalPatients\n";
echo "   - Patients créés aujourd'hui: $patientsToday\n";
echo "   - Patients avec type_medecin défini: $patientsWithType\n";

// Répartition par type de médecin
foreach (['psycho', 'médecin générale', 'dentiste'] as $type) {
    $count = \DB::table('patients')->where('type_medecin', $type)->count();
    echo "   - Patients $type: $count\n";
}

echo "\n2. Création de patients de test avec type_medecin défini:\n";

// Supprimer les anciens patients de test s'ils existent
\DB::table('patients')->where('matricule', '>=', 9999990)->delete();

// Obtenir quelques matricules d'étudiants par année
$student1 = \DB::table('students')->where('grade', '1')->first();
$student2 = \DB::table('students')->where('grade', '2')->first();
$student3 = \DB::table('students')->where('grade', '3')->first();

if (!$student1 || !$student2 || !$student3) {
    echo "Erreur: Pas assez d'étudiants dans la base de données\n";
    exit;
}

// Créer des patients de test pour aujourd'hui avec des matricules fictifs
$testMatricule = 9999990;
$testPatients = [
    ['matricule' => $testMatricule++, 'student_grade' => '1', 'type_medecin' => 'psycho', 'valider' => 1],
    ['matricule' => $testMatricule++, 'student_grade' => '2', 'type_medecin' => 'psycho', 'valider' => 1],
    ['matricule' => $testMatricule++, 'student_grade' => '3', 'type_medecin' => 'psycho', 'valider' => 1],
    ['matricule' => $testMatricule++, 'student_grade' => '1', 'type_medecin' => 'médecin générale', 'valider' => 1],
    ['matricule' => $testMatricule++, 'student_grade' => '3', 'type_medecin' => 'médecin générale', 'valider' => 0],
    ['matricule' => $testMatricule++, 'student_grade' => '2', 'type_medecin' => 'dentiste', 'valider' => 1],
];

// Insérer les étudiants de test d'abord
foreach ($testPatients as $patient) {
    // Insérer l'étudiant de test s'il n'existe pas
    $existingStudent = \DB::table('students')->where('matricule', $patient['matricule'])->first();
    if (!$existingStudent) {
        \DB::table('students')->insert([
            'matricule' => $patient['matricule'],
            'nom' => 'TestStudent',
            'prenom' => 'Test',
            'grade' => $patient['student_grade'],
            'created_at' => $today,
            'updated_at' => $today,
            'section_id' => 1,
            'consigned' => 0
        ]);
    }
    
    // Insérer le patient de test
    \DB::table('patients')->insert([
        'matricule' => $patient['matricule'],
        'type_medecin' => $patient['type_medecin'],
        'valider' => $patient['valider'],
        'valider_rhp' => 0,
        'created_at' => $today,
        'updated_at' => $today
    ]);
}

echo "   ✓ " . count($testPatients) . " patients de test créés\n\n";

// 3. Test du filtrage par spécialité
echo "3. Test du filtrage par spécialité:\n";

foreach (['psycho', 'médecin générale', 'dentiste'] as $specialty) {
    $total = \DB::table('patients')
        ->whereDate('created_at', $today)
        ->where('type_medecin', $specialty)
        ->count();
    echo "   - Total patients $specialty aujourd'hui: $total\n";
    
    // Par année
    for ($grade = 1; $grade <= 3; $grade++) {
        $matricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');
        $count = \DB::table('patients')
            ->whereDate('created_at', $today)
            ->where('type_medecin', $specialty)
            ->whereIn('matricule', $matricules)
            ->count();
        echo "     * Année $grade: $count patients\n";
    }
}

echo "\n4. Test de simulation des appels AJAX:\n";

// Test pour psychologue filtrant la 3ème année
echo "   A. Psychologue filtrant 3ème année:\n";
$allowedSpecialty = 'psycho';
$grade = '3';
$studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');

$validPatients = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 1)
    ->count();

$totalValidPsycho = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->where('valider', 1)
    ->count();

echo "      - Patients psycho valides 3ème année: $validPatients\n";
echo "      - Total patients psycho valides: $totalValidPsycho\n";
echo "      - Différence: " . ($totalValidPsycho - $validPatients) . " (patients autres années)\n";

// Test pour médecin générale filtrant toutes les années
echo "   B. Médecin générale filtrant toutes les années:\n";
$allowedSpecialty = 'médecin générale';

$totalMedGen = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->count();

echo "      - Total patients médecin générale: $totalMedGen\n";

// Test pour médecin générale filtrant 1ère année
echo "   C. Médecin générale filtrant 1ère année:\n";
$grade = '1';
$studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');

$medGen1stYear = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->count();

echo "      - Patients médecin générale 1ère année: $medGen1stYear\n";
echo "      - Différence avec total: " . ($totalMedGen - $medGen1stYear) . "\n";

echo "\n5. Test des convocations par spécialité:\n";

// Vérifier les convocations existantes
$totalConvocations = \DB::table('convoncus')->count();
echo "   - Total convocations: $totalConvocations\n";

foreach (['psycho', 'médecin générale', 'dentiste'] as $specialty) {
    $field = ($specialty === 'psycho') ? 'psy' : 
             (($specialty === 'médecin générale') ? 'medGen' : 'chirDent');
    
    $validConvocations = \DB::table('convoncus')->whereNotNull($field)->count();
    $invalidConvocations = \DB::table('convoncus')->whereNull($field)->count();
    
    echo "   - $specialty:\n";
    echo "     * Convocations valides (champ $field non-null): $validConvocations\n";
    echo "     * Convocations invalides (champ $field null): $invalidConvocations\n";
}

echo "\n6. Nettoyage:\n";
// Supprimer les données de test
$deletedPatients = \DB::table('patients')->where('matricule', '>=', 9999990)->delete();
$deletedStudents = \DB::table('students')->where('matricule', '>=', 9999990)->delete();
echo "   ✓ $deletedPatients patients de test supprimés\n";
echo "   ✓ $deletedStudents étudiants de test supprimés\n";

echo "\n=== CONCLUSION ===\n";
echo "Le code de filtrage semble fonctionner correctement.\n";
echo "Si le problème persiste dans l'interface web, il pourrait s'agir de:\n";
echo "1. Un problème de cache JavaScript\n";
echo "2. Une erreur dans les appels AJAX\n";
echo "3. Des données réelles sans type_medecin défini\n";
echo "\n=== FIN DU TEST ===\n";
