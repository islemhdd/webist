<?php
/**
 * Diagnostic complet de la colonne type_medecin et des valeurs de formulaires
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔍 DIAGNOSTIC - Colonne type_medecin et valeurs formulaires\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // 1. Vérifier la structure actuelle de la colonne type_medecin
    echo "1️⃣  STRUCTURE ACTUELLE DE LA COLONNE type_medecin\n";
    echo str_repeat("-", 50) . "\n";

    $columnInfo = \DB::select("SHOW COLUMNS FROM liste_rdvs WHERE Field = 'type_medecin'");
    if (!empty($columnInfo)) {
        $column = $columnInfo[0];
        echo "Type actuel: {$column->Type}\n";
        echo "Valeurs ENUM autorisées:\n";

        // Extraire les valeurs ENUM
        $enumValues = str_replace(['enum(', ')', "'"], '', $column->Type);
        $values = explode(',', $enumValues);
        foreach ($values as $value) {
            echo "   • '$value'\n";
        }
    } else {
        echo "❌ Colonne type_medecin non trouvée\n";
    }

    echo "\n";

    // 2. Vérifier les rôles d'utilisateurs médicaux
    echo "2️⃣  RÔLES D'UTILISATEURS MÉDICAUX\n";
    echo str_repeat("-", 50) . "\n";

    $medicalRoles = \DB::table('roles')
                      ->whereIn('name', ['psychologue', 'dentiste', 'médecin générale', 'chef_médecin'])
                      ->get();

    echo "Rôles médicaux trouvés:\n";
    foreach ($medicalRoles as $role) {
        echo "   • ID: {$role->id} - Nom: '{$role->name}'\n";

        // Compter les utilisateurs avec ce rôle
        $userCount = \DB::table('users')->where('role_id', $role->id)->count();
        echo "     Utilisateurs: $userCount\n";
    }

    echo "\n";

    // 3. Tester le mapping actuel dans les contrôleurs
    echo "3️⃣  TEST DU MAPPING DANS LES CONTRÔLEURS\n";
    echo str_repeat("-", 50) . "\n";

    $testRoles = ['psychologue', 'dentiste', 'médecin générale', 'chef_médecin'];

    foreach ($testRoles as $role) {
        $typeMedecin = match($role) {
            'psychologue' => 'psycho',
            'dentiste' => 'dentiste',
            'médecin générale' => 'médecin générale',
            'chef_médecin' => 'médecin générale',
            default => 'médecin générale'
        };

        echo "Rôle: '$role' → type_medecin: '$typeMedecin'\n";

        // Vérifier si cette valeur est dans l'ENUM
        $isValid = in_array($typeMedecin, $values);
        echo "   Valeur valide dans ENUM: " . ($isValid ? "✅ OUI" : "❌ NON") . "\n";
    }

    echo "\n";

    // 4. Vérifier les données existantes
    echo "4️⃣  DONNÉES EXISTANTES DANS liste_rdvs\n";
    echo str_repeat("-", 50) . "\n";

    $existingTypes = \DB::table('liste_rdvs')
                       ->select('type_medecin', \DB::raw('COUNT(*) as count'))
                       ->groupBy('type_medecin')
                       ->get();

    echo "Valeurs type_medecin dans la base:\n";
    foreach ($existingTypes as $type) {
        echo "   • '{$type->type_medecin}': {$type->count} rendez-vous\n";
    }

    echo "\n";

    // 5. Proposer les corrections nécessaires
    echo "5️⃣  CORRECTIONS NÉCESSAIRES\n";
    echo str_repeat("-", 50) . "\n";

    echo "🔧 PROBLÈMES IDENTIFIÉS:\n";

    // Vérifier si 'chef_médecin' est dans l'ENUM
    if (!in_array('chef_médecin', $values)) {
        echo "   ❌ 'chef_médecin' n'est pas dans l'ENUM\n";
        echo "      Solution: Ajouter 'chef_médecin' à l'ENUM ou mapper vers une valeur existante\n";
    }

    // Vérifier la cohérence des mappings
    $invalidMappings = [];
    foreach ($testRoles as $role) {
        $typeMedecin = match($role) {
            'psychologue' => 'psycho',
            'dentiste' => 'dentiste',
            'médecin générale' => 'médecin générale',
            'chef_médecin' => 'médecin générale',
            default => 'médecin générale'
        };

        if (!in_array($typeMedecin, $values)) {
            $invalidMappings[] = "$role → $typeMedecin";
        }
    }

    if (!empty($invalidMappings)) {
        echo "   ❌ Mappings invalides:\n";
        foreach ($invalidMappings as $mapping) {
            echo "      • $mapping\n";
        }
    }

    echo "\n✅ SOLUTIONS RECOMMANDÉES:\n";
    echo "   1. Modifier l'ENUM pour inclure toutes les valeurs nécessaires\n";
    echo "   2. Ou ajuster les mappings pour utiliser seulement les valeurs ENUM existantes\n";
    echo "   3. Tester les formulaires avec les nouvelles valeurs\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🏁 Diagnostic terminé!\n";
?>
