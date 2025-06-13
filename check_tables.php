<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Vérification des tables disponibles ===\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    if (strpos($tableName, 'etudiant') !== false || strpos($tableName, 'patient') !== false) {
        echo "Table trouvée: $tableName\n";
    }
}

echo "\n=== Structure de la table patients ===\n";
try {
    $columns = DB::select('DESCRIBE patients');
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Test de quelques enregistrements patients ===\n";
try {
    $patients = DB::table('patients')->limit(3)->get(['matricule', 'nom', 'prenom']);
    foreach ($patients as $patient) {
        echo "- Matricule: {$patient->matricule}, Nom: {$patient->nom} {$patient->prenom}\n";
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
