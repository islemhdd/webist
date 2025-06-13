<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 ANALYSE DE LA STRUCTURE UTILISATEURS ACTUELLE\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // 1. Analyser la table users en détail
    echo "👤 TABLE: users (détaillée)\n";
    echo "-" . str_repeat("-", 30) . "\n";

    $usersColumns = Schema::getColumnListing('users');
    echo "Colonnes: " . implode(', ', $usersColumns) . "\n\n";

    // Récupérer tous les utilisateurs avec leurs rôles
    $users = DB::table('users')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.name as role_name')
        ->get();

    echo "Utilisateurs existants:\n";
    foreach ($users as $user) {
        echo "  - ID: {$user->id}\n";
        echo "    Username: {$user->username}\n";
        echo "    Rôle: " . ($user->role_name ?? 'Aucun') . " (role_id: {$user->role_id})\n";
        echo "    Phone: " . ($user->phone ?? 'N/A') . "\n";
        echo "    Bat: " . ($user->bat ?? 'N/A') . "\n";
        echo "    Créé le: {$user->created_at}\n\n";
    }

    // 2. Analyser la table patients
    echo "🏥 TABLE: patients (détaillée)\n";
    echo "-" . str_repeat("-", 30) . "\n";

    if (Schema::hasTable('patients')) {
        $patientsColumns = Schema::getColumnListing('patients');
        echo "Colonnes: " . implode(', ', $patientsColumns) . "\n";

        // Quelques exemples de patients
        $samplePatients = DB::table('patients')
            ->limit(5)
            ->get();

        echo "\nExemples de patients:\n";
        foreach ($samplePatients as $patient) {
            echo "  - ID: {$patient->id}\n";
            foreach ($patientsColumns as $column) {
                if (isset($patient->$column) && $patient->$column !== null) {
                    echo "    {$column}: {$patient->$column}\n";
                }
            }
            echo "\n";
        }
    }

    // 3. Vérifier si la table appointments existe
    echo "📅 TABLE: appointments\n";
    echo "-" . str_repeat("-", 20) . "\n";

    if (Schema::hasTable('appointments')) {
        $appointmentsColumns = Schema::getColumnListing('appointments');
        echo "Colonnes: " . implode(', ', $appointmentsColumns) . "\n";
    } else {
        echo "❌ Table appointments n'existe pas - Création nécessaire\n";
    }

    // 4. Analyser les routes web pour comprendre la structure de l'infirmerie
    echo "\n🔗 ANALYSE DES ROUTES INFIRMERIE\n";
    echo "-" . str_repeat("-", 35) . "\n";

    $webRoutesPath = base_path('routes/web.php');
    if (file_exists($webRoutesPath)) {
        $routesContent = file_get_contents($webRoutesPath);

        // Chercher les routes liées à l'infirmerie
        if (strpos($routesContent, 'infirmerie') !== false) {
            echo "✅ Routes infirmerie trouvées dans web.php\n";

            // Extraire les lignes contenant 'infirmerie'
            $lines = explode("\n", $routesContent);
            $infirmerieLines = array_filter($lines, function($line) {
                return stripos($line, 'infirmerie') !== false;
            });

            echo "Routes infirmerie:\n";
            foreach ($infirmerieLines as $line) {
                echo "  " . trim($line) . "\n";
            }
        } else {
            echo "❌ Aucune route infirmerie trouvée\n";
        }
    }

    // 5. Lister toutes les tables pour voir ce qui existe
    echo "\n📊 TOUTES LES TABLES\n";
    echo "-" . str_repeat("-", 20) . "\n";

    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::getDatabaseName();
    $tableKey = "Tables_in_$databaseName";

    foreach ($tables as $table) {
        $tableName = $table->$tableKey;
        echo "  - $tableName\n";

        // Pour les tables importantes, afficher le nombre d'enregistrements
        if (in_array($tableName, ['users', 'patients', 'students', 'roles'])) {
            $count = DB::table($tableName)->count();
            echo "    (Enregistrements: $count)\n";
        }
    }

    echo "\n🎯 RECOMMANDATIONS POUR LA CRÉATION DES COMPTES MÉDICAUX\n";
    echo "=" . str_repeat("=", 60) . "\n";

    echo "1. 📋 Créer les nouveaux rôles: Psychologue, Dentiste, Médecin général\n";
    echo "2. 👤 Créer les utilisateurs avec ces rôles dans la table users\n";

    if (!in_array('type_medecin', Schema::getColumnListing('patients'))) {
        echo "3. 🔧 Ajouter la colonne 'type_medecin' à la table patients\n";
    } else {
        echo "3. ✅ La colonne 'type_medecin' existe déjà\n";
    }

    if (!Schema::hasTable('appointments')) {
        echo "4. 📅 Créer la table appointments pour les rendez-vous\n";
    } else {
        echo "4. ✅ La table appointments existe déjà\n";
    }

    echo "5. 🎨 Modifier la sidebar pour chaque type de médecin\n";
    echo "6. 🔧 Adapter les contrôleurs pour filtrer par spécialité\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
