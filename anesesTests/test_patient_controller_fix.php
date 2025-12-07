<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\Student;
use Carbon\Carbon;

echo "=== TEST PATIENT CONTROLLER (simulation index) ===\n";

try {
    echo "\n=== Test 1: Simulation de la requête PatientController (médecin chef) ===\n";

    // Simuler la requête du PatientController pour le médecin chef
    $query = Patient::query();

    // Filtrage pour médecin chef (comme dans le contrôleur)
    $userRole = 'Medecin'; // Médecin chef
    if ($userRole === 'Medecin') {
        $query->whereDate('patients.created_at', Carbon::today());
    }

    // Simuler une recherche (comme dans le contrôleur)
    $search = '2022';
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('matricule', 'LIKE', '%' . $search . '%')
              ->orWhereHas('student', function($subQ) use ($search) {
                  $subQ->where('nom', 'LIKE', '%' . $search . '%')
                       ->orWhere('prenom', 'LIKE', '%' . $search . '%');
              });
        });
    }

    // Join avec Students (comme dans le contrôleur)
    $patients = $query->join('Students', 'patients.matricule', '=', 'Students.matricule')
        ->select('patients.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
        ->orderBy('patients.created_at', 'desc')
        ->take(5)
        ->get();

    echo "Test PatientController simulation: SUCCÈS\n";
    echo "Patients récupérés: " . $patients->count() . "\n";

    foreach($patients as $patient) {
        echo "Matricule: {$patient->matricule} - Nom: {$patient->nom} {$patient->prenom} - Section: {$patient->section_id}\n";
    }

} catch (\Exception $e) {
    echo "ERREUR dans PatientController simulation: " . $e->getMessage() . "\n";
}

try {
    echo "\n=== Test 2: Requête de comptage (comme dans le count) ===\n";

    // Test du count qui pourrait causer l'erreur
    $count = Patient::join('Students', 'patients.matricule', '=', 'Students.matricule')
        ->whereDate('patients.created_at', Carbon::today())
        ->count();

    echo "Test count avec jointure: SUCCÈS\n";
    echo "Nombre total de patients aujourd'hui: $count\n";

} catch (\Exception $e) {
    echo "ERREUR dans le count: " . $e->getMessage() . "\n";
}

try {
    echo "\n=== Test 3: Test pagination (simulation complète) ===\n";

    $query = Patient::query();
    $query->whereDate('patients.created_at', Carbon::today());

    // Simulation pagination comme dans le vrai contrôleur
    $perPage = 15;
    $patients = $query->join('Students', 'patients.matricule', '=', 'Students.matricule')
        ->select('patients.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
        ->orderBy('patients.created_at', 'desc')
        ->paginate($perPage);

    echo "Test pagination complète: SUCCÈS\n";
    echo "Total patients: " . $patients->total() . "\n";
    echo "Patients par page: " . $patients->count() . "\n";

} catch (\Exception $e) {
    echo "ERREUR dans la pagination: " . $e->getMessage() . "\n";
}

echo "\n=== Résumé des tests PatientController ===\n";
echo "Si aucune erreur n'est affichée, le PatientController est corrigé !\n";
