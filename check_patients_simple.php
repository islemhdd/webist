<?php

// Script simple pour vérifier la structure de la table patients
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Vérification de la structure de la table patients ===\n\n";

    // Obtenir la structure de la table patients
    $columns = DB::select("DESCRIBE patients");

    echo "Colonnes de la table patients:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }

    // Vérifier spécifiquement si valider_rhp existe
    echo "\n=== Vérification du champ valider_rhp ===\n";
    $hasValiderRhp = false;
    foreach ($columns as $column) {
        if ($column->Field === 'valider_rhp') {
            $hasValiderRhp = true;
            echo "✓ Le champ 'valider_rhp' existe\n";
            break;
        }
    }

    if (!$hasValiderRhp) {
        echo "✗ Le champ 'valider_rhp' n'existe PAS\n";
    }

    // Statistiques des patients
    echo "\n=== Statistiques des patients ===\n";
    $total = DB::table('patients')->count();
    echo "Total des patients: $total\n";

    if ($hasValiderRhp) {
        $validatedRhp = DB::table('patients')->where('valider_rhp', 1)->count();
        $pendingRhp = DB::table('patients')->where('valider_rhp', 0)->count();
        echo "Patients validés RHP: $validatedRhp\n";
        echo "Patients en attente RHP: $pendingRhp\n";
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
