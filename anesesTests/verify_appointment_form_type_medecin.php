<?php
// Vérification complète du système de création de rendez-vous

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION SYSTÈME CRÉATION RENDEZ-VOUS ===\n\n";

try {
    // 1. Vérifier la structure ENUM de type_medecin
    echo "1. Vérification de la colonne type_medecin...\n";

    $columnInfo = DB::select("SHOW COLUMNS FROM liste_rdvs WHERE Field = 'type_medecin'");
    if (!empty($columnInfo)) {
        $enumType = $columnInfo[0]->Type;
        echo "✅ Colonne trouvée: $enumType\n";

        // Extraire les valeurs ENUM
        preg_match_all("/'([^']*)'/", $enumType, $matches);
        $enumValues = $matches[1];

        echo "📋 Valeurs ENUM autorisées:\n";
        foreach ($enumValues as $value) {
            echo "   - '$value'\n";
        }
    } else {
        echo "❌ Colonne type_medecin non trouvée!\n";
        exit(1);
    }

    // 2. Vérifier le mapping des rôles dans MedicalSpecialtyController
    echo "\n2. Vérification du mapping des rôles...\n";

    $controllerFile = app_path('Http/Controllers/MedicalSpecialtyController.php');
    $content = file_get_contents($controllerFile);

    // Chercher le mapping dans createAppointment
    preg_match('/\$typeMedecin = match\(\$userRole\) \{(.*?)\};/s', $content, $matches);
    if (!empty($matches[1])) {
        echo "✅ Mapping trouvé dans createAppointment:\n";
        $mappingLines = explode("\n", trim($matches[1]));
        foreach ($mappingLines as $line) {
            $line = trim($line);
            if ($line && !str_contains($line, 'default')) {
                echo "   $line\n";
            }
        }
    }

    // 3. Vérifier les rôles utilisateur disponibles
    echo "\n3. Vérification des rôles utilisateur médicaux...\n";

    $medicalRoles = DB::table('roles')
        ->whereIn('name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
        ->get();

    echo "Rôles médicaux dans la base:\n";
    foreach ($medicalRoles as $role) {
        echo "   - '{$role->name}' (ID: {$role->id})\n";
    }

    // 4. Test du mapping avec les rôles réels
    echo "\n4. Test du mapping role → type_medecin...\n";

    $roleMapping = [
        'Psychologue' => 'psycho',
        'Dentiste' => 'dentiste',
        'Médecin général' => 'médecin générale',
        'Medecin' => 'chef_médecin'  // Note: ceci pourrait être le problème
    ];

    foreach ($roleMapping as $role => $expectedType) {
        $roleExists = $medicalRoles->where('name', $role)->first();
        $typeInEnum = in_array($expectedType, $enumValues);

        $status = $roleExists && $typeInEnum ? '✅' : '❌';
        echo "$status $role → '$expectedType' ";

        if (!$roleExists) echo "(Rôle manquant) ";
        if (!$typeInEnum) echo "(Type non dans ENUM) ";
        echo "\n";
    }

    // 5. Vérifier le mapping dans le contrôleur vs les valeurs ENUM
    echo "\n5. Problèmes détectés:\n";

    // Problème potentiel 1: 'psychologue' vs 'Psychologue'
    if (str_contains($content, "'psychologue' =>")) {
        echo "❌ PROBLÈME: Le mapping utilise 'psychologue' (minuscule) au lieu de 'Psychologue'\n";
    }

    // Problème potentiel 2: 'chef_médecin' qui n'existe pas dans l'ENUM
    if (!in_array('chef_médecin', $enumValues)) {
        echo "❌ PROBLÈME: 'chef_médecin' n'existe pas dans l'ENUM\n";
        echo "   Valeurs ENUM disponibles: " . implode(', ', $enumValues) . "\n";
    }

    // 6. Test avec des utilisateurs réels
    echo "\n6. Test avec utilisateurs médicaux existants...\n";

    $medicalUsers = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
        ->select('users.username', 'roles.name as role_name')
        ->limit(5)
        ->get();

    foreach ($medicalUsers as $user) {
        $mappedType = match($user->role_name) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'médecin générale', // Correction suggérée
            default => 'médecin générale'
        };

        $validType = in_array($mappedType, $enumValues);
        $status = $validType ? '✅' : '❌';

        echo "$status User: {$user->username} ({$user->role_name}) → '$mappedType'\n";
    }

    // 7. Recommandations
    echo "\n7. RECOMMANDATIONS:\n";
    echo "✅ Mapping correct suggéré:\n";
    echo "   'Psychologue' => 'psycho'\n";
    echo "   'Dentiste' => 'dentiste'\n";
    echo "   'Médecin général' => 'médecin générale'\n";
    echo "   'Medecin' => 'médecin générale' (ou créer 'chef_médecin' dans ENUM)\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
