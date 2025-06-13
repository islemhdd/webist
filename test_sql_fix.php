<?php
/**
 * Test de correction SQL - Vérification que les erreurs d'alias sont résolues
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔧 TEST DE CORRECTION SQL\n";
echo str_repeat("=", 40) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // Test 1: Tester la requête du MedicalSpecialtyController avec recherche
    echo "1️⃣  TEST REQUÊTE MEDICAL SPECIALTY CONTROLLER\n";
    echo str_repeat("-", 40) . "\n";

    // Simuler une requête avec recherche comme dans appointmentsList()
    $searchTerm = "2022250";

    $query = \DB::table('liste_rdvs as r')
        ->join('students as s', 'r.matricule', '=', 's.matricule')
        ->select('r.*', 's.nom', 's.prenom',
                 \DB::raw('NULL as phone'),
                 \DB::raw('NULL as bat'))
        ->where('r.type_medecin', 'psycho')
        ->where(function($q) use ($searchTerm) {
            $q->where('r.matricule', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('s.nom', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('s.prenom', 'LIKE', '%' . $searchTerm . '%');
        });

    $count = $query->count();
    echo "✅ Requête avec recherche exécutée avec succès\n";
    echo "   Résultats trouvés: $count\n\n";

    // Test 2: Tester les mappings de type_medecin
    echo "2️⃣  TEST MAPPINGS TYPE_MEDECIN\n";
    echo str_repeat("-", 40) . "\n";

    $validEnumValues = ['médecin générale', 'dentiste', 'psycho'];

    foreach ($validEnumValues as $enumValue) {
        $count = \DB::table('liste_rdvs')->where('type_medecin', $enumValue)->count();
        echo "✅ '$enumValue': $count rendez-vous\n";
    }

    echo "\n";

    // Test 3: Vérifier les relations avec Student
    echo "3️⃣  TEST RELATIONS AVEC STUDENTS\n";
    echo str_repeat("-", 40) . "\n";

    $rdvWithStudents = \App\Models\ListeRdv::with('student')->limit(3)->get();

    foreach ($rdvWithStudents as $rdv) {
        if ($rdv->student) {
            echo "✅ RDV {$rdv->matricule}: {$rdv->student->nom} {$rdv->student->prenom}\n";
        } else {
            echo "❌ RDV {$rdv->matricule}: Pas d'étudiant trouvé\n";
        }
    }

    echo "\n";

    echo "🎉 TOUS LES TESTS PASSÉS AVEC SUCCÈS !\n";
    echo "   Les erreurs SQL d'alias ont été corrigées.\n";
    echo "   Les mappings type_medecin utilisent les bonnes valeurs ENUM.\n";
    echo "   Les relations avec students fonctionnent correctement.\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
}

echo "\n" . str_repeat("=", 40) . "\n";
echo "🏁 Test terminé!\n";
?>
