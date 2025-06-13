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

echo "=== TEST DU FILTRAGE PAR ANNÉE ===\n\n";

// Test 1: Vérifier les données de base
echo "1. Données de base:\n";
echo "   - Étudiants en 3e année: " . \DB::table('students')->where('grade', '3')->count() . "\n";
echo "   - Total étudiants: " . \DB::table('students')->count() . "\n";
echo "   - Total patients aujourd'hui: " . \DB::table('patients')->whereDate('created_at', today())->count() . "\n";
echo "   - Total convocations: " . \DB::table('convoncus')->count() . "\n\n";

// Test 2: Patients par spécialité
echo "2. Patients par spécialité (aujourd'hui):\n";
$specialties = ['psycho', 'médecin générale', 'dentiste'];
foreach ($specialties as $specialty) {
    $total = \DB::table('patients')
        ->whereDate('created_at', today())
        ->where('type_medecin', $specialty)
        ->count();
    echo "   - $specialty: $total patients\n";
}
echo "\n";

// Test 3: Patients par spécialité ET par année
echo "3. Patients psycho filtrés par année (aujourd'hui):\n";
for ($grade = 1; $grade <= 3; $grade++) {
    $matricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');
    $count = \DB::table('patients')
        ->whereDate('created_at', today())
        ->where('type_medecin', 'psycho')
        ->whereIn('matricule', $matricules)
        ->count();
    echo "   - Année $grade: $count patients\n";
}
echo "\n";

// Test 4: Vérifier les matricules
echo "4. Échantillon de matricules par année:\n";
for ($grade = 1; $grade <= 3; $grade++) {
    $matricules = \DB::table('students')->where('grade', $grade)->limit(5)->pluck('matricule');
    echo "   - Année $grade: [" . implode(', ', $matricules->toArray()) . "]\n";
}
echo "\n";

// Test 5: Patients avec leurs matricules
echo "5. Patients psycho avec leurs années:\n";
$patients = \DB::table('patients')
    ->join('students', 'patients.matricule', '=', 'students.matricule')
    ->where('patients.type_medecin', 'psycho')
    ->whereDate('patients.created_at', today())
    ->select('patients.matricule', 'students.grade')
    ->limit(10)
    ->get();

foreach ($patients as $patient) {
    echo "   - Matricule: {$patient->matricule}, Année: {$patient->grade}\n";
}

// Test 6: Test complet de filtrage
echo "\n6. Test complet de filtrage pour psychologue:\n";
$today = \Carbon\Carbon::today();
$grade = '3';
$allowedSpecialty = 'psycho';

$studentMatricules = \DB::table('students')->where('grade', $grade)->pluck('matricule');
echo "   - Étudiants 3e année: " . $studentMatricules->count() . "\n";

$validPatients = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 1)
    ->count();

$invalidPatients = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->whereIn('matricule', $studentMatricules)
    ->where('valider', 0)
    ->count();

echo "   - Patients psycho valides 3e année: $validPatients\n";
echo "   - Patients psycho invalides 3e année: $invalidPatients\n";

// Comparer avec les totaux
$totalValidPsycho = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->where('valider', 1)
    ->count();

$totalInvalidPsycho = \DB::table('patients')
    ->whereDate('created_at', $today)
    ->where('type_medecin', $allowedSpecialty)
    ->where('valider', 0)
    ->count();

echo "   - Total patients psycho valides: $totalValidPsycho\n";
echo "   - Total patients psycho invalides: $totalInvalidPsycho\n";

echo "\n=== FIN DU TEST ===\n";
