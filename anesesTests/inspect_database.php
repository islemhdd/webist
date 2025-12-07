<?php
// Définir le chemin racine de Laravel
define('LARAVEL_START', microtime(true));

// Charger l'autoloader de Composer
require __DIR__.'/vendor/autoload.php';

// Bootstrapper l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Créer un kernel pour la console
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== INSPECTION DE LA STRUCTURE DE LA BASE DE DONNÉES ===\n\n";

// Vérifier la structure de la table patients
echo "1. Structure de la table 'patients':\n";
try {
    $columns = \DB::select('SHOW COLUMNS FROM patients');
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n2. Échantillon de données de la table 'patients':\n";
try {
    $sample = \DB::table('patients')->limit(3)->get();
    if ($sample->count() > 0) {
        $first = $sample->first();
        echo "   Colonnes disponibles: " . implode(', ', array_keys((array)$first)) . "\n";
        foreach ($sample as $i => $patient) {
            echo "   Patient " . ($i+1) . ": " . json_encode($patient, JSON_PRETTY_PRINT) . "\n";
        }
    } else {
        echo "   Aucun patient trouvé dans la table.\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n3. Structure de la table 'students':\n";
try {
    $columns = \DB::select('SHOW COLUMNS FROM students');
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n4. Échantillon de données de la table 'students':\n";
try {
    $sample = \DB::table('students')->limit(3)->get();
    foreach ($sample as $i => $student) {
        echo "   Étudiant " . ($i+1) . ": matricule={$student->matricule}, grade={$student->grade}\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE L'INSPECTION ===\n";
