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

echo "=== TEST DU FILTRAGE PAR ANNÉE AVEC DONNÉES DE TEST ===\n\n";

// Créer des données de test
echo "1. Création de données de test...\n";

// Obtenir quelques matricules d'étudiants
$student1 = \DB::table('students')->where('grade', '1')->first();
$student2 = \DB::table('students')->where('grade', '2')->first();
$student3 = \DB::table('students')->where('grade', '3')->first();

if (!$student1 || !$student2 || !$student3) {
    echo "Erreur: Pas assez d'étudiants dans la base de données\n";
    exit;
}

echo "   - Étudiant 1ère année: {$student1->matricule}\n";
echo "   - Étudiant 2ème année: {$student2->matricule}\n";
echo "   - Étudiant 3ème année: {$student3->matricule}\n\n";

// Supprimer les anciens patients de test
\DB::table('patients')->where('nom', 'LIKE', 'TestPatient%')->delete();

// Créer des patients de test pour aujourd'hui
$today = \Carbon\Carbon::today();
$patients = [
    ['nom' => 'TestPatientPsy1', 'matricule' => $student1->matricule, 'type_medecin' => 'psycho', 'valider' => 1],
    ['nom' => 'TestPatientPsy2', 'matricule' => $student2->matricule, 'type_medecin' => 'psycho', 'valider' => 1],
    ['nom' => 'TestPatientPsy3', 'matricule' => $student3->matricule, 'type_medecin' => 'psycho', 'valider' => 1],
    ['nom' => 'TestPatientMed1', 'matricule' => $student1->matricule, 'type_medecin' => 'médecin générale', 'valider' => 1],
    ['nom' => 'TestPatientMed3', 'matricule' => $student3->matricule, 'type_medecin' => 'médecin générale', 'valider' => 0],
    ['nom' => 'TestPatientDent2', 'matricule' => $student2->matricule, 'type_medecin' => 'dentiste', 'valider' => 1],
];

foreach ($patients as $patient) {
    \DB::table('patients')->insert([
        'nom' => $patient['nom'],
        'prenom' => 'Test',
        'matricule' => $patient['matricule'],
        'type_medecin' => $patient['type_medecin'],
        'valider' => $patient['valider'],
        'created_at' => $today,
        'updated_at' => $today,
        'date_consultation' => $today->format('Y-m-d'),
        'telephone' => '0123456789',
        'email' => 'test@test.com'
    ]);
}

echo "2. Patients créés avec succès!\n\n";

// Test 3: Vérifier le filtrage
echo "3. Test du filtrage par spécialité et année:\n";

// Test pour psychologue - toutes années
$totalPsycho = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', 'psycho')
    ->where('valider', 1)
    ->count();
echo "   - Total patients psycho valides: $totalPsycho (attendu: 3)\n";

// Test pour psychologue - 3ème année seulement
$studentMatricules3 = \DB::table('students')->where('grade', '3')->pluck('matricule');
$psycho3rdYear = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', 'psycho')
    ->whereIn('matricule', $studentMatricules3)
    ->where('valider', 1)
    ->count();
echo "   - Patients psycho valides 3ème année: $psycho3rdYear (attendu: 1)\n";

// Test pour médecin générale - toutes années
$totalMedGen = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', 'médecin générale')
    ->count();
echo "   - Total patients médecin générale: $totalMedGen (attendu: 2)\n";

// Test pour médecin générale - 3ème année seulement
$medGen3rdYear = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', 'médecin générale')
    ->whereIn('matricule', $studentMatricules3)
    ->count();
echo "   - Patients médecin générale 3ème année: $medGen3rdYear (attendu: 1)\n";

echo "\n4. Test de simulation de l'appel AJAX pour psychologue:\n";

// Simuler l'appel de getSpecialtyStatsByGrade pour un psychologue
$allowedSpecialty = 'psycho';
$grade = '3';

$studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');
$validPatientsToday = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 1)
    ->count();

$invalidPatientsToday = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 0)
    ->count();

echo "   - Résultat simulé pour psycho/3e année:\n";
echo "     * Patients valides: $validPatientsToday (attendu: 1)\n";
echo "     * Patients invalides: $invalidPatientsToday (attendu: 0)\n";

echo "\n5. Test de simulation de l'appel AJAX pour médecin générale:\n";

$allowedSpecialty = 'médecin générale';
$validPatientsToday = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 1)
    ->count();

$invalidPatientsToday = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 0)
    ->count();

echo "   - Résultat simulé pour médecin générale/3e année:\n";
echo "     * Patients valides: $validPatientsToday (attendu: 0)\n";
echo "     * Patients invalides: $invalidPatientsToday (attendu: 1)\n";

echo "\n6. Nettoyage - suppression des données de test...\n";
\DB::table('patients')->where('nom', 'LIKE', 'TestPatient%')->delete();
echo "   Données de test supprimées.\n";

echo "\n=== FIN DU TEST ===\n";
