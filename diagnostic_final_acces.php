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

echo "=== DIAGNOSTIC AVANCÉ - PROBLÈME FICHE MÉDICALE ===\n\n";

try {
    // 1. Vérifier l'utilisateur Dr. Généraliste exact
    echo "1. VÉRIFICATION DE L'UTILISATEUR 'Dr. Généraliste':\n";
    echo "-" . str_repeat("-", 60) . "\n";

    $drGeneraliste = Capsule::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.username', 'Dr. Généraliste')
        ->select('users.*', 'roles.name as role_name')
        ->first();

    if ($drGeneraliste) {
        echo "   ✅ UTILISATEUR TROUVÉ:\n";
        echo "      ID: {$drGeneraliste->id}\n";
        echo "      Username: '{$drGeneraliste->username}'\n";
        echo "      Role ID: {$drGeneraliste->role_id}\n";
        echo "      Role Name: '{$drGeneraliste->role_name}'\n";
        echo "      Password: " . substr($drGeneraliste->password, 0, 20) . "...\n";
    } else {
        echo "   ❌ UTILISATEUR NON TROUVÉ avec ce nom exact\n";

        // Chercher des variantes
        $variants = Capsule::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.username', 'like', '%Généraliste%')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        echo "   🔍 VARIANTES TROUVÉES:\n";
        foreach ($variants as $variant) {
            echo "      - '{$variant->username}' (Rôle: {$variant->role_name})\n";
        }
    }

    // 2. Test de la logique FicheController
    echo "\n2. TEST DE LA LOGIQUE FicheController:\n";
    echo "-" . str_repeat("-", 60) . "\n";

    if ($drGeneraliste) {
        $userRole = $drGeneraliste->role_name;
        echo "   Rôle à tester: '{$userRole}'\n";

        // Simuler la fonction getEditableFields du FicheController
        function getEditableFields($userRole) {
            switch ($userRole) {
                case 'Psychologue':
                    return ['psy'];
                case 'Dentiste':
                    return ['chirDent'];
                case 'Medecin general':
                case 'Médecin général':
                case 'Medecin generale':
                    return ['medGen'];
                case 'Medecin':
                case 'Medecin chef':
                    return ['avisSpe'];
                default:
                    return [];
            }
        }

        $editableFields = getEditableFields($userRole);

        if (!empty($editableFields)) {
            echo "   ✅ ACCÈS AUTORISÉ - Champs modifiables: [" . implode(', ', $editableFields) . "]\n";
        } else {
            echo "   ❌ ACCÈS REFUSÉ - Aucun champ modifiable\n";
            echo "   🔍 Le rôle '{$userRole}' n'est pas reconnu dans FicheController\n";
        }
    }

    // 3. Vérifier la fiche spécifique (matricule 2022250)
    echo "\n3. VÉRIFICATION DE LA FICHE SPÉCIFIQUE (2022250):\n";
    echo "-" . str_repeat("-", 60) . "\n";

    $student = Capsule::table('Students')
        ->where('matricule', '2022250')
        ->first();

    if ($student) {
        echo "   ✅ ÉTUDIANT TROUVÉ:\n";
        echo "      Matricule: {$student->matricule}\n";
        echo "      Nom: {$student->nom}\n";
        echo "      Prénom: {$student->prenom}\n";
        echo "      Section: {$student->section_id}\n";
    } else {
        echo "   ❌ ÉTUDIANT NON TROUVÉ avec matricule 2022250\n";
    }

    $convoncu = Capsule::table('convoncus')
        ->where('matricule', '2022250')
        ->first();

    if ($convoncu) {
        echo "   ✅ CONVOCATION TROUVÉE:\n";
        echo "      Psy: " . ($convoncu->psy ? "REMPLI" : "NULL") . "\n";
        echo "      MedGen: " . ($convoncu->medGen ? "REMPLI" : "NULL") . "\n";
        echo "      ChirDent: " . ($convoncu->chirDent ? "REMPLI" : "NULL") . "\n";
        echo "      AvisSpe: " . ($convoncu->avisSpe ? "REMPLI" : "NULL") . "\n";
    } else {
        echo "   ❌ CONVOCATION NON TROUVÉE pour matricule 2022250\n";
    }

    // 4. Test des routes
    echo "\n4. VÉRIFICATION DES ROUTES:\n";
    echo "-" . str_repeat("-", 60) . "\n";

    // Inclure Laravel pour vérifier les routes
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    $routes = \Route::getRoutes();
    $ficheRoutes = [];

    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'fiche')) {
            $ficheRoutes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
                'name' => $route->getName()
            ];
        }
    }

    echo "   Routes fiche trouvées:\n";
    foreach ($ficheRoutes as $route) {
        echo "      {$route['method']} /{$route['uri']} → {$route['action']} (name: {$route['name']})\n";
    }

    // 5. Test d'authentification simulé
    echo "\n5. SIMULATION D'AUTHENTIFICATION:\n";
    echo "-" . str_repeat("-", 60) . "\n";

    if ($drGeneraliste) {
        // Simuler la logique d'AuthController
        $userRole = $drGeneraliste->role_name;
        echo "   Rôle utilisateur: '{$userRole}'\n";

        $isMedical = in_array($userRole, ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général', 'Medecin general', 'Medecin generale']);

        if ($isMedical) {
            echo "   ✅ Utilisateur identifié comme médical\n";
            echo "   📍 Redirection attendue: route('medical.dashboard')\n";
        } else {
            echo "   ❌ Utilisateur NON identifié comme médical\n";
            echo "   🔍 Rôles médicaux reconnus: Medecin, Psychologue, Dentiste, Médecin général\n";
        }
    }

    // 6. Diagnostic final
    echo "\n6. DIAGNOSTIC FINAL:\n";
    echo "-" . str_repeat("-", 60) . "\n";

    if ($drGeneraliste) {
        $problems = [];

        // Vérifier chaque étape
        $editableFields = getEditableFields($drGeneraliste->role_name);
        if (empty($editableFields)) {
            $problems[] = "FicheController ne reconnaît pas le rôle '{$drGeneraliste->role_name}'";
        }

        $isMedical = in_array($drGeneraliste->role_name, ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général']);
        if (!$isMedical) {
            $problems[] = "AuthController ne reconnaît pas le rôle comme médical";
        }

        if (empty($problems)) {
            echo "   ✅ AUCUN PROBLÈME DÉTECTÉ - La correction devrait fonctionner\n";
            echo "   💡 Le problème pourrait être un cache navigateur ou une session active\n";
            echo "   🔧 SOLUTIONS:\n";
            echo "      1. Vider le cache du navigateur\n";
            echo "      2. Se déconnecter et se reconnecter\n";
            echo "      3. Essayer en navigation privée\n";
        } else {
            echo "   ❌ PROBLÈMES DÉTECTÉS:\n";
            foreach ($problems as $problem) {
                echo "      - {$problem}\n";
            }
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
