<?php

// Script pour tester les nouvelles statistiques
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Test des statistiques du tableau de bord ===\n\n";

    $today = now()->toDateString();
    echo "Date d'aujourd'hui: $today\n\n";

    // Statistiques par statut 'valider'
    echo "=== Répartition par statut 'valider' ===\n";
    $validerStats = DB::table('patients')
        ->select('valider', DB::raw('count(*) as count'))
        ->groupBy('valider')
        ->get();

    foreach ($validerStats as $stat) {
        echo "valider = {$stat->valider}: {$stat->count} patients\n";
    }

    // Test de la nouvelle logique des statistiques
    echo "\n=== Test de la nouvelle logique (tous les patients) ===\n";

    // Total de TOUS les patients (valider = 0, 1, 2) pour aujourd'hui
    $totalAll = DB::table('patients')
        ->whereIn('valider', [0, 1, 2])
        ->whereDate('created_at', '=', $today)
        ->count();

    // Patients validés RHP (seulement ceux avec valider = 1 ou 2)
    $validatedRhp = DB::table('patients')
        ->whereIn('valider', [1, 2])
        ->where('valider_rhp', 1)
        ->whereDate('created_at', '=', $today)
        ->count();

    // Patients en attente RHP (seulement ceux avec valider = 1 ou 2)
    $pendingRhp = DB::table('patients')
        ->whereIn('valider', [1, 2])
        ->where('valider_rhp', 0)
        ->whereDate('created_at', '=', $today)
        ->count();

    echo "Total tous patients (0,1,2) aujourd'hui: $totalAll\n";
    echo "Validés RHP (1,2 + valider_rhp=1) aujourd'hui: $validatedRhp\n";
    echo "En attente RHP (1,2 + valider_rhp=0) aujourd'hui: $pendingRhp\n";

    // Vérification détaillée pour aujourd'hui
    echo "\n=== Détail par statut pour aujourd'hui ===\n";
    $todayStats = DB::table('patients')
        ->select('valider', 'valider_rhp', DB::raw('count(*) as count'))
        ->whereDate('created_at', '=', $today)
        ->groupBy('valider', 'valider_rhp')
        ->get();

    foreach ($todayStats as $stat) {
        echo "valider={$stat->valider}, valider_rhp={$stat->valider_rhp}: {$stat->count} patients\n";
    }

    echo "\n=== Test terminé ===\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
