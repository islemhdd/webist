<?php

// Créer des données de test pour vérifier le tableau de bord
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Création de données de test pour le tableau de bord ===\n\n";

    $today = now()->toDateString();    // Utiliser des matricules existants
    $existingMatricules = [2022169, 2022358, 2022375, 2022468, 2022545, 2022549, 2022580, 2022705, 2022733, 2022956, 2022055, 2022104];

    // Créer des patients avec différents statuts pour aujourd'hui
    $testData = [
        // 3 patients non validés par médecin (valider = 0)
        ['matricule' => $existingMatricules[0], 'valider' => 0, 'valider_rhp' => 0, 'type_medecin' => 'médecin générale'],
        ['matricule' => $existingMatricules[1], 'valider' => 0, 'valider_rhp' => 0, 'type_medecin' => 'dentiste'],
        ['matricule' => $existingMatricules[2], 'valider' => 0, 'valider_rhp' => 0, 'type_medecin' => 'psycho'],

        // 4 patients validés par médecin mais en attente RHP (valider = 1, valider_rhp = 0)
        ['matricule' => $existingMatricules[3], 'valider' => 1, 'valider_rhp' => 0, 'type_medecin' => 'médecin générale'],
        ['matricule' => $existingMatricules[4], 'valider' => 1, 'valider_rhp' => 0, 'type_medecin' => 'dentiste'],
        ['matricule' => $existingMatricules[5], 'valider' => 1, 'valider_rhp' => 0, 'type_medecin' => 'psycho'],
        ['matricule' => $existingMatricules[6], 'valider' => 1, 'valider_rhp' => 0, 'type_medecin' => 'médecin générale'],

        // 2 patients validés par médecin et RHP (valider = 1, valider_rhp = 1)
        ['matricule' => $existingMatricules[7], 'valider' => 1, 'valider_rhp' => 1, 'type_medecin' => 'médecin générale'],
        ['matricule' => $existingMatricules[8], 'valider' => 1, 'valider_rhp' => 1, 'type_medecin' => 'dentiste'],

        // 2 patients validés avec statut 2 mais en attente RHP (valider = 2, valider_rhp = 0)
        ['matricule' => $existingMatricules[9], 'valider' => 2, 'valider_rhp' => 0, 'type_medecin' => 'médecin générale'],
        ['matricule' => $existingMatricules[10], 'valider' => 2, 'valider_rhp' => 0, 'type_medecin' => 'psycho'],

        // 1 patient validé avec statut 2 et validé RHP (valider = 2, valider_rhp = 1)
        ['matricule' => $existingMatricules[11], 'valider' => 2, 'valider_rhp' => 1, 'type_medecin' => 'médecin générale'],
    ];

    echo "Suppression des anciennes données de test...\n";
    DB::table('patients')->whereIn('matricule', $existingMatricules)->delete();

    echo "Insertion de nouvelles données de test...\n";
    foreach ($testData as $patient) {
        DB::table('patients')->insert([
            'matricule' => $patient['matricule'],
            'valider' => $patient['valider'],
            'valider_rhp' => $patient['valider_rhp'],
            'type_medecin' => $patient['type_medecin'],
            'avis_medecin' => 'Test patient',
            'created_at' => $today . ' 10:00:00',
            'updated_at' => $today . ' 10:00:00',
        ]);
    }

    echo "Données de test créées avec succès !\n\n";

    // Vérifier les statistiques attendues
    echo "=== Statistiques attendues ===\n";
    echo "Total tous patients (valider 0,1,2): " . count($testData) . "\n";
    echo "Patients validés RHP (valider 1,2 + valider_rhp=1): 3\n";
    echo "Patients en attente RHP (valider 1,2 + valider_rhp=0): 6\n";
    echo "Patients non validés médecin (valider=0): 3\n\n";

    // Vérifier en base
    echo "=== Vérification en base ===\n";
    $totalAll = DB::table('patients')
        ->whereIn('valider', [0, 1, 2])
        ->whereDate('created_at', '=', $today)
        ->count();

    $validatedRhp = DB::table('patients')
        ->whereIn('valider', [1, 2])
        ->where('valider_rhp', 1)
        ->whereDate('created_at', '=', $today)
        ->count();

    $pendingRhp = DB::table('patients')
        ->whereIn('valider', [1, 2])
        ->where('valider_rhp', 0)
        ->whereDate('created_at', '=', $today)
        ->count();

    echo "Total réel en base: $totalAll\n";
    echo "Validés RHP réel: $validatedRhp\n";
    echo "En attente RHP réel: $pendingRhp\n";

    echo "\n=== Données de test prêtes ===\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
