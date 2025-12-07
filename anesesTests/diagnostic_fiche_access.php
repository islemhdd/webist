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

echo "=== DIAGNOSTIC DU PROBLÈME D'ACCÈS À LA FICHE ===\n\n";

try {
    // 1. Vérifier tous les utilisateurs médicaux et leurs rôles
    echo "1. UTILISATEURS MÉDICAUX ET LEURS RÔLES:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $medicalUsers = Capsule::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Medecin general'])
        ->select('users.id', 'users.username', 'users.nom', 'users.prenom', 'roles.name as role_name')
        ->get();

    foreach ($medicalUsers as $user) {
        echo "   {$user->username} ({$user->nom} {$user->prenom}) → Rôle: '{$user->role_name}'\n";
    }

    echo "\n2. RECHERCHE SPÉCIFIQUE DE 'Dr. Généraliste':\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $drGeneraliste = Capsule::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.username', 'like', '%Généraliste%')
        ->orWhere('users.nom', 'like', '%Généraliste%')
        ->orWhere('users.prenom', 'like', '%Généraliste%')
        ->select('users.id', 'users.username', 'users.nom', 'users.prenom', 'roles.name as role_name')
        ->get();

    if ($drGeneraliste->count() > 0) {
        foreach ($drGeneraliste as $user) {
            echo "   TROUVÉ: {$user->username} ({$user->nom} {$user->prenom}) → Rôle: '{$user->role_name}'\n";

            // Vérifier si le rôle correspond exactement à ce qui est attendu dans FicheController
            $expectedRoles = ['Psychologue', 'Dentiste', 'Medecin general', 'Medecin'];
            if (in_array($user->role_name, $expectedRoles)) {
                echo "   ✅ Rôle '{$user->role_name}' AUTORISÉ dans FicheController\n";
            } else {
                echo "   ❌ Rôle '{$user->role_name}' NON AUTORISÉ dans FicheController\n";
                echo "   💡 Rôles autorisés: " . implode(', ', $expectedRoles) . "\n";
            }
        }
    } else {
        echo "   ❌ Aucun utilisateur 'Dr. Généraliste' trouvé\n";
        echo "   🔍 Recherche tous les utilisateurs contenant 'general'...\n";

        $generalUsers = Capsule::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.username', 'like', '%general%')
            ->orWhere('users.nom', 'like', '%general%')
            ->orWhere('users.prenom', 'like', '%general%')
            ->orWhere('roles.name', 'like', '%general%')
            ->select('users.id', 'users.username', 'users.nom', 'users.prenom', 'roles.name as role_name')
            ->get();

        foreach ($generalUsers as $user) {
            echo "      {$user->username} ({$user->nom} {$user->prenom}) → Rôle: '{$user->role_name}'\n";
        }
    }

    echo "\n3. VÉRIFICATION DES RÔLES DANS LA TABLE roles:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $allRoles = Capsule::table('roles')
        ->select('id', 'name')
        ->orderBy('name')
        ->get();

    echo "   Tous les rôles disponibles:\n";
    foreach ($allRoles as $role) {
        echo "   - ID: {$role->id} | Nom: '{$role->name}'\n";
    }

    echo "\n4. COMPARAISON AVEC FicheController:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $ficheControllerRoles = ['Psychologue', 'Dentiste', 'Medecin general', 'Medecin'];
    echo "   Rôles autorisés dans FicheController:\n";
    foreach ($ficheControllerRoles as $role) {
        $exists = $allRoles->firstWhere('name', $role);
        $status = $exists ? '✅' : '❌';
        echo "   {$status} '{$role}'" . ($exists ? " (ID: {$exists->id})" : " (INEXISTANT)") . "\n";
    }

    echo "\n5. DIAGNOSTIC ET SOLUTION:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $problemFound = false;

    // Vérifier s'il y a des rôles avec des variantes d'écriture
    $medGenVariants = $allRoles->filter(function($role) {
        return stripos($role->name, 'medecin') !== false && stripos($role->name, 'general') !== false;
    });

    if ($medGenVariants->count() > 0) {
        echo "   🔍 VARIANTES TROUVÉES pour 'Medecin general':\n";
        foreach ($medGenVariants as $variant) {
            echo "      - '{$variant->name}' (ID: {$variant->id})\n";
            if ($variant->name !== 'Medecin general') {
                echo "   ❌ PROBLÈME: Le rôle '{$variant->name}' ne correspond pas exactement à 'Medecin general'\n";
                echo "   💡 SOLUTION: Modifier FicheController pour accepter '{$variant->name}'\n";
                $problemFound = true;
            }
        }
    }

    if (!$problemFound) {
        echo "   ✅ Pas de problème de rôle détecté\n";
        echo "   🔍 Le problème pourrait être ailleurs (middleware, authentification, etc.)\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
