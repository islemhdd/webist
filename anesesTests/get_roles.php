<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RÔLES EXISTANTS DANS LA BASE ===\n";

try {
    $roles = \App\Models\Role::all();

    if ($roles->count() > 0) {
        foreach ($roles as $role) {
            echo "ID: {$role->id} - Nom: {$role->name}\n";
        }
    } else {
        echo "Aucun rôle trouvé dans la base de données.\n";
    }

    echo "\n=== STRUCTURE DE LA TABLE REPORTS ===\n";

    // Examiner la structure de la table reports
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('reports');

    foreach ($columns as $column) {
        echo "Colonne: $column\n";
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
