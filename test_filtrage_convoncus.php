<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'webistIslem',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== TEST DU FILTRAGE DES CONVOQUÉS ===\n\n";

// 1. Vérifier les données de test
echo "📊 ÉTAT ACTUEL DES CONVOQUÉS :\n";
echo "===============================\n";

$convoncus = Capsule::table('convoncus')
    ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
    ->select('convoncus.*', 'Students.nom', 'Students.prenom')
    ->get();

foreach ($convoncus as $convoncu) {
    $psyStatus = ($convoncu->psy === null || trim($convoncu->psy) === '') ? '❌ VIDE' : '✅ REMPLI';
    $medGenStatus = ($convoncu->medGen === null || trim($convoncu->medGen) === '') ? '❌ VIDE' : '✅ REMPLI';
    $chirDentStatus = ($convoncu->chirDent === null || trim($convoncu->chirDent) === '') ? '❌ VIDE' : '✅ REMPLI';
    $avisStatus = ($convoncu->avisSpe === null || trim($convoncu->avisSpe) === '') ? '❌ VIDE' : '✅ REMPLI';

    echo sprintf("👤 %s %s (%s)\n", $convoncu->nom, $convoncu->prenom, $convoncu->matricule);
    echo sprintf("   🧠 Psy: %s\n", $psyStatus);
    echo sprintf("   🩺 MedGen: %s\n", $medGenStatus);
    echo sprintf("   🦷 ChirDent: %s\n", $chirDentStatus);
    echo sprintf("   👨‍⚕️ AvisSpe: %s\n", $avisStatus);
    echo "\n";
}

echo "\n=== TESTS DE FILTRAGE PAR RÔLE ===\n\n";

// 2. Test Psychologue : étudiants avec champ psy null ou vide
echo "🧠 FILTRAGE PSYCHOLOGUE (psy null ou vide) :\n";
echo "=============================================\n";
$psychologueQuery = Capsule::table('convoncus')
    ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
    ->select('convoncus.*', 'Students.nom', 'Students.prenom')
    ->where(function($q) {
        $q->whereNull('psy')->orWhere('psy', '');
    });

$psychologueResults = $psychologueQuery->get();
echo "Nombre d'étudiants visibles : " . count($psychologueResults) . "\n";
foreach ($psychologueResults as $result) {
    echo sprintf("- %s %s (%s)\n", $result->nom, $result->prenom, $result->matricule);
}

// 3. Test Dentiste : étudiants avec champ chirDent null ou vide
echo "\n🦷 FILTRAGE DENTISTE (chirDent null ou vide) :\n";
echo "===============================================\n";
$dentisteQuery = Capsule::table('convoncus')
    ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
    ->select('convoncus.*', 'Students.nom', 'Students.prenom')
    ->where(function($q) {
        $q->whereNull('chirDent')->orWhere('chirDent', '');
    });

$dentisteResults = $dentisteQuery->get();
echo "Nombre d'étudiants visibles : " . count($dentisteResults) . "\n";
foreach ($dentisteResults as $result) {
    echo sprintf("- %s %s (%s)\n", $result->nom, $result->prenom, $result->matricule);
}

// 4. Test Médecin général : étudiants avec champ medGen null ou vide
echo "\n🩺 FILTRAGE MÉDECIN GÉNÉRAL (medGen null ou vide) :\n";
echo "===================================================\n";
$medGenQuery = Capsule::table('convoncus')
    ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
    ->select('convoncus.*', 'Students.nom', 'Students.prenom')
    ->where(function($q) {
        $q->whereNull('medGen')->orWhere('medGen', '');
    });

$medGenResults = $medGenQuery->get();
echo "Nombre d'étudiants visibles : " . count($medGenResults) . "\n";
foreach ($medGenResults as $result) {
    echo sprintf("- %s %s (%s)\n", $result->nom, $result->prenom, $result->matricule);
}

// 5. Test Médecin chef : étudiants avec AU MOINS UN champ null ou vide
echo "\n👨‍⚕️ FILTRAGE MÉDECIN CHEF (au moins un champ null ou vide) :\n";
echo "===========================================================\n";
$medecinChefQuery = Capsule::table('convoncus')
    ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
    ->select('convoncus.*', 'Students.nom', 'Students.prenom')
    ->where(function($q) {
        $q->where(function($subQ) {
            // Champ psy null ou vide
            $subQ->whereNull('psy')->orWhere('psy', '');
        })->orWhere(function($subQ) {
            // Champ medGen null ou vide
            $subQ->whereNull('medGen')->orWhere('medGen', '');
        })->orWhere(function($subQ) {
            // Champ chirDent null ou vide
            $subQ->whereNull('chirDent')->orWhere('chirDent', '');
        });
    });

$medecinChefResults = $medecinChefQuery->get();
echo "Nombre d'étudiants visibles : " . count($medecinChefResults) . "\n";
foreach ($medecinChefResults as $result) {
    $psyEmpty = ($result->psy === null || trim($result->psy) === '');
    $medGenEmpty = ($result->medGen === null || trim($result->medGen) === '');
    $chirDentEmpty = ($result->chirDent === null || trim($result->chirDent) === '');

    $missingFields = [];
    if ($psyEmpty) $missingFields[] = 'Psy';
    if ($medGenEmpty) $missingFields[] = 'MedGen';
    if ($chirDentEmpty) $missingFields[] = 'ChirDent';

    echo sprintf("- %s %s (%s) - Champs manquants: %s\n",
        $result->nom, $result->prenom, $result->matricule,
        implode(', ', $missingFields));
}

echo "\n=== VÉRIFICATION DE LA LOGIQUE ===\n\n";

// 6. Vérifier que la logique est correcte
$totalConvoncus = count($convoncus);
$psychologueCount = count($psychologueResults);
$dentisteCount = count($dentisteResults);
$medGenCount = count($medGenResults);
$medecinChefCount = count($medecinChefResults);

echo "📈 RÉSUMÉ DES FILTRES :\n";
echo "=======================\n";
echo sprintf("Total convoqués : %d\n", $totalConvoncus);
echo sprintf("Visibles par Psychologue : %d (%.1f%%)\n", $psychologueCount, ($psychologueCount/$totalConvoncus)*100);
echo sprintf("Visibles par Dentiste : %d (%.1f%%)\n", $dentisteCount, ($dentisteCount/$totalConvoncus)*100);
echo sprintf("Visibles par Médecin Général : %d (%.1f%%)\n", $medGenCount, ($medGenCount/$totalConvoncus)*100);
echo sprintf("Visibles par Médecin Chef : %d (%.1f%%)\n", $medecinChefCount, ($medecinChefCount/$totalConvoncus)*100);

echo "\n✅ Test de filtrage des convoqués terminé !\n";
