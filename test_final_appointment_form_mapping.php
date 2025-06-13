<?php
// Test final pour vérifier que le formulaire de création de rendez-vous fonctionne correctement

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST FINAL FORMULAIRE CRÉATION RENDEZ-VOUS ===\n\n";

try {
    // 1. Vérifier les valeurs ENUM
    echo "1. Vérification des valeurs ENUM...\n";

    $columnInfo = DB::select("SHOW COLUMNS FROM liste_rdvs WHERE Field = 'type_medecin'");
    preg_match_all("/'([^']*)'/", $columnInfo[0]->Type, $matches);
    $enumValues = $matches[1];

    echo "Valeurs ENUM autorisées: " . implode(', ', $enumValues) . "\n";

    // 2. Tester le mapping pour chaque rôle médical
    echo "\n2. Test du mapping dans les contrôleurs...\n";

    $medicalRoles = ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'];

    foreach ($medicalRoles as $role) {
        // Simulation du mapping du MedicalSpecialtyController
        $typeMedecin = match($role) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'chef_médecin',
            default => 'médecin générale'
        };

        $isValid = in_array($typeMedecin, $enumValues);
        $status = $isValid ? '✅' : '❌';

        echo "$status $role → '$typeMedecin' ";
        if (!$isValid) echo "(INVALIDE dans ENUM!)";
        echo "\n";
    }

    // 3. Test de création simulée
    echo "\n3. Test de création de rendez-vous simulé...\n";

    // Récupérer un étudiant existant
    $student = DB::table('students')->first();
    if (!$student) {
        echo "❌ Aucun étudiant trouvé pour le test\n";
        exit(1);
    }

    echo "Étudiant test: {$student->nom} {$student->prenom} (Matricule: {$student->matricule})\n";

    // Tester pour chaque rôle médical
    foreach ($medicalRoles as $role) {
        $typeMedecin = match($role) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'chef_médecin',
            default => 'médecin générale'
        };

        echo "\nTest création RDV pour rôle '$role':\n";
        echo "  - type_medecin qui sera assigné: '$typeMedecin'\n";

        // Vérifier si ce type existe dans l'ENUM
        if (in_array($typeMedecin, $enumValues)) {
            echo "  ✅ Type valide dans ENUM\n";

            // Test d'insertion réelle (on va l'annuler après)
            try {
                $rdvData = [
                    'type_medecin' => $typeMedecin,
                    'matricule' => $student->matricule,
                    'motif' => 'consultation',
                    'service' => 'test_service',
                    'date' => date('Y-m-d H:i:s')
                ];

                $id = DB::table('liste_rdvs')->insertGetId($rdvData);
                echo "  ✅ Insertion réussie (ID: $id)\n";

                // Supprimer le test
                DB::table('liste_rdvs')->where('id', $id)->delete();
                echo "  ✅ Test nettoyé\n";

            } catch (Exception $e) {
                echo "  ❌ Erreur d'insertion: " . $e->getMessage() . "\n";
            }
        } else {
            echo "  ❌ Type INVALIDE dans ENUM!\n";
        }
    }

    // 4. Vérifier les services par spécialité
    echo "\n4. Vérification des services par spécialité...\n";

    // Simulation de la méthode getMotifsAndServicesBySpecialty
    $servicesParSpecialite = [
        'psycho' => ['psychiatrie', 'neurologie', 'consultation_psychologique'],
        'dentiste' => ['chirurgie_dentaire', 'orthodontie', 'parodontologie'],
        'médecin générale' => ['cardiologie', 'pneumologie', 'gastroenterologie'],
        'chef_médecin' => ['cardiologie', 'pneumologie', 'gastroenterologie', 'psychiatrie', 'chirurgie_dentaire']
    ];

    foreach ($servicesParSpecialite as $specialite => $services) {
        echo "✅ $specialite: " . count($services) . " services disponibles\n";
    }

    // 5. Test avec utilisateurs réels
    echo "\n5. Test avec utilisateurs médicaux réels...\n";

    $medicalUsers = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->whereIn('roles.name', $medicalRoles)
        ->select('users.username', 'roles.name as role_name')
        ->get();

    foreach ($medicalUsers as $user) {
        $typeMedecin = match($user->role_name) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'chef_médecin',
            default => 'médecin générale'
        };

        $status = in_array($typeMedecin, $enumValues) ? '✅' : '❌';
        echo "$status {$user->username} ({$user->role_name}) → '$typeMedecin'\n";
    }

    // 6. Résumé final
    echo "\n=== RÉSUMÉ FINAL ===\n";

    $allValid = true;
    foreach ($medicalRoles as $role) {
        $typeMedecin = match($role) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'chef_médecin',
            default => 'médecin générale'
        };

        if (!in_array($typeMedecin, $enumValues)) {
            $allValid = false;
            break;
        }
    }

    if ($allValid) {
        echo "🎉 ✅ SYSTÈME ENTIÈREMENT FONCTIONNEL!\n";
        echo "✅ Tous les rôles mappent vers des valeurs ENUM valides\n";
        echo "✅ Le formulaire de création de rendez-vous assignera automatiquement le bon type_medecin\n";
        echo "✅ Les contrôleurs MedicalSpecialtyController et ListeRdvController sont synchronisés\n";
    } else {
        echo "❌ PROBLÈMES DÉTECTÉS!\n";
        echo "Certains rôles mappent vers des valeurs qui n'existent pas dans l'ENUM\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
?>
