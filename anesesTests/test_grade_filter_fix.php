<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Test du filtre d'année corrigé ===\n\n";

// Vérifier les patients avec leurs grades
$patients = DB::table('patients')
    ->join('students', 'patients.matricule', '=', 'students.matricule')
    ->select('patients.id', 'patients.matricule', 'students.grade', 'students.section_id', 'students.nom', 'students.prenom', 'patients.created_at')
    ->whereDate('patients.created_at', '=', now()->toDateString())
    ->orderBy('students.grade')
    ->get();

echo "Patients d'aujourd'hui avec leurs grades:\n";
echo "==========================================\n";

$gradeCount = [1 => 0, 2 => 0, 3 => 0];

foreach ($patients as $patient) {
    echo "Matricule: {$patient->matricule} | Grade: {$patient->grade} | Section: {$patient->section_id} | Nom: {$patient->nom} {$patient->prenom}\n";
    if (isset($gradeCount[$patient->grade])) {
        $gradeCount[$patient->grade]++;
    }
}

echo "\n=== Résumé par grade ===\n";
echo "1ère année: {$gradeCount[1]} patients\n";
echo "2ème année: {$gradeCount[2]} patients\n";
echo "3ème année: {$gradeCount[3]} patients\n";
echo "Total: " . array_sum($gradeCount) . " patients\n";

echo "\n=== Test terminé ===\n";
