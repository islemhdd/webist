<?php

echo "🔍 DIAGNOSTIC GLOBAL - PROBLÈME DE VALIDATION FICHE MÉDICALE\n";
echo "=============================================================\n\n";

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

try {
    echo "🎯 ANALYSE DU PROBLÈME\n";
    echo "======================\n";
    echo "SYMPTÔMES OBSERVÉS:\n";
    echo "- ✅ Interface montre tous diagnostics complétés\n";
    echo "- ❌ Laravel affiche erreurs: psy, medGen, chirDent requis\n";
    echo "- ❌ Le dd() dans le contrôleur n'apparaît pas\n";
    echo "- ❌ Validation échoue avant d'atteindre le contrôleur\n\n";

    echo "1. 🔍 VÉRIFICATION DES DONNÉES\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $conv = Convoncu::where('matricule', '2022064')->first();
    if ($conv) {
        echo "📋 Convocation 2022064:\n";
        echo "   - Psy: '{$conv->psy}' (" . strlen($conv->psy) . " chars)\n";
        echo "   - MedGen: '{$conv->medGen}' (" . strlen($conv->medGen) . " chars)\n";
        echo "   - ChirDent: '{$conv->chirDent}' (" . strlen($conv->chirDent) . " chars)\n";
        echo "   - AvisSpe: '{$conv->avisSpe}' (" . strlen($conv->avisSpe ?: '') . " chars)\n";

        $allFilled = !empty(trim($conv->psy)) && !empty(trim($conv->medGen)) && !empty(trim($conv->chirDent));
        echo "   - STATUT: " . ($allFilled ? "✅ TOUS REMPLIS" : "❌ INCOMPLET") . "\n\n";
    }

    echo "2. 🛣️ VÉRIFICATION DES ROUTES\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $routes = Route::getRoutes();
    $ficheRoutes = [];

    foreach ($routes as $route) {
        if (str_contains($route->uri(), 'fiche')) {
            $ficheRoutes[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'action' => $route->getActionName()
            ];
        }
    }

    foreach ($ficheRoutes as $route) {
        echo "   📍 {$route['method']} /{$route['uri']} → {$route['action']}\n";
    }

    echo "\n3. 🎭 VÉRIFICATION DES RÔLES\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $medecin = User::whereHas('role', function($q) {
        $q->where('name', 'Medecin');
    })->first();

    if ($medecin) {
        echo "   👨‍⚕️ Médecin chef: {$medecin->username} (Role: {$medecin->role->name})\n";
    }

    echo "\n4. 🧪 ANALYSE DU FORMULAIRE HTML\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Simuler le rendu de la vue pour médecin chef
    $Student = Student::where('matricule', '2022064')->first();
    $convoncu = $conv;
    $userRole = 'Medecin';

    echo "   📝 Simulation du formulaire médecin chef:\n";
    echo "   - Rôle: {$userRole}\n";
    echo "   - Champs attendus: avisSpe SEULEMENT\n";
    echo "   - Champs readonly: psy_display, medGen_display, chirDent_display\n\n";

    echo "5. 🐛 DIAGNOSTIC DU PROBLÈME\n";
    echo "-" . str_repeat("-", 50) . "\n";

    echo "   HYPOTHÈSES:\n";
    echo "   1. ❓ JavaScript envoie des champs supplémentaires\n";
    echo "   2. ❓ Middleware de validation personnalisé\n";
    echo "   3. ❓ Règles de validation globales\n";
    echo "   4. ❓ Noms de champs incorrects dans le formulaire\n";
    echo "   5. ❓ Problème de cache ou de session\n\n";

    echo "6. 🔧 TESTS DE RÉSOLUTION\n";
    echo "-" . str_repeat("-", 50) . "\n";

    echo "   TEST 1: Vérification du FormRequest\n";
    $formRequestExists = class_exists('App\\Http\\Requests\\FicheRequest');
    echo "   - FormRequest personnalisé: " . ($formRequestExists ? "✅ EXISTE" : "❌ N'EXISTE PAS") . "\n";

    echo "\n   TEST 2: Middleware sur la route\n";
    $ficheRoute = null;
    foreach ($routes as $route) {
        if ($route->uri() === 'fiche/{matricule}' && in_array('PUT', $route->methods())) {
            $ficheRoute = $route;
            break;
        }
    }

    if ($ficheRoute) {
        $middleware = $ficheRoute->gatherMiddleware();
        echo "   - Middleware appliqués: " . (empty($middleware) ? "Aucun" : implode(', ', $middleware)) . "\n";
    }

    echo "\n   TEST 3: Validation globale Laravel\n";
    echo "   - Version Laravel: " . app()->version() . "\n";
    echo "   - Validation implicite: Laravel valide automatiquement tous les champs reçus\n";

    echo "\n7. 💡 SOLUTIONS RECOMMANDÉES\n";
    echo "-" . str_repeat("-", 50) . "\n";

    echo "   SOLUTION 1: Désactiver validation implicite\n";
    echo "   - Utiliser \$request->only() strictement\n";
    echo "   - Ne valider QUE les champs autorisés\n\n";

    echo "   SOLUTION 2: Nettoyer le formulaire HTML\n";
    echo "   - S'assurer qu'aucun champ name='psy|medGen|chirDent' n'existe pour médecin chef\n";
    echo "   - Utiliser seulement des champs d'affichage sans name\n\n";

    echo "   SOLUTION 3: Debug approfondi\n";
    echo "   - Ajouter middleware de debug pour voir EXACTEMENT ce qui est envoyé\n";
    echo "   - Logger toutes les requêtes PUT vers /fiche/*\n\n";

    echo "8. 🛠️ PLAN D'ACTION IMMÉDIAT\n";
    echo "-" . str_repeat("-", 50) . "\n";

    echo "   ÉTAPE 1: Créer middleware de debug\n";
    echo "   ÉTAPE 2: Modifier le contrôleur pour être ultra-strict\n";
    echo "   ÉTAPE 3: Nettoyer complètement le formulaire HTML\n";
    echo "   ÉTAPE 4: Tester avec données réelles\n\n";

    echo "9. 🎯 CONCLUSION\n";
    echo "-" . str_repeat("-", 50) . "\n";

    echo "   Le problème est probablement que:\n";
    echo "   ❌ Laravel reçoit des champs psy, medGen, chirDent dans la requête\n";
    echo "   ❌ Ces champs sont vides ou invalides\n";
    echo "   ❌ Laravel applique sa validation par défaut sur TOUS les champs reçus\n";
    echo "   ❌ Le contrôleur n'est jamais atteint car la validation échoue avant\n\n";

    echo "   PRIORITÉ: Identifier d'où viennent ces champs parasites !\n\n";

} catch (Exception $e) {
    echo "❌ Erreur pendant le diagnostic: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
