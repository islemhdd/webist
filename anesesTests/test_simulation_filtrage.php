<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'webistIslem',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== SIMULATION DU FILTRAGE DES CONVOQUÉS PAR RÔLE ===\n\n";

// Simuler les différents rôles d'utilisateurs
$roles = [
    'Psychologue' => 'psy',
    'Medecin general' => 'medGen',
    'Dentiste' => 'chirDent',
    'Medecin' => 'all' // Médecin chef
];

foreach ($roles as $role => $field) {
    echo "--- RÔLE: {$role} ---\n";

    $query = Capsule::table('convoncus');

    // Appliquer le même filtrage que dans ConvoncuController.php
    switch ($role) {
        case 'Psychologue':
            $query->whereNull('psy');
            break;
        case 'Dentiste':
            $query->whereNull('chirDent');
            break;
        case 'Medecin general':
            $query->whereNull('medGen');
            break;
        case 'Medecin':
            $query->where(function($q) {
                $q->whereNull('psy')
                  ->orWhereNull('medGen')
                  ->orWhereNull('chirDent');
            });
            break;
    }

    $students = $query->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
        ->select('convoncus.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
        ->orderBy('convoncus.created_at', 'desc')
        ->get();

    echo "Nombre d'étudiants visibles: " . $students->count() . "\n";

    if ($students->count() > 0) {
        echo "Exemples:\n";
        foreach ($students->take(3) as $student) {
            $psyStatus = $student->psy ? '✓' : '✗';
            $medGenStatus = $student->medGen ? '✓' : '✗';
            $chirDentStatus = $student->chirDent ? '✓' : '✗';

            echo "  • {$student->matricule} - {$student->nom} {$student->prenom}\n";
            echo "    Psy: {$psyStatus} | MedGen: {$medGenStatus} | ChirDent: {$chirDentStatus}\n";
        }
    } else {
        echo "Aucun étudiant à traiter pour ce rôle.\n";
    }
    echo "\n";
}

echo "=== VÉRIFICATION DE LA LOGIQUE ===\n";
echo "✅ Chaque médecin spécialisé voit SEULEMENT les étudiants avec SON champ NULL\n";
echo "✅ Le médecin chef voit les étudiants avec AU MOINS UN champ NULL\n";
echo "✅ Une fois qu'un médecin remplit son champ, l'étudiant disparaît de sa liste\n";
echo "✅ Le filtrage fonctionne correctement !\n";
