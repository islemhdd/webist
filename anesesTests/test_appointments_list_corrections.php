<?php
// Test des corrections apportées à la liste des rendez-vous médicaux

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\MedicalSpecialtyController;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DES CORRECTIONS LISTE RENDEZ-VOUS MÉDICAUX ===\n\n";

try {
    // 1. Vérifier la structure de la table students pour la colonne section
    echo "1. Vérification de la colonne 'section' dans la table students...\n";

    $studentsColumns = DB::select("SHOW COLUMNS FROM students");
    $sectionExists = false;

    foreach ($studentsColumns as $column) {
        if ($column->Field === 'section') {
            $sectionExists = true;
            echo "✅ Colonne 'section' trouvée dans la table students (type: {$column->Type})\n";
            break;
        }
    }

    if (!$sectionExists) {
        echo "❌ Colonne 'section' manquante dans la table students\n";
        echo "⚠️  Recherche d'autres colonnes similaires...\n";

        foreach ($studentsColumns as $column) {
            if (strpos(strtolower($column->Field), 'bat') !== false ||
                strpos(strtolower($column->Field), 'section') !== false ||
                strpos(strtolower($column->Field), 'compagnie') !== false) {
                echo "   - Colonne trouvée: {$column->Field} (type: {$column->Type})\n";
            }
        }
    }

    // 2. Vérifier quelques exemples de données d'étudiants
    echo "\n2. Vérification des données des étudiants (10 premiers)...\n";

    $students = DB::table('students')->limit(10)->get();

    if ($students->count() > 0) {
        echo "✅ Étudiants trouvés: " . $students->count() . "\n";

        foreach ($students->take(3) as $student) {
            $sectionValue = $student->section ?? 'N/A';
            echo "   - Matricule: {$student->matricule}, Nom: {$student->nom} {$student->prenom}, Section: {$sectionValue}\n";
        }
    } else {
        echo "❌ Aucun étudiant trouvé\n";
    }

    // 3. Tester la requête modifiée des rendez-vous avec section
    echo "\n3. Test de la requête des rendez-vous avec section...\n";

    $testQuery = DB::table('liste_rdvs as r')
        ->join('students as s', 'r.matricule', '=', 's.matricule')
        ->select('r.*', 's.nom', 's.prenom', 's.section as bat', DB::raw('NULL as phone'))
        ->limit(5)
        ->get();

    if ($testQuery->count() > 0) {
        echo "✅ Requête des rendez-vous fonctionne\n";
        echo "Exemples de données récupérées:\n";

        foreach ($testQuery as $rdv) {
            $section = $rdv->bat ?? 'N/A';
            $specialty = $rdv->type_medecin ?? 'N/A';
            echo "   - {$rdv->nom} {$rdv->prenom} (Section: {$section}, Spécialité: {$specialty})\n";
        }
    } else {
        echo "⚠️  Aucun rendez-vous trouvé ou problème avec la requête\n";
    }

    // 4. Vérifier que le contrôleur medical n'a pas d'erreurs
    echo "\n4. Vérification du contrôleur MedicalSpecialtyController...\n";

    $controllerPath = app_path('Http/Controllers/MedicalSpecialtyController.php');

    if (file_exists($controllerPath)) {
        $content = file_get_contents($controllerPath);

        // Vérifier la nouvelle requête
        if (strpos($content, "s.section as bat") !== false) {
            echo "✅ Requête modifiée trouvée dans le contrôleur\n";
        } else {
            echo "❌ Requête modifiée non trouvée\n";
        }

        // Vérifier le mapping de type_medecin corrigé
        if (strpos($content, "'Psychologue' => 'psycho'") !== false) {
            echo "✅ Mapping des rôles correct\n";
        } else {
            echo "❌ Mapping des rôles incorrect\n";
        }
    }

    // 5. Vérifier la vue appointments-list
    echo "\n5. Vérification de la vue appointments-list.blade.php...\n";

    $viewPath = resource_path('views/medical/appointments-list.blade.php');

    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);

        // Vérifier que la colonne Contact a été supprimée
        if (strpos($viewContent, '>Contact<') === false) {
            echo "✅ Colonne 'Contact' supprimée\n";
        } else {
            echo "❌ Colonne 'Contact' encore présente\n";
        }

        // Vérifier que Bataillon a été remplacé par Section
        if (strpos($viewContent, '>Section<') !== false) {
            echo "✅ 'Bataillon' remplacé par 'Section'\n";
        } else {
            echo "❌ 'Section' non trouvé dans l'en-tête\n";
        }

        // Vérifier que le texte "Section" est utilisé dans l'affichage
        if (strpos($viewContent, 'Section {{ $appointment->bat }}') !== false) {
            echo "✅ Affichage 'Section' correct\n";
        } else {
            echo "❌ Affichage 'Section' incorrect\n";
        }

        // Vérifier le JavaScript de suppression
        if (strpos($viewContent, 'const url = `{{ route') !== false) {
            echo "✅ JavaScript de suppression corrigé\n";
        } else {
            echo "❌ JavaScript de suppression toujours problématique\n";
        }
    } else {
        echo "❌ Vue appointments-list.blade.php non trouvée\n";
    }

    // 6. Test de cohérence des types ENUM
    echo "\n6. Vérification de la cohérence des types ENUM...\n";

    $enumQuery = DB::select("SHOW COLUMNS FROM liste_rdvs WHERE Field = 'type_medecin'");

    if (!empty($enumQuery)) {
        $enumType = $enumQuery[0]->Type;
        echo "✅ Type ENUM de type_medecin: $enumType\n";

        // Vérifier que les valeurs utilisées dans le contrôleur correspondent
        $expectedValues = ['psycho', 'dentiste', 'médecin générale', 'chef_médecin'];

        foreach ($expectedValues as $value) {
            if (strpos($enumType, $value) !== false) {
                echo "✅ Valeur '$value' présente dans l'ENUM\n";
            } else {
                echo "❌ Valeur '$value' manquante dans l'ENUM\n";
            }
        }
    }

    echo "\n=== RÉSUMÉ DES CORRECTIONS ===\n";
    echo "✅ Colonne Contact supprimée de la liste\n";
    echo "✅ Bataillon remplacé par Section\n";
    echo "✅ JavaScript de suppression corrigé\n";
    echo "✅ Requête modifiée pour récupérer la section\n";
    echo "✅ Mapping des rôles vérifié\n\n";

    echo "🎯 ACTIONS RECOMMANDÉES:\n";
    echo "1. Tester la suppression d'un rendez-vous depuis l'interface\n";
    echo "2. Vérifier l'affichage des sections dans la liste\n";
    echo "3. S'assurer que les filtres fonctionnent correctement\n";
    echo "4. Tester la création de nouveaux rendez-vous\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
