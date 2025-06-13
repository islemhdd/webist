<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Toutes les tables de la base de données ===\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "- $tableName\n";
}

echo "\n=== Recherche de tables contenant 'nom' et 'prenom' ===\n";
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    try {
        $columns = DB::select("DESCRIBE $tableName");
        $hasNom = false;
        $hasPrenom = false;
        $hasMatricule = false;

        foreach ($columns as $column) {
            if ($column->Field === 'nom') $hasNom = true;
            if ($column->Field === 'prenom') $hasPrenom = true;
            if ($column->Field === 'matricule') $hasMatricule = true;
        }

        if ($hasNom && $hasPrenom && $hasMatricule) {
            echo "✅ Table '$tableName' contient nom, prenom, matricule\n";

            // Afficher la structure
            echo "   Colonnes:\n";
            foreach ($columns as $column) {
                echo "   - {$column->Field} ({$column->Type})\n";
            }
            echo "\n";
        }
    } catch (Exception $e) {
        // Ignorer les erreurs de tables
    }
}
