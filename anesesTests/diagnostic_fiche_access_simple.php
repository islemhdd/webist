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

echo "=== VÉRIFICATION STRUCTURE TABLE USERS ===\n\n";

try {
    // 1. Vérifier la structure de la table users
    echo "1. STRUCTURE DE LA TABLE USERS:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $columns = Capsule::select("DESCRIBE users");
    foreach ($columns as $column) {
        echo "   {$column->Field} ({$column->Type}) - {$column->Null} - {$column->Key}\n";
    }

    echo "\n2. RECHERCHE DES UTILISATEURS MÉDICAUX:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Utiliser seulement les colonnes qui existent
    $medicalUsers = Capsule::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Medecin general'])
        ->select('users.id', 'users.username', 'roles.name as role_name')
        ->get();

    foreach ($medicalUsers as $user) {
        echo "   {$user->username} → Rôle: '{$user->role_name}'\n";
    }

    echo "\n3. RECHERCHE SPÉCIFIQUE DE 'GÉNÉRALISTE':\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $drGeneraliste = Capsule::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.username', 'like', '%Généraliste%')
        ->select('users.id', 'users.username', 'roles.name as role_name')
        ->get();

    if ($drGeneraliste->count() > 0) {
        foreach ($drGeneraliste as $user) {
            echo "   TROUVÉ: {$user->username} → Rôle: '{$user->role_name}'\n";

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
        echo "   ❌ Aucun utilisateur 'Généraliste' trouvé avec ce terme exact\n";
        echo "   🔍 Recherche tous les utilisateurs contenant 'general'...\n";

        $generalUsers = Capsule::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where(function($query) {
                $query->where('users.username', 'like', '%general%')
                      ->orWhere('roles.name', 'like', '%general%');
            })
            ->select('users.id', 'users.username', 'roles.name as role_name')
            ->get();

        foreach ($generalUsers as $user) {
            echo "      {$user->username} → Rôle: '{$user->role_name}'\n";
        }
    }

    echo "\n4. TOUS LES RÔLES DISPONIBLES:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $allRoles = Capsule::table('roles')
        ->select('id', 'name')
        ->orderBy('name')
        ->get();

    foreach ($allRoles as $role) {
        echo "   - ID: {$role->id} | Nom: '{$role->name}'\n";
    }

    echo "\n5. DIAGNOSTIC FINAL:\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Chercher les variations de "Medecin general"
    $medGenVariants = $allRoles->filter(function($role) {
        return stripos($role->name, 'medecin') !== false || stripos($role->name, 'general') !== false;
    });

    if ($medGenVariants->count() > 0) {
        echo "   Rôles liés à 'medecin' ou 'general':\n";
        foreach ($medGenVariants as $variant) {
            echo "      - '{$variant->name}' (ID: {$variant->id})\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
