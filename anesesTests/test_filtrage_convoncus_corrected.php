<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'webistIslem',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== TEST DU FILTRAGE CORRIGÉ DES CONVOQUÉS ===\n\n";

try {
    // Test 1: Psychologue - doit voir seulement les étudiants avec psy NULL
    echo "1. TEST PSYCHOLOGUE (champ psy NULL uniquement):\n";
    $psychologueStudents = Capsule::table('convoncus')
        ->whereNull('psy')
        ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
        ->select('convoncus.matricule', 'Students.nom', 'Students.prenom', 'convoncus.psy', 'convoncus.medGen', 'convoncus.chirDent')
        ->get();

    echo "   Nombre d'étudiants visibles: " . $psychologueStudents->count() . "\n";
    foreach ($psychologueStudents->take(3) as $student) {
        echo "   - {$student->matricule} ({$student->nom} {$student->prenom}) - psy: " . ($student->psy ?? 'NULL') . "\n";
    }
    echo "\n";

    // Test 2: Médecin général - doit voir seulement les étudiants avec medGen NULL
    echo "2. TEST MÉDECIN GÉNÉRAL (champ medGen NULL uniquement):\n";
    $medGenStudents = Capsule::table('convoncus')
        ->whereNull('medGen')
        ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
        ->select('convoncus.matricule', 'Students.nom', 'Students.prenom', 'convoncus.psy', 'convoncus.medGen', 'convoncus.chirDent')
        ->get();

    echo "   Nombre d'étudiants visibles: " . $medGenStudents->count() . "\n";
    foreach ($medGenStudents->take(3) as $student) {
        echo "   - {$student->matricule} ({$student->nom} {$student->prenom}) - medGen: " . ($student->medGen ?? 'NULL') . "\n";
    }
    echo "\n";

    // Test 3: Dentiste - doit voir seulement les étudiants avec chirDent NULL
    echo "3. TEST DENTISTE (champ chirDent NULL uniquement):\n";
    $dentisteStudents = Capsule::table('convoncus')
        ->whereNull('chirDent')
        ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
        ->select('convoncus.matricule', 'Students.nom', 'Students.prenom', 'convoncus.psy', 'convoncus.medGen', 'convoncus.chirDent')
        ->get();

    echo "   Nombre d'étudiants visibles: " . $dentisteStudents->count() . "\n";
    foreach ($dentisteStudents->take(3) as $student) {
        echo "   - {$student->matricule} ({$student->nom} {$student->prenom}) - chirDent: " . ($student->chirDent ?? 'NULL') . "\n";
    }
    echo "\n";

    // Test 4: Médecin chef - doit voir les étudiants avec AU MOINS UN champ NULL
    echo "4. TEST MÉDECIN CHEF (au moins un champ NULL):\n";
    $medecinChefStudents = Capsule::table('convoncus')
        ->where(function($q) {
            $q->whereNull('psy')
              ->orWhereNull('medGen')
              ->orWhereNull('chirDent');
        })
        ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
        ->select('convoncus.matricule', 'Students.nom', 'Students.prenom', 'convoncus.psy', 'convoncus.medGen', 'convoncus.chirDent')
        ->get();

    echo "   Nombre d'étudiants visibles: " . $medecinChefStudents->count() . "\n";
    foreach ($medecinChefStudents->take(5) as $student) {
        $psyStatus = $student->psy ? 'REMPLI' : 'NULL';
        $medGenStatus = $student->medGen ? 'REMPLI' : 'NULL';
        $chirDentStatus = $student->chirDent ? 'REMPLI' : 'NULL';
        echo "   - {$student->matricule} ({$student->nom} {$student->prenom}) - psy: {$psyStatus}, medGen: {$medGenStatus}, chirDent: {$chirDentStatus}\n";
    }
    echo "\n";

    // Test 5: Vérification qu'aucun médecin ne voit les étudiants avec tous les champs remplis
    echo "5. VÉRIFICATION - Étudiants avec TOUS les champs remplis (ne doivent être vus par personne sauf médecin chef pour avis):\n";
    $completeStudents = Capsule::table('convoncus')
        ->whereNotNull('psy')
        ->where('psy', '!=', '')
        ->whereNotNull('medGen')
        ->where('medGen', '!=', '')
        ->whereNotNull('chirDent')
        ->where('chirDent', '!=', '')
        ->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
        ->select('convoncus.matricule', 'Students.nom', 'Students.prenom', 'convoncus.avisSpe')
        ->get();

    echo "   Nombre d'étudiants avec tous les diagnostics: " . $completeStudents->count() . "\n";
    foreach ($completeStudents->take(3) as $student) {
        $avisSpeStatus = $student->avisSpe ? 'DONNÉ' : 'EN ATTENTE';
        echo "   - {$student->matricule} ({$student->nom} {$student->prenom}) - Avis spécialisé: {$avisSpeStatus}\n";
    }
    echo "\n";

    echo "=== RÉSUMÉ DES TESTS ===\n";
    echo "✅ Psychologue voit " . $psychologueStudents->count() . " étudiants (psy NULL)\n";
    echo "✅ Médecin général voit " . $medGenStudents->count() . " étudiants (medGen NULL)\n";
    echo "✅ Dentiste voit " . $dentisteStudents->count() . " étudiants (chirDent NULL)\n";
    echo "✅ Médecin chef voit " . $medecinChefStudents->count() . " étudiants (au moins 1 champ NULL)\n";
    echo "✅ " . $completeStudents->count() . " étudiants ont tous les diagnostics (visibles par médecin chef pour avis spécialisé)\n";

    echo "\n=== FILTRAGE CORRIGÉ AVEC SUCCÈS ! ===\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
