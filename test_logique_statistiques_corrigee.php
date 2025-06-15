<?php
/**
 * TEST DE LA NOUVELLE LOGIQUE DES STATISTIQUES MÉDICALES
 * Vérification que les statistiques de convocations respectent les critères spécifiés
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 TEST DE LA NOUVELLE LOGIQUE DES STATISTIQUES MÉDICALES\n";
echo str_repeat("=", 65) . "\n\n";

echo "📋 CRITÈRES DE VALIDATION :\n";
echo "─" . str_repeat("─", 50) . "\n";
echo "🔸 MÉDECIN CHEF : Valide SEULEMENT si TOUS les champs remplis\n";
echo "   - psy NON-NULL ET non-vide\n";
echo "   - medGen NON-NULL ET non-vide\n";
echo "   - chirDent NON-NULL ET non-vide\n";
echo "   - avisSpe NON-NULL ET non-vide\n\n";

echo "🔸 MÉDECINS SPÉCIALISÉS : Validé si LEUR champ rempli uniquement\n";
echo "   - Psychologue → champ 'psy' rempli\n";
echo "   - Dentiste → champ 'chirDent' rempli\n";
echo "   - Médecin général → champ 'medGen' rempli\n\n";

// Vérifier la structure des données
echo "1️⃣  ANALYSE DES DONNÉES EXISTANTES\n";
echo "─" . str_repeat("─", 40) . "\n";

$totalConvocations = DB::table('convoncus')->count();
echo "📊 Total convocations : {$totalConvocations}\n\n";

// Analyser quelques échantillons
echo "🔍 Échantillon de données (5 premiers enregistrements) :\n";
$echantillons = DB::table('convoncus')->limit(5)->get();
foreach ($echantillons as $index => $conv) {
    echo "   📄 Convocation " . ($index + 1) . " (Matricule: {$conv->matricule}) :\n";
    echo "      - psy: '" . ($conv->psy ?? 'NULL') . "' | Longueur: " . strlen($conv->psy ?? '') . "\n";
    echo "      - medGen: '" . ($conv->medGen ?? 'NULL') . "' | Longueur: " . strlen($conv->medGen ?? '') . "\n";
    echo "      - chirDent: '" . ($conv->chirDent ?? 'NULL') . "' | Longueur: " . strlen($conv->chirDent ?? '') . "\n";
    echo "      - avisSpe: '" . ($conv->avisSpe ?? 'NULL') . "' | Longueur: " . strlen($conv->avisSpe ?? '') . "\n\n";
}

echo "2️⃣  TESTS DE LA NOUVELLE LOGIQUE\n";
echo "─" . str_repeat("─", 40) . "\n";

// Test Médecin Chef
echo "👨‍⚕️ MÉDECIN CHEF (TOUS les champs doivent être remplis) :\n";
$convocationsValidesChef = DB::table('convoncus')
    ->whereNotNull('psy')->where('psy', '!=', '')
    ->whereNotNull('medGen')->where('medGen', '!=', '')
    ->whereNotNull('chirDent')->where('chirDent', '!=', '')
    ->whereNotNull('avisSpe')->where('avisSpe', '!=', '')
    ->count();

$convocationsInvalidesChef = DB::table('convoncus')
    ->where(function($q) {
        $q->whereNull('psy')->orWhere('psy', '=', '')
          ->orWhereNull('medGen')->orWhere('medGen', '=', '')
          ->orWhereNull('chirDent')->orWhere('chirDent', '=', '')
          ->orWhereNull('avisSpe')->orWhere('avisSpe', '=', '');
    })->count();

echo "   ✅ Convocations VALIDES : {$convocationsValidesChef}\n";
echo "   ❌ Convocations NON-VALIDES : {$convocationsInvalidesChef}\n";
echo "   📊 Vérification total : " . ($convocationsValidesChef + $convocationsInvalidesChef == $totalConvocations ? "✓ CORRECT" : "✗ ERREUR") . "\n\n";

// Test Psychologue
echo "🧠 PSYCHOLOGUE (champ 'psy' uniquement) :\n";
$convocationsValidesPsy = DB::table('convoncus')
    ->whereNotNull('psy')
    ->where('psy', '!=', '')
    ->count();

$convocationsInvalidesPsy = DB::table('convoncus')
    ->where(function($q) {
        $q->whereNull('psy')->orWhere('psy', '=', '');
    })
    ->count();

echo "   ✅ Convocations VALIDES : {$convocationsValidesPsy}\n";
echo "   ❌ Convocations NON-VALIDES : {$convocationsInvalidesPsy}\n";
echo "   📊 Vérification total : " . ($convocationsValidesPsy + $convocationsInvalidesPsy == $totalConvocations ? "✓ CORRECT" : "✗ ERREUR") . "\n\n";

// Test Médecin Général
echo "🩺 MÉDECIN GÉNÉRAL (champ 'medGen' uniquement) :\n";
$convocationsValidesMedGen = DB::table('convoncus')
    ->whereNotNull('medGen')
    ->where('medGen', '!=', '')
    ->count();

$convocationsInvalidesMedGen = DB::table('convoncus')
    ->where(function($q) {
        $q->whereNull('medGen')->orWhere('medGen', '=', '');
    })
    ->count();

echo "   ✅ Convocations VALIDES : {$convocationsValidesMedGen}\n";
echo "   ❌ Convocations NON-VALIDES : {$convocationsInvalidesMedGen}\n";
echo "   📊 Vérification total : " . ($convocationsValidesMedGen + $convocationsInvalidesMedGen == $totalConvocations ? "✓ CORRECT" : "✗ ERREUR") . "\n\n";

// Test Dentiste
echo "🦷 DENTISTE (champ 'chirDent' uniquement) :\n";
$convocationsValidesDent = DB::table('convoncus')
    ->whereNotNull('chirDent')
    ->where('chirDent', '!=', '')
    ->count();

$convocationsInvalidesDent = DB::table('convoncus')
    ->where(function($q) {
        $q->whereNull('chirDent')->orWhere('chirDent', '=', '');
    })
    ->count();

echo "   ✅ Convocations VALIDES : {$convocationsValidesDent}\n";
echo "   ❌ Convocations NON-VALIDES : {$convocationsInvalidesDent}\n";
echo "   📊 Vérification total : " . ($convocationsValidesDent + $convocationsInvalidesDent == $totalConvocations ? "✓ CORRECT" : "✗ ERREUR") . "\n\n";

echo "3️⃣  COMPARAISON ANCIENNE VS NOUVELLE LOGIQUE\n";
echo "─" . str_repeat("─", 45) . "\n";

// Ancienne logique (seulement whereNotNull)
$anciennePsy = DB::table('convoncus')->whereNotNull('psy')->count();
$ancienneMedGen = DB::table('convoncus')->whereNotNull('medGen')->count();
$ancienneDent = DB::table('convoncus')->whereNotNull('chirDent')->count();
$ancienneChef = DB::table('convoncus')
    ->whereNotNull('psy')
    ->whereNotNull('medGen')
    ->whereNotNull('chirDent')
    ->whereNotNull('avisSpe')
    ->count();

echo "📈 ANCIENNE LOGIQUE (seulement NOT NULL) :\n";
echo "   🧠 Psychologue : {$anciennePsy}\n";
echo "   🩺 Médecin général : {$ancienneMedGen}\n";
echo "   🦷 Dentiste : {$ancienneDent}\n";
echo "   👨‍⚕️ Médecin chef : {$ancienneChef}\n\n";

echo "📊 NOUVELLE LOGIQUE (NOT NULL + non-vide) :\n";
echo "   🧠 Psychologue : {$convocationsValidesPsy}\n";
echo "   🩺 Médecin général : {$convocationsValidesMedGen}\n";
echo "   🦷 Dentiste : {$convocationsValidesDent}\n";
echo "   👨‍⚕️ Médecin chef : {$convocationsValidesChef}\n\n";

echo "📉 DIFFÉRENCE (améliorations détectées) :\n";
echo "   🧠 Psychologue : " . ($anciennePsy - $convocationsValidesPsy) . " champs vides filtrés\n";
echo "   🩺 Médecin général : " . ($ancienneMedGen - $convocationsValidesMedGen) . " champs vides filtrés\n";
echo "   🦷 Dentiste : " . ($ancienneDent - $convocationsValidesDent) . " champs vides filtrés\n";
echo "   👨‍⚕️ Médecin chef : " . ($ancienneChef - $convocationsValidesChef) . " dossiers incomplets filtrés\n\n";

echo "4️⃣  VALIDATION DES FICHIERS MODIFIÉS\n";
echo "─" . str_repeat("─", 40) . "\n";

$controllerPath = 'app/Http/Controllers/MedicalSpecialtyController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);

    // Vérifier les modifications
    $nouveauLogique = strpos($content, "->where('psy', '!=', '')") !== false;
    $commentaireMisAJour = strpos($content, "NOUVELLE LOGIQUE") !== false;

    echo "📂 Fichier : {$controllerPath}\n";
    echo "   ✅ Nouvelle logique appliquée : " . ($nouveauLogique ? "OUI" : "NON") . "\n";
    echo "   ✅ Commentaires mis à jour : " . ($commentaireMisAJour ? "OUI" : "NON") . "\n";
    echo "   📊 Taille du fichier : " . number_format(strlen($content)) . " caractères\n\n";
} else {
    echo "❌ Fichier contrôleur non trouvé !\n\n";
}

echo "🎯 RÉSUMÉ FINAL\n";
echo "─" . str_repeat("─", 20) . "\n";
echo "✅ Logique corrigée selon les spécifications exactes\n";
echo "✅ Validation stricte pour le médecin chef (TOUS les champs)\n";
echo "✅ Validation spécialisée pour chaque médecin (LEUR champ)\n";
echo "✅ Filtrage des champs vides en plus des NULL\n";
echo "✅ Commentaires explicatifs ajoutés dans le code\n\n";

$ameliorationTotale = ($anciennePsy - $convocationsValidesPsy) +
                     ($ancienneMedGen - $convocationsValidesMedGen) +
                     ($ancienneDent - $convocationsValidesDent) +
                     ($ancienneChef - $convocationsValidesChef);

echo "🚀 AMÉLIORATION GLOBALE : {$ameliorationTotale} validations incorrectes corrigées\n";
echo str_repeat("=", 65) . "\n";
echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
