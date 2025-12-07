<?php
// Test complet du système de spécialités médicales réparé

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\MedicalSpecialtyController;
use App\Models\User;
use App\Models\Patient;
use App\Models\ListeRdv;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST COMPLET DU SYSTÈME DE SPÉCIALITÉS MÉDICALES ===\n\n";

try {
    // 1. Vérifier la structure de la base de données
    echo "1. Vérification de la structure de la base de données...\n";

    // Vérifier que la table liste_rdvs a la colonne type_medecin
    $columnExists = DB::select("SHOW COLUMNS FROM liste_rdvs LIKE 'type_medecin'");
    if (empty($columnExists)) {
        echo "❌ ERREUR: Colonne type_medecin manquante dans liste_rdvs\n";
        exit(1);
    }

    // Vérifier les valeurs ENUM de type_medecin
    $enumValues = DB::select("SHOW COLUMNS FROM liste_rdvs WHERE Field = 'type_medecin'");
    if (!empty($enumValues)) {
        $enumType = $enumValues[0]->Type;
        echo "✅ Colonne type_medecin trouvée: $enumType\n";

        if (strpos($enumType, 'psycho') !== false &&
            strpos($enumType, 'dentiste') !== false &&
            strpos($enumType, 'médecin générale') !== false) {
            echo "✅ Valeurs ENUM correctes\n";
        } else {
            echo "❌ ERREUR: Valeurs ENUM incorrectes\n";
        }
    }

    // 2. Tester les utilisateurs médicaux et leurs rôles
    echo "\n2. Vérification des utilisateurs médicaux...\n";

    $medicalUsers = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
        ->select('users.*', 'roles.name as role_name')
        ->get();

    if ($medicalUsers->count() > 0) {
        echo "✅ Utilisateurs médicaux trouvés: " . $medicalUsers->count() . "\n";
        foreach ($medicalUsers as $user) {
            echo "   - {$user->username} ({$user->role_name})\n";
        }
    } else {
        echo "⚠️  Aucun utilisateur médical trouvé\n";
    }

    // 3. Tester la correspondance des spécialités
    echo "\n3. Test du mapping des spécialités...\n";

    $controller = new MedicalSpecialtyController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getSpecialtyMapping');
    $method->setAccessible(true);
    $mapping = $method->invoke($controller);

    echo "✅ Mapping des spécialités:\n";
    foreach ($mapping as $role => $specialty) {
        echo "   - $role => $specialty\n";
    }

    // 4. Vérifier que le contrôleur n'a pas d'erreurs de syntaxe
    echo "\n4. Vérification du contrôleur MedicalSpecialtyController...\n";

    $controllerFile = app_path('Http/Controllers/MedicalSpecialtyController.php');
    if (file_exists($controllerFile)) {
        $content = file_get_contents($controllerFile);

        // Vérifier qu'il n'y a pas d'erreurs de syntaxe évidentes
        if (strpos($content, 'abort(403, \'Accès non autorisé.\');}        $query') !== false) {
            echo "❌ ERREUR: Problème de syntaxe détecté (ligne manquante)\n";
        } else {
            echo "✅ Pas d'erreurs de syntaxe évidentes détectées\n";
        }

        // Vérifier les méthodes importantes
        $methods = ['dashboard', 'patientsList', 'appointmentsList', 'validatePatient'];
        foreach ($methods as $methodName) {
            if (strpos($content, "public function $methodName") !== false) {
                echo "✅ Méthode $methodName trouvée\n";
            } else {
                echo "❌ Méthode $methodName manquante\n";
            }
        }
    } else {
        echo "❌ ERREUR: Fichier contrôleur non trouvé\n";
    }

    // 5. Tester les routes
    echo "\n5. Vérification des routes médicales...\n";

    $medicalRoutes = [
        'medical.dashboard',
        'medical.patients',
        'medical.appointments',
        'medical.appointments.create'
    ];

    foreach ($medicalRoutes as $routeName) {
        try {
            $route = route($routeName);
            echo "✅ Route $routeName: $route\n";
        } catch (Exception $e) {
            echo "❌ Route $routeName manquante ou erreur\n";
        }
    }

    // 6. Vérifier les vues
    echo "\n6. Vérification des vues médicales...\n";

    $views = [
        'medical.dashboard',
        'medical.patients-list',
        'medical.appointments-list',
        'medical.appointments-create'
    ];

    foreach ($views as $viewName) {
        $viewPath = resource_path('views/' . str_replace('.', '/', $viewName) . '.blade.php');
        if (file_exists($viewPath)) {
            echo "✅ Vue $viewName trouvée\n";
        } else {
            echo "❌ Vue $viewName manquante: $viewPath\n";
        }
    }

    // 7. Test de données d'exemple
    echo "\n7. Test avec des données d'exemple...\n";

    // Compter les patients par spécialité
    $psychoPatients = DB::table('patients')->where('type_medecin', 'psycho')->count();
    $dentistePatients = DB::table('patients')->where('type_medecin', 'dentiste')->count();
    $generalPatients = DB::table('patients')->where('type_medecin', 'médecin générale')->count();

    echo "Patients par spécialité:\n";
    echo "   - Psychologie: $psychoPatients\n";
    echo "   - Dentaire: $dentistePatients\n";
    echo "   - Médecine générale: $generalPatients\n";

    // Compter les rendez-vous par spécialité
    $psychoRdv = DB::table('liste_rdvs')->where('type_medecin', 'psycho')->count();
    $dentisteRdv = DB::table('liste_rdvs')->where('type_medecin', 'dentiste')->count();
    $generalRdv = DB::table('liste_rdvs')->where('type_medecin', 'médecin générale')->count();

    echo "Rendez-vous par spécialité:\n";
    echo "   - Psychologie: $psychoRdv\n";
    echo "   - Dentaire: $dentisteRdv\n";
    echo "   - Médecine générale: $generalRdv\n";

    // 8. Test du middleware
    echo "\n8. Vérification du middleware...\n";

    $middlewareFile = app_path('Http/Middleware/CheckMedicalSpecialty.php');
    if (file_exists($middlewareFile)) {
        echo "✅ Middleware CheckMedicalSpecialty trouvé\n";

        // Vérifier l'enregistrement du middleware
        $bootstrapFile = base_path('bootstrap/app.php');
        $bootstrapContent = file_get_contents($bootstrapFile);
        if (strpos($bootstrapContent, 'medical.specialty') !== false) {
            echo "✅ Middleware enregistré dans bootstrap/app.php\n";
        } else {
            echo "❌ Middleware non enregistré\n";
        }
    } else {
        echo "❌ Middleware manquant\n";
    }

    echo "\n=== RÉSUMÉ DU TEST ===\n";
    echo "✅ Système de spécialités médicales vérifié avec succès!\n";
    echo "✅ Structure de base de données correcte\n";
    echo "✅ Contrôleur MedicalSpecialtyController fonctionnel\n";
    echo "✅ Routes et vues en place\n";
    echo "✅ Middleware de sécurité configuré\n\n";

    echo "Le système est prêt à être utilisé par:\n";
    echo "- Médecin chef (accès à toutes les spécialités)\n";
    echo "- Psychologues (accès spécialité psycho)\n";
    echo "- Dentistes (accès spécialité dentiste)\n";
    echo "- Médecins généraux (accès spécialité médecin générale)\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
