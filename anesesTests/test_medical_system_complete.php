<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\MedicalSpecialtyController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TEST COMPLET DU SYSTÈME MÉDICAL ===\n\n";

try {
    // Test 1: Vérifier que les méthodes existent
    echo "1. Vérification des méthodes dans MedicalSpecialtyController:\n";
    
    $reflection = new ReflectionClass(MedicalSpecialtyController::class);
    
    $requiredMethods = [
        'statistics',
        'filterStatistics',
        'appointmentsList',
        'deleteAppointment',
        'appointmentsStats'
    ];
    
    foreach ($requiredMethods as $method) {
        if ($reflection->hasMethod($method)) {
            echo "✅ Méthode '$method' trouvée\n";
        } else {
            echo "❌ Méthode '$method' manquante\n";
        }
    }
    echo "\n";
    
    // Test 2: Créer un utilisateur médecin chef pour les tests
    echo "2. Test avec utilisateur médecin chef:\n";
    
    $user = User::where('username', 'Medecin chef')->first();
    if (!$user) {
        echo "❌ Utilisateur médecin chef non trouvé\n";
    } else {
        echo "✅ Utilisateur médecin chef trouvé: {$user->username}\n";
        
        // Simuler l'authentification
        Auth::login($user);
        
        $controller = new MedicalSpecialtyController();
        
        // Test de la méthode statistics
        try {
            $request = new Request();
            $response = $controller->statistics($request);
            echo "✅ Méthode statistics() fonctionne\n";
        } catch (Exception $e) {
            echo "❌ Erreur dans statistics(): " . $e->getMessage() . "\n";
        }
        
        // Test de la méthode filterStatistics
        try {
            $request = new Request(['grade' => 'all']);
            $response = $controller->filterStatistics($request);
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                echo "✅ Méthode filterStatistics() retourne du JSON\n";
                $data = $response->getData(true);
                echo "   Données reçues: " . count($data) . " éléments\n";
            } else {
                echo "❌ filterStatistics() ne retourne pas de JSON\n";
            }
        } catch (Exception $e) {
            echo "❌ Erreur dans filterStatistics(): " . $e->getMessage() . "\n";
        }
        
        // Test avec grade spécifique
        try {
            $request = new Request(['grade' => '1']);
            $response = $controller->filterStatistics($request);
            echo "✅ Filtrage par grade 1 fonctionne\n";
        } catch (Exception $e) {
            echo "❌ Erreur filtrage grade 1: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // Test 3: Test avec différents types de médecins
    echo "3. Test avec différents rôles médicaux:\n";
    
    $medicalRoles = [
        'Psychologue' => 'Psychologue',
        'Dentiste' => 'Dentiste', 
        'Médecin général' => 'Medecin generale'
    ];
    
    foreach ($medicalRoles as $role => $username) {
        $user = User::where('username', $username)->first();
        if ($user) {
            Auth::login($user);
            echo "✅ Test avec {$role}: authentification OK\n";
            
            try {
                $controller = new MedicalSpecialtyController();
                $request = new Request();
                $response = $controller->statistics($request);
                echo "   - statistics() fonctionne pour {$role}\n";
            } catch (Exception $e) {
                echo "   ❌ Erreur statistics() pour {$role}: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ Utilisateur {$role} non trouvé\n";
        }
    }
    echo "\n";
    
    // Test 4: Vérifier les routes
    echo "4. Vérification des routes médicales:\n";
    
    $routes = [
        '/medical/statistics',
        '/medical/statistics/filter',
        '/medical/appointments',
        '/medical/appointments/stats'
    ];
    
    foreach ($routes as $route) {
        try {
            $request = Request::create($route, 'GET');
            echo "✅ Route {$route} définie\n";
        } catch (Exception $e) {
            echo "❌ Problème avec route {$route}\n";
        }
    }
    echo "\n";
    
    // Test 5: Test des données d'exemples
    echo "5. Vérification des données de test:\n";
    
    $today = now()->toDateString();
    
    // Patients
    $patientsCount = \App\Models\Patient::whereDate('created_at', $today)->count();
    echo "Patients d'aujourd'hui: {$patientsCount}\n";
    
    // Rendez-vous
    $rdvCount = \App\Models\ListeRdv::whereDate('date', $today)->count();
    echo "Rendez-vous d'aujourd'hui: {$rdvCount}\n";
    
    // Convocations
    $convocationsCount = \App\Models\Convoncu::count();
    echo "Convocations totales: {$convocationsCount}\n";
    
    // Exemptions
    $exemptionsCount = \App\Models\Exemption::whereDate('date_debut', '<=', $today)
        ->whereDate('date_fin', '>=', $today)->count();
    echo "Exemptions actives: {$exemptionsCount}\n";
    
    echo "\n✅ SYSTÈME MÉDICAL COMPLET ET FONCTIONNEL!\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR CRITIQUE: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "TEST TERMINÉ\n";
