<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

// Configuration de la base de données
$capsule = new DB;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'webist_islem',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    echo "=== Structure de la table patients ===\n";

    // Obtenir la structure de la table patients
    $columns = DB::select("DESCRIBE patients");

    echo "Colonnes de la table patients:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) - Null: {$column->Null}, Default: {$column->Default}\n";
    }

    // Vérifier spécifiquement si valider_rhp existe
    echo "\n=== Vérification du champ valider_rhp ===\n";
    $hasValiderRhp = false;
    foreach ($columns as $column) {
        if ($column->Field === 'valider_rhp') {
            $hasValiderRhp = true;
            echo "✓ Le champ 'valider_rhp' existe: {$column->Type}, Null: {$column->Null}, Default: {$column->Default}\n";
            break;
        }
    }

    if (!$hasValiderRhp) {
        echo "✗ Le champ 'valider_rhp' n'existe PAS dans la table patients\n";
        echo "Il faut l'ajouter à la table.\n";
    }

    // Compter les enregistrements pour chaque valeur de valider
    echo "\n=== Statistiques des patients par statut 'valider' ===\n";
    $stats = DB::table('patients')
        ->select('valider', DB::raw('count(*) as count'))
        ->groupBy('valider')
        ->get();

    foreach ($stats as $stat) {
        echo "valider = {$stat->valider}: {$stat->count} patients\n";
    }

    // Si valider_rhp existe, montrer les statistiques
    if ($hasValiderRhp) {
        echo "\n=== Statistiques des patients par statut 'valider_rhp' ===\n";
        $rhpStats = DB::table('patients')
            ->select('valider_rhp', DB::raw('count(*) as count'))
            ->groupBy('valider_rhp')
            ->get();

        foreach ($rhpStats as $stat) {
            echo "valider_rhp = {$stat->valider_rhp}: {$stat->count} patients\n";
        }
    }

    echo "\n=== Test terminé avec succès ===\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
