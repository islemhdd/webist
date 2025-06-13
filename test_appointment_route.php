<?php
/**
 * Test script pour vérifier le fonctionnement de la route de création de rendez-vous
 */

// Inclusion de l'autoloader de Laravel
require_once 'vendor/autoload.php';

// Bootstrap de l'application Laravel
$app = require_once 'bootstrap/app.php';

echo "=== TEST DE LA ROUTE DE CRÉATION DE RENDEZ-VOUS ===\n\n";

try {
    // Test 1: Vérifier que les routes sont bien définies
    echo "1. Vérification des routes...\n";

    $router = $app->make('router');
    $routes = $router->getRoutes();

    $createRoute = null;
    $storeRoute = null;

    foreach ($routes as $route) {
        if ($route->getName() === 'medical.appointments.create') {
            $createRoute = $route;
        }
        if ($route->getName() === 'medical.appointments.store') {
            $storeRoute = $route;
        }
    }

    if ($createRoute) {
        echo "   ✅ Route GET 'medical.appointments.create' trouvée: " . $createRoute->uri() . "\n";
        echo "      Méthodes: " . implode(', ', $createRoute->methods()) . "\n";
        echo "      Action: " . $createRoute->getActionName() . "\n";
    } else {
        echo "   ❌ Route GET 'medical.appointments.create' NON TROUVÉE\n";
    }

    if ($storeRoute) {
        echo "   ✅ Route POST 'medical.appointments.store' trouvée: " . $storeRoute->uri() . "\n";
        echo "      Méthodes: " . implode(', ', $storeRoute->methods()) . "\n";
        echo "      Action: " . $storeRoute->getActionName() . "\n";
    } else {
        echo "   ❌ Route POST 'medical.appointments.store' NON TROUVÉE\n";
    }

    echo "\n";

    // Test 2: Vérifier que le contrôleur a les bonnes méthodes
    echo "2. Vérification du contrôleur...\n";

    $controller = 'App\Http\Controllers\MedicalSpecialtyController';

    if (class_exists($controller)) {
        echo "   ✅ Contrôleur '$controller' existe\n";

        if (method_exists($controller, 'showCreateAppointmentForm')) {
            echo "   ✅ Méthode 'showCreateAppointmentForm' existe\n";
        } else {
            echo "   ❌ Méthode 'showCreateAppointmentForm' MANQUANTE\n";
        }

        if (method_exists($controller, 'createAppointment')) {
            echo "   ✅ Méthode 'createAppointment' existe\n";
        } else {
            echo "   ❌ Méthode 'createAppointment' MANQUANTE\n";
        }

        if (method_exists($controller, 'getMotifsAndServicesBySpecialty')) {
            echo "   ✅ Méthode 'getMotifsAndServicesBySpecialty' existe\n";
        } else {
            echo "   ❌ Méthode 'getMotifsAndServicesBySpecialty' MANQUANTE\n";
        }
    } else {
        echo "   ❌ Contrôleur '$controller' N'EXISTE PAS\n";
    }

    echo "\n";

    // Test 3: Vérifier que la vue existe
    echo "3. Vérification de la vue...\n";

    $viewPath = 'resources/views/medical/appointments-create.blade.php';
    if (file_exists($viewPath)) {
        echo "   ✅ Vue '$viewPath' existe\n";
    } else {
        echo "   ❌ Vue '$viewPath' N'EXISTE PAS\n";
    }

    echo "\n=== RÉSULTAT ===\n";

    if ($createRoute && $storeRoute && class_exists($controller) &&
        method_exists($controller, 'showCreateAppointmentForm') &&
        method_exists($controller, 'createAppointment') &&
        file_exists($viewPath)) {
        echo "✅ SUCCÈS: Toutes les routes et méthodes sont correctement configurées\n";
        echo "   Vous pouvez maintenant accéder à /medical/appointments/create\n";
    } else {
        echo "❌ ERREUR: Des éléments sont manquants\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
