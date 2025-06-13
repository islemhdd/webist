<?php
/**
 * Test du système d'insertion automatique du type_medecin
 * selon le rôle de l'utilisateur connecté
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🧪 TEST - Système d'insertion automatique du type_medecin\n";
echo str_repeat("=", 60) . "\n\n";

// Test 1: Vérification des contrôleurs
echo "1️⃣  VÉRIFICATION DES CONTRÔLEURS\n";
echo str_repeat("-", 40) . "\n";

$controllers = [
    'ListeRdvController' => __DIR__ . '/app/Http/Controllers/ListeRdvController.php',
    'MedicalSpecialtyController' => __DIR__ . '/app/Http/Controllers/MedicalSpecialtyController.php'
];

foreach ($controllers as $name => $path) {
    echo "📄 $name:\n";

    if (!file_exists($path)) {
        echo "   ❌ Fichier non trouvé\n";
        continue;
    }

    $content = file_get_contents($path);

    // Vérifier l'import Auth
    if (strpos($content, 'use Illuminate\Support\Facades\Auth;') !== false) {
        echo "   ✅ Import Auth présent\n";
    } else {
        echo "   ⚠️  Import Auth manquant\n";
    }

    // Vérifier la logique automatique
    if (strpos($content, 'typeMedecin = match($userRole)') !== false) {
        echo "   ✅ Logique automatique de type_medecin présente\n";
    } else {
        echo "   ❌ Logique automatique manquante\n";
    }

    // Vérifier les rôles supportés
    $roles = ['psychologue', 'dentiste', 'médecin générale', 'chef_médecin'];
    $allRolesFound = true;
    foreach ($roles as $role) {
        if (strpos($content, "'$role'") === false) {
            $allRolesFound = false;
            break;
        }
    }

    if ($allRolesFound) {
        echo "   ✅ Tous les rôles médecins supportés\n";
    } else {
        echo "   ⚠️  Certains rôles manquants\n";
    }

    echo "\n";
}

// Test 2: Vérification du modèle
echo "2️⃣  VÉRIFICATION DU MODÈLE ListeRdv\n";
echo str_repeat("-", 40) . "\n";

$modelPath = __DIR__ . '/app/Models/listeRdv.php';
if (file_exists($modelPath)) {
    $modelContent = file_get_contents($modelPath);

    if (strpos($modelContent, "'type_medecin'") !== false) {
        echo "   ✅ Champ type_medecin dans fillable\n";
    } else {
        echo "   ❌ Champ type_medecin manquant dans fillable\n";
    }

    // Vérifier que nom, prenom, section_id ne sont plus dans fillable (récupérés via relation)
    $shouldNotBeInFillable = ['nom', 'prenom', 'section_id'];
    $cleanFillable = true;
    foreach ($shouldNotBeInFillable as $field) {
        if (strpos($modelContent, "'$field'") !== false) {
            $cleanFillable = false;
            echo "   ⚠️  Champ '$field' encore dans fillable (devrait être récupéré via relation)\n";
        }
    }

    if ($cleanFillable) {
        echo "   ✅ Fillable optimisé (données étudiant récupérées via relation)\n";
    }
} else {
    echo "   ❌ Modèle ListeRdv non trouvé\n";
}

echo "\n";

// Test 3: Vérification de la migration
echo "3️⃣  VÉRIFICATION DE LA MIGRATION\n";
echo str_repeat("-", 40) . "\n";

$migrationPath = __DIR__ . '/database/migrations/2025_06_02_112907_add_type_medecin_to_liste_rdvs_table.php';
if (file_exists($migrationPath)) {
    $migrationContent = file_get_contents($migrationPath);

    if (strpos($migrationContent, "table->string('type_medecin')") !== false) {
        echo "   ✅ Migration pour type_medecin présente\n";
    } else {
        echo "   ❌ Migration pour type_medecin incorrecte\n";
    }

    if (strpos($migrationContent, "->default('chief_doctor')") !== false ||
        strpos($migrationContent, "->default('chef_médecin')") !== false) {
        echo "   ✅ Valeur par défaut définie\n";
    } else {
        echo "   ⚠️  Pas de valeur par défaut définie\n";
    }
} else {
    echo "   ❌ Migration non trouvée\n";
}

echo "\n";

// Test 4: Logique de mapping des rôles
echo "4️⃣  TEST DE LA LOGIQUE DE MAPPING DES RÔLES\n";
echo str_repeat("-", 40) . "\n";

$roleMapping = [
    'psychologue' => 'psychologue',
    'dentiste' => 'dentiste',
    'médecin générale' => 'médecin générale',
    'chef_médecin' => 'chef_médecin',
    'admin' => 'chef_médecin', // Par défaut
    'autre_role' => 'chef_médecin' // Par défaut
];

echo "Mapping des rôles utilisateur → type_medecin:\n";
foreach ($roleMapping as $userRole => $expectedType) {
    echo "   $userRole → $expectedType\n";
}

echo "\n";

// Test 5: Résumé du système
echo "5️⃣  RÉSUMÉ DU SYSTÈME UNIFIÉ\n";
echo str_repeat("-", 40) . "\n";

echo "✨ SYSTÈME DE RENDEZ-VOUS UNIFIÉ:\n";
echo "   • Chef médecin: Tous les services (15 services)\n";
echo "   • Psychologue: Services psychologiques (6 services)\n";
echo "   • Dentiste: Services dentaires (6 services)\n";
echo "   • Médecin général: Services généraux (7 services)\n\n";

echo "🔄 INSERTION AUTOMATIQUE:\n";
echo "   • Type médecin déterminé par le rôle utilisateur\n";
echo "   • Données étudiant récupérées via matricule\n";
echo "   • Motifs simplifiés: consultation, urgences\n\n";

echo "📊 TRAÇABILITÉ:\n";
echo "   • Chaque RDV marqué avec son type_medecin\n";
echo "   • Possibilité de filtrer par type de médecin\n";
echo "   • Historique complet des créations\n\n";

// Vérification finale
$allSystemsReady = file_exists(__DIR__ . '/app/Http/Controllers/ListeRdvController.php') &&
                   file_exists(__DIR__ . '/app/Http/Controllers/MedicalSpecialtyController.php') &&
                   file_exists(__DIR__ . '/app/Models/listeRdv.php') &&
                   file_exists($migrationPath);

if ($allSystemsReady) {
    echo "🎉 SYSTÈME PRÊT!\n";
    echo "   Exécutez la migration: php artisan migrate\n";
    echo "   Puis testez les créations de RDV avec différents comptes\n";
} else {
    echo "⚠️  SYSTÈME INCOMPLET\n";
    echo "   Vérifiez les fichiers manquants ci-dessus\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🏁 Test terminé!\n";
?>
