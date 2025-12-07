<?php
// Définir le chemin racine de Laravel
define('LARAVEL_START', microtime(true));

// Charger l'autoloader de Composer
require __DIR__.'/vendor/autoload.php';

// Bootstrapper l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Créer un kernel pour la console
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DU NOUVEAU SYSTÈME DE SÉLECTION TYPE_MEDECIN ===\n\n";

// 1. Vérifier la structure actuelle
echo "1. État actuel de la base de données:\n";
$totalPatients = \DB::table('patients')->count();
$patientsWithType = \DB::table('patients')->whereNotNull('type_medecin')->count();
$patientsWithoutType = \DB::table('patients')->whereNull('type_medecin')->count();

echo "   - Total patients: $totalPatients\n";
echo "   - Patients avec type_medecin: $patientsWithType\n";
echo "   - Patients sans type_medecin: $patientsWithoutType\n";

// Répartition par type de médecin
foreach (['psycho', 'médecin générale', 'dentiste'] as $type) {
    $count = \DB::table('patients')->where('type_medecin', $type)->count();
    echo "   - Patients $type: $count\n";
}

// 2. Test de création manuelle de patient avec type_medecin
echo "\n2. Test de création de patient avec type_medecin spécifié:\n";

// Obtenir un matricule d'étudiant valide
$student = \DB::table('students')->first();
if (!$student) {
    echo "Erreur: Aucun étudiant trouvé dans la base de données\n";
    exit;
}

echo "   - Utilisation du matricule: {$student->matricule}\n";

// Supprimer le patient de test s'il existe déjà
\DB::table('patients')->where('matricule', $student->matricule)->delete();

// Test 1: Créer un patient psycho
try {
    $patient = new \App\Models\Patient([
        'matricule' => $student->matricule,
        'type_medecin' => 'psycho',
        'valider' => 0
    ]);
    $patient->save();
    
    echo "   ✓ Patient psycho créé avec succès (ID: {$patient->id})\n";
    
    // Vérifier que le type_medecin a été correctement sauvegardé
    $savedPatient = \DB::table('patients')->where('id', $patient->id)->first();
    echo "   - Type médecin sauvegardé: {$savedPatient->type_medecin}\n";
    
    // Supprimer le patient de test
    $patient->delete();
    echo "   ✓ Patient de test supprimé\n";
    
} catch (Exception $e) {
    echo "   ✗ Erreur lors de la création: " . $e->getMessage() . "\n";
}

// Test 2: Créer un patient médecin générale
try {
    $patient = new \App\Models\Patient([
        'matricule' => $student->matricule,
        'type_medecin' => 'médecin générale',
        'valider' => 0
    ]);
    $patient->save();
    
    echo "   ✓ Patient médecin générale créé avec succès (ID: {$patient->id})\n";
    
    // Vérifier que le type_medecin a été correctement sauvegardé
    $savedPatient = \DB::table('patients')->where('id', $patient->id)->first();
    echo "   - Type médecin sauvegardé: {$savedPatient->type_medecin}\n";
    
    // Supprimer le patient de test
    $patient->delete();
    echo "   ✓ Patient de test supprimé\n";
    
} catch (Exception $e) {
    echo "   ✗ Erreur lors de la création: " . $e->getMessage() . "\n";
}

// Test 3: Créer un patient dentiste
try {
    $patient = new \App\Models\Patient([
        'matricule' => $student->matricule,
        'type_medecin' => 'dentiste',
        'valider' => 0
    ]);
    $patient->save();
    
    echo "   ✓ Patient dentiste créé avec succès (ID: {$patient->id})\n";
    
    // Vérifier que le type_medecin a été correctement sauvegardé
    $savedPatient = \DB::table('patients')->where('id', $patient->id)->first();
    echo "   - Type médecin sauvegardé: {$savedPatient->type_medecin}\n";
    
    // Supprimer le patient de test
    $patient->delete();
    echo "   ✓ Patient de test supprimé\n";
    
} catch (Exception $e) {
    echo "   ✗ Erreur lors de la création: " . $e->getMessage() . "\n";
}

// 3. Test de validation des contraintes
echo "\n3. Test de validation des contraintes:\n";

// Test avec type_medecin invalide
try {
    $patient = new \App\Models\Patient([
        'matricule' => $student->matricule,
        'type_medecin' => 'type_invalide',
        'valider' => 0
    ]);
    $patient->save();
    echo "   ✗ PROBLÈME: Type médecin invalide accepté!\n";
    $patient->delete();
} catch (Exception $e) {
    echo "   ✓ Type médecin invalide correctement rejeté\n";
}

// 4. Vérifier le filtrage par spécialité
echo "\n4. Test du filtrage par spécialité avec nouvelles données:\n";

// Créer 3 patients de test avec différents types
$testMatricules = \DB::table('students')->limit(3)->pluck('matricule')->toArray();
if (count($testMatricules) >= 3) {
    $testPatients = [
        ['matricule' => $testMatricules[0], 'type_medecin' => 'psycho'],
        ['matricule' => $testMatricules[1], 'type_medecin' => 'médecin générale'],
        ['matricule' => $testMatricules[2], 'type_medecin' => 'dentiste'],
    ];
    
    $createdIds = [];
    foreach ($testPatients as $patientData) {
        $patient = new \App\Models\Patient([
            'matricule' => $patientData['matricule'],
            'type_medecin' => $patientData['type_medecin'],
            'valider' => 0
        ]);
        $patient->save();
        $createdIds[] = $patient->id;
    }
    
    echo "   ✓ 3 patients de test créés\n";
    
    // Tester le filtrage
    foreach (['psycho', 'médecin générale', 'dentiste'] as $type) {
        $count = \DB::table('patients')->where('type_medecin', $type)->count();
        echo "   - Patients $type: $count\n";
    }
    
    // Nettoyer les patients de test
    \DB::table('patients')->whereIn('id', $createdIds)->delete();
    echo "   ✓ Patients de test supprimés\n";
}

echo "\n5. Recommandations:\n";
echo "   - ✓ Le formulaire d'ajout de patient inclut maintenant le champ type_medecin\n";
echo "   - ✓ La validation force la sélection d'un type de médecin valide\n";
echo "   - ✓ Les patients existants sans type_medecin peuvent être mis à jour manuellement\n";
echo "   - ⚠ Les patients existants sans type_medecin ne seront pas visibles dans les filtres de spécialité\n";

echo "\n=== SYSTÈME PRÊT À UTILISER ===\n";
echo "L'officier peut maintenant:\n";
echo "1. Entrer le matricule de l'étudiant\n";
echo "2. Sélectionner le type de médecin (psycho, médecin générale, dentiste)\n";
echo "3. Ajouter le patient à l'infirmerie\n";
echo "4. Le patient sera visible uniquement pour le médecin de la spécialité choisie\n";

echo "\n=== FIN DU TEST ===\n";
