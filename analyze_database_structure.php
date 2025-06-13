<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 ANALYSE COMPLÈTE DE LA STRUCTURE DE LA BASE DE DONNÉES\n";
echo "=" . str_repeat("=", 60) . "\n\n";

try {
    // 1. Analyser la table roles
    echo "📋 TABLE: roles\n";
    echo "-" . str_repeat("-", 20) . "\n";

    $rolesColumns = Schema::getColumnListing('roles');
    echo "Colonnes: " . implode(', ', $rolesColumns) . "\n";

    $roles = DB::table('roles')->get();
    echo "Rôles existants:\n";
    foreach ($roles as $role) {
        echo "  - ID: {$role->id}, Nom: {$role->name}\n";
    }

    // 2. Analyser la table users
    echo "\n👤 TABLE: users\n";
    echo "-" . str_repeat("-", 20) . "\n";

    $usersColumns = Schema::getColumnListing('users');
    echo "Colonnes: " . implode(', ', $usersColumns) . "\n";

    $usersCount = DB::table('users')->count();
    echo "Nombre d'utilisateurs: $usersCount\n";

    // 3. Analyser la table officers
    echo "\n👮 TABLE: officers\n";
    echo "-" . str_repeat("-", 20) . "\n";

    $officersColumns = Schema::getColumnListing('officers');
    echo "Colonnes: " . implode(', ', $officersColumns) . "\n";

    $officers = DB::table('officers')
        ->join('users', 'officers.user_id', '=', 'users.id')
        ->join('roles', 'officers.role_id', '=', 'roles.id')
        ->select('officers.*', 'users.name as user_name', 'users.email', 'roles.name as role_name')
        ->get();

    echo "Officers existants:\n";
    foreach ($officers as $officer) {
        echo "  - {$officer->user_name} ({$officer->email}) - Rôle: {$officer->role_name}\n";
    }

    // 4. Analyser la table patients
    echo "\n🏥 TABLE: patients\n";
    echo "-" . str_repeat("-", 20) . "\n";

    if (Schema::hasTable('patients')) {
        $patientsColumns = Schema::getColumnListing('patients');
        echo "Colonnes: " . implode(', ', $patientsColumns) . "\n";

        $patientsCount = DB::table('patients')->count();
        echo "Nombre de patients: $patientsCount\n";

        // Vérifier si la colonne type_medecin existe
        if (in_array('type_medecin', $patientsColumns)) {
            echo "✅ Colonne 'type_medecin' existe\n";
            $typesMedecin = DB::table('patients')
                ->select('type_medecin', DB::raw('count(*) as count'))
                ->groupBy('type_medecin')
                ->get();
            echo "Types de médecin existants:\n";
            foreach ($typesMedecin as $type) {
                echo "  - {$type->type_medecin}: {$type->count} patients\n";
            }
        } else {
            echo "❌ Colonne 'type_medecin' n'existe pas\n";
        }
    } else {
        echo "❌ Table patients n'existe pas\n";
    }

    // 5. Analyser la table appointments si elle existe
    echo "\n📅 TABLE: appointments\n";
    echo "-" . str_repeat("-", 20) . "\n";

    if (Schema::hasTable('appointments')) {
        $appointmentsColumns = Schema::getColumnListing('appointments');
        echo "Colonnes: " . implode(', ', $appointmentsColumns) . "\n";

        $appointmentsCount = DB::table('appointments')->count();
        echo "Nombre de rendez-vous: $appointmentsCount\n";
    } else {
        echo "❌ Table appointments n'existe pas\n";
    }

    // 6. Analyser la table reports
    echo "\n📄 TABLE: reports\n";
    echo "-" . str_repeat("-", 20) . "\n";

    if (Schema::hasTable('reports')) {
        $reportsColumns = Schema::getColumnListing('reports');
        echo "Colonnes: " . implode(', ', $reportsColumns) . "\n";

        // Vérifier les colonnes d'avis
        $avisColumns = array_filter($reportsColumns, function($column) {
            return strpos($column, 'Avis') === 0;
        });
        echo "Colonnes d'avis: " . implode(', ', $avisColumns) . "\n";
    }

    // 7. Analyser la table students
    echo "\n🎓 TABLE: students\n";
    echo "-" . str_repeat("-", 20) . "\n";

    if (Schema::hasTable('students')) {
        $studentsColumns = Schema::getColumnListing('students');
        echo "Colonnes: " . implode(', ', $studentsColumns) . "\n";

        $studentsCount = DB::table('students')->count();
        echo "Nombre d'étudiants: $studentsCount\n";

        // Analyser les grades
        $grades = DB::table('students')
            ->select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();
        echo "Répartition par grade:\n";
        foreach ($grades as $grade) {
            echo "  - Grade {$grade->grade}: {$grade->count} étudiants\n";
        }
    }

    // 8. Lister toutes les tables
    echo "\n📊 TOUTES LES TABLES DE LA BASE\n";
    echo "-" . str_repeat("-", 30) . "\n";

    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::getDatabaseName();
    $tableKey = "Tables_in_$databaseName";

    echo "Tables existantes:\n";
    foreach ($tables as $table) {
        echo "  - " . $table->$tableKey . "\n";
    }

    echo "\n🎯 ANALYSE TERMINÉE\n";
    echo "=" . str_repeat("=", 60) . "\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de l'analyse : " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "📁 Fichier: " . $e->getFile() . "\n";
}
