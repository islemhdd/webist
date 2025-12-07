<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\Student;
use Carbon\Carbon;

echo "=== TEST API PATIENTS (médecin chef) ===\n";

// Compter les patients d'aujourd'hui
$patientsToday = Patient::whereDate('created_at', Carbon::today())->count();
echo "Patients aujourd'hui: $patientsToday\n";

// Tester la requête avec jointure (comme dans patientsApi)
try {
    echo "\n=== Test 1: Requête de base avec relation ===\n";
    $query = Patient::with('Student')->whereDate('patients.created_at', Carbon::today());
    $patients = $query->orderBy('patients.created_at', 'desc')->take(3)->get();

    echo "Test requête avec jointure: SUCCÈS\n";
    echo "Patients récupérés: " . $patients->count() . "\n";

    foreach($patients as $patient) {
        $student = $patient->Student;
        echo "Matricule: {$patient->matricule} - Nom: " . ($student ? $student->nom . ' ' . $student->prenom : 'N/A') . "\n";
    }
} catch (\Exception $e) {
    echo "ERREUR dans la requête: " . $e->getMessage() . "\n";
}

try {
    echo "\n=== Test 2: Requête avec recherche (comme dans patientsApi) ===\n";
    $search = '2022';
    $query = Patient::with('Student')->whereDate('patients.created_at', Carbon::today());
    $query->where(function($q) use ($search) {
        $q->where('matricule', 'LIKE', '%' . $search . '%')
          ->orWhereHas('Student', function($subQ) use ($search) {
              $subQ->where('nom', 'LIKE', '%' . $search . '%')
                   ->orWhere('prenom', 'LIKE', '%' . $search . '%');
          });
    });

    $patients = $query->orderBy('patients.created_at', 'desc')->take(5)->get();

    echo "Test requête avec recherche: SUCCÈS\n";
    echo "Patients trouvés avec recherche '$search': " . $patients->count() . "\n";

    foreach($patients as $patient) {
        $student = $patient->Student;
        echo "Matricule: {$patient->matricule} - Nom: " . ($student ? $student->nom . ' ' . $student->prenom : 'N/A') . "\n";
    }
} catch (\Exception $e) {
    echo "ERREUR dans la requête avec recherche: " . $e->getMessage() . "\n";
}

try {
    echo "\n=== Test 3: Requête avec filtres de spécialité ===\n";
    $query = Patient::with('Student')->whereDate('patients.created_at', Carbon::today());
    $query->where('type_medecin', 'médecin générale');

    $patients = $query->orderBy('patients.created_at', 'desc')->take(3)->get();

    echo "Test requête avec filtre spécialité: SUCCÈS\n";
    echo "Patients médecin général: " . $patients->count() . "\n";

} catch (\Exception $e) {
    echo "ERREUR dans la requête avec filtre: " . $e->getMessage() . "\n";
}

try {
    echo "\n=== Test 4: Simulation complète de patientsApi ===\n";

    // Simulation de la méthode patientsApi complète
    $query = Patient::with('Student')->query();
    $query->whereDate('patients.created_at', Carbon::today());

    // Test avec recherche
    $search = 'Test';
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('matricule', 'LIKE', '%' . $search . '%')
              ->orWhereHas('Student', function($subQ) use ($search) {
                  $subQ->where('nom', 'LIKE', '%' . $search . '%')
                       ->orWhere('prenom', 'LIKE', '%' . $search . '%');
              });
        });
    }

    // Test pagination
    $perPage = 15;
    $patients = $query->orderBy('patients.created_at', 'desc')->paginate($perPage);

    echo "Test simulation complète de patientsApi: SUCCÈS\n";
    echo "Total patients: " . $patients->total() . "\n";
    echo "Patients par page: " . $patients->count() . "\n";
    echo "Page actuelle: " . $patients->currentPage() . "\n";

} catch (\Exception $e) {
    echo "ERREUR dans la simulation complète: " . $e->getMessage() . "\n";
}

echo "\n=== Résumé des tests ===\n";
echo "Tous les tests sont terminés. Si aucune erreur n'est affichée, l'API est corrigée !\n";
