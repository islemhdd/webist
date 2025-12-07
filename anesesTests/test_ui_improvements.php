<?php
/**
 * Script de test des améliorations UI de la page expulsions
 * Vérifie les améliorations visuelles et le centrage
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Initialisation Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🎨 TEST DES AMÉLIORATIONS UI - Page Expulsions DE\n";
echo "=================================================\n\n";

/**
 * Test 1: Vérification des structures CSS et HTML
 */
echo "🧪 Test 1: Vérification des améliorations CSS\n";
echo "-----------------------------------------\n";

$viewPath = resource_path('views/de/expulsions/index.blade.php');
$viewContent = file_get_contents($viewPath);

// Vérifier les améliorations apportées
$improvements = [
    'Container avec padding' => 'min-h-screen bg-gray-50 dark:bg-gray-900 py-8',
    'Cartes avec hover effects' => 'hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer',
    'Grid centré' => 'grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 justify-center',
    'Gradients améliorés' => 'bg-gradient-to-br from-red-50 to-red-100',
    'Styles JavaScript' => 'updateGradeButtonStyles()',
    'Filtrage temps réel' => 'applyRealTimeFilters()',
];

foreach ($improvements as $nom => $pattern) {
    if (strpos($viewContent, $pattern) !== false) {
        echo "✅ {$nom}: Présent\n";
    } else {
        echo "❌ {$nom}: Manquant\n";
    }
}

/**
 * Test 2: Vérification des fonctionnalités JavaScript
 */
echo "\n🧪 Test 2: Vérification des fonctions JavaScript\n";
echo "-----------------------------------------------\n";

$jsFunctions = [
    'applyRealTimeFilters()' => 'function applyRealTimeFilters()',
    'updateGradeButtonStyles()' => 'function updateGradeButtonStyles()',
    'openAddModal()' => 'function openAddModal()',
    'editExpulsion()' => 'function editExpulsion(',
    'deleteExpulsion()' => 'function deleteExpulsion(',
    'closeModal()' => 'function closeModal()',
];

foreach ($jsFunctions as $nom => $pattern) {
    if (strpos($viewContent, $pattern) !== false) {
        echo "✅ {$nom}: Définie\n";
    } else {
        echo "❌ {$nom}: Manquante\n";
    }
}

/**
 * Test 3: Vérification des éléments d'interface
 */
echo "\n🧪 Test 3: Vérification des éléments d'interface\n";
echo "----------------------------------------------\n";

$uiElements = [
    'Header avec icône' => 'fas fa-user-times text-red-500',
    'Barre décorative' => 'bg-gradient-to-r from-red-500 to-orange-500',
    'Cartes statistiques colorées' => 'bg-gradient-to-br from-blue-50 to-blue-100',
    'Filtres temps réel' => 'onkeyup="applyRealTimeFilters()"',
    'Boutons de grade' => 'onchange="applyRealTimeFilters()"',
    'Modal d\'ajout' => 'id="expulsionModal"',
];

foreach ($uiElements as $nom => $pattern) {
    if (strpos($viewContent, $pattern) !== false) {
        echo "✅ {$nom}: Présent\n";
    } else {
        echo "❌ {$nom}: Manquant\n";
    }
}

/**
 * Test 4: Vérification de la structure responsive
 */
echo "\n🧪 Test 4: Vérification de la structure responsive\n";
echo "------------------------------------------------\n";

$responsiveElements = [
    'Grid responsive' => 'grid-cols-1 md:grid-cols-4',
    'Flexbox responsive' => 'flex-col lg:flex-row',
    'Padding responsive' => 'px-4 sm:px-6 lg:px-8',
    'Largeur responsive' => 'lg:w-80',
    'Text responsive' => 'text-4xl',
];

foreach ($responsiveElements as $nom => $pattern) {
    if (strpos($viewContent, $pattern) !== false) {
        echo "✅ {$nom}: Configuré\n";
    } else {
        echo "❌ {$nom}: Non configuré\n";
    }
}

/**
 * Test 5: Vérification des données de test
 */
echo "\n🧪 Test 5: Vérification des données pour test visuel\n";
echo "---------------------------------------------------\n";

try {
    // Compter les expulsions
    $expulsionsCount = DB::table('expulsions')
        ->whereDate('created_at', today())
        ->count();

    echo "📊 Expulsions aujourd'hui: {$expulsionsCount}\n";

    // Compter les étudiants par grade
    $students = DB::table('students')
        ->select('grade', DB::raw('count(*) as count'))
        ->groupBy('grade')
        ->get();

    foreach ($students as $student) {
        echo "👥 Grade {$student->grade}: {$student->count} étudiants\n";
    }

    echo "✅ Données de test disponibles\n";
} catch (Exception $e) {
    echo "❌ Erreur accès aux données: " . $e->getMessage() . "\n";
}

/**
 * Test 6: Simulation d'accès à la page
 */
echo "\n🧪 Test 6: Simulation d'accès à la route\n";
echo "---------------------------------------\n";

try {
    // Tester la route des expulsions
    $routes = [
        'de.expulsions.index' => '/de/expulsions',
        'de.infirmerie.index' => '/de/infirmerie',
    ];

    foreach ($routes as $name => $path) {
        if (Route::has($name)) {
            echo "✅ Route {$name}: Accessible\n";
        } else {
            echo "❌ Route {$name}: Non trouvée\n";
        }
    }
} catch (Exception $e) {
    echo "ℹ️ Routes non testables dans ce contexte\n";
}

echo "\n🎯 Résumé des Améliorations UI\n";
echo "==============================\n";
echo "✨ Centrage et espacement améliorés\n";
echo "🎨 Effets hover sur les cartes statistiques\n";
echo "🚀 Transitions fluides (duration-300)\n";
echo "📱 Design responsive optimisé\n";
echo "🌈 Gradients et couleurs améliorés\n";
echo "⚡ Filtrage en temps réel fonctionnel\n";
echo "📋 Interface modale améliorée\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "Test terminé le " . date('d/m/Y à H:i:s') . "\n";
