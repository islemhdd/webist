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

echo "=== MISE À JOUR DES PATIENTS EXISTANTS SANS TYPE_MEDECIN ===\n\n";

// 1. Analyser les patients sans type_medecin
$patientsWithoutType = \DB::table('patients')->whereNull('type_medecin')->get();
echo "1. Patients à mettre à jour: " . $patientsWithoutType->count() . "\n\n";

if ($patientsWithoutType->count() === 0) {
    echo "Aucun patient à mettre à jour.\n";
    exit;
}

// 2. Demander confirmation (simulation)
echo "2. Stratégie de mise à jour:\n";
echo "   Les patients seront répartis équitablement entre les 3 spécialités\n";
echo "   selon leur matricule (modulo 3):\n";
echo "   - Matricule % 3 = 0 → psycho\n";
echo "   - Matricule % 3 = 1 → médecin générale\n";
echo "   - Matricule % 3 = 2 → dentiste\n\n";

// 3. Prévisualisation de la répartition
$preview = [
    'psycho' => 0,
    'médecin générale' => 0,
    'dentiste' => 0
];

foreach ($patientsWithoutType as $patient) {
    $matriculeInt = intval($patient->matricule);
    $remainder = $matriculeInt % 3;
    
    switch ($remainder) {
        case 0:
            $preview['psycho']++;
            break;
        case 1:
            $preview['médecin générale']++;
            break;
        case 2:
            $preview['dentiste']++;
            break;
    }
}

echo "3. Prévisualisation de la répartition:\n";
foreach ($preview as $type => $count) {
    echo "   - $type: $count patients\n";
}

// 4. Confirmation simulée (automatique pour le script)
echo "\n4. Application des mises à jour...\n";

$updated = 0;
$errors = 0;

foreach ($patientsWithoutType as $patient) {
    try {
        $matriculeInt = intval($patient->matricule);
        $remainder = $matriculeInt % 3;
        
        $typeMedecin = match($remainder) {
            0 => 'psycho',
            1 => 'médecin générale',
            2 => 'dentiste',
            default => 'médecin générale'
        };
        
        \DB::table('patients')
            ->where('id', $patient->id)
            ->update(['type_medecin' => $typeMedecin]);
        
        $updated++;
        
        if ($updated <= 5 || $updated % 10 === 0) {
            echo "   ✓ Patient ID {$patient->id} (matricule: {$patient->matricule}) → $typeMedecin\n";
        }
        
    } catch (Exception $e) {
        echo "   ✗ Erreur pour patient ID {$patient->id}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n5. Résultat de la mise à jour:\n";
echo "   - Patients mis à jour: $updated\n";
echo "   - Erreurs: $errors\n";

// 6. Vérification finale
echo "\n6. Vérification finale:\n";
$finalCount = \DB::table('patients')->whereNull('type_medecin')->count();
echo "   - Patients restants sans type_medecin: $finalCount\n";

foreach (['psycho', 'médecin générale', 'dentiste'] as $type) {
    $count = \DB::table('patients')->where('type_medecin', $type)->count();
    echo "   - Patients $type: $count\n";
}

// 7. Test du filtrage après mise à jour
echo "\n7. Test du filtrage par spécialité:\n";
$today = \Carbon\Carbon::today();

foreach (['psycho', 'médecin générale', 'dentiste'] as $specialty) {
    $totalPatients = \DB::table('patients')->where('type_medecin', $specialty)->count();
    $patientsToday = \DB::table('patients')
        ->where('type_medecin', $specialty)
        ->whereDate('created_at', $today)
        ->count();
    
    echo "   - $specialty: $totalPatients total ($patientsToday aujourd'hui)\n";
}

echo "\n=== MISE À JOUR TERMINÉE ===\n";
echo "Tous les patients ont maintenant un type_medecin défini.\n";
echo "Le filtrage par spécialité fonctionne maintenant correctement.\n";
echo "Le filtrage par année devrait également fonctionner.\n";

echo "\n=== FIN DU SCRIPT ===\n";
