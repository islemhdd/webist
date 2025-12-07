<?php
/**
 * Test direct du contrôleur de rendez-vous
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== TEST DIRECT DU CONTRÔLEUR DE RENDEZ-VOUS ===\n\n";

try {
    // Instancier le contrôleur
    $controller = new App\Http\Controllers\MedicalSpecialtyController();

    echo "✅ Contrôleur instancié avec succès\n";

    // Vérifier que les méthodes existent
    $methods = get_class_methods($controller);

    if (in_array('showCreateAppointmentForm', $methods)) {
        echo "✅ Méthode 'showCreateAppointmentForm' trouvée\n";
    } else {
        echo "❌ Méthode 'showCreateAppointmentForm' manquante\n";
        echo "Méthodes disponibles: " . implode(', ', $methods) . "\n";
    }

    if (in_array('createAppointment', $methods)) {
        echo "✅ Méthode 'createAppointment' trouvée\n";
    } else {
        echo "❌ Méthode 'createAppointment' manquante\n";
    }

    if (in_array('getMotifsAndServicesBySpecialty', $methods)) {
        echo "✅ Méthode 'getMotifsAndServicesBySpecialty' trouvée\n";
    } else {
        echo "❌ Méthode 'getMotifsAndServicesBySpecialty' manquante\n";
    }

    echo "\n=== TEST DES DONNÉES MOTIFS/SERVICES ===\n";

    // Utiliser la réflexion pour accéder à la méthode privée
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getMotifsAndServicesBySpecialty');
    $method->setAccessible(true);

    // Tester différentes spécialités
    $specialties = ['psycho', 'dentiste', 'médecin générale', 'all'];

    foreach ($specialties as $specialty) {
        echo "\nSpécialité: $specialty\n";
        $data = $method->invoke($controller, $specialty);
        echo "  - Motifs: " . count($data['motifs']) . " éléments\n";
        echo "  - Services: " . count($data['services']) . " éléments\n";

        if (!empty($data['motifs'])) {
            echo "  - Premier motif: " . $data['motifs'][0] . "\n";
        }
        if (!empty($data['services'])) {
            echo "  - Premier service: " . $data['services'][0] . "\n";
        }
    }

    echo "\n✅ Test terminé avec succès!\n";
    echo "Le problème de la route non trouvée est probablement dû au cache de routes ou au serveur.\n";
    echo "Essayez: php artisan route:clear && php artisan serve\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
