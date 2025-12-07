<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// Boot the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\Patient;
use Carbon\Carbon;

echo "=== DEBUG DES GRADES ===\n";

// Vérifier les grades disponibles
$grades = Student::distinct()->pluck('grade')->toArray();
echo "Grades disponibles: " . implode(', ', $grades) . "\n";

// Compter les étudiants par grade
$students = Student::selectRaw('grade, COUNT(*) as count')->groupBy('grade')->get();
foreach($students as $student) {
    echo "Grade: " . $student->grade . " - Count: " . $student->count . "\n";
}

echo "\n=== PATIENTS PAR GRADE ===\n";
$today = Carbon::today();

foreach(['1', '2', '3'] as $grade) {
    $studentMatricules = Student::where('grade', $grade)->pluck('matricule');
    echo "Grade $grade - Matricules trouvés: " . $studentMatricules->count() . "\n";

    $validPatients = Patient::whereDate('created_at', $today)
        ->where('valider', 1)
        ->whereIn('matricule', $studentMatricules)
        ->count();

    $invalidPatients = Patient::whereDate('created_at', $today)
        ->where('valider', 0)
        ->whereIn('matricule', $studentMatricules)
        ->count();

    echo "Grade $grade - Patients validés: $validPatients, Patients en attente: $invalidPatients\n";
}

echo "\n=== TOUS LES PATIENTS AUJOURD'HUI ===\n";
$allValidPatients = Patient::whereDate('created_at', $today)->where('valider', 1)->count();
$allInvalidPatients = Patient::whereDate('created_at', $today)->where('valider', 0)->count();
echo "Total validés: $allValidPatients, Total en attente: $allInvalidPatients\n";

// Vérifier quelques matricules de patients
$patientMatricules = Patient::whereDate('created_at', $today)->pluck('matricule')->unique()->take(10);
echo "\nExemples de matricules de patients: " . $patientMatricules->implode(', ') . "\n";

// Vérifier quelques matricules d'étudiants
$studentMatricules = Student::take(10)->pluck('matricule');
echo "Exemples de matricules d'étudiants: " . $studentMatricules->implode(', ') . "\n";
