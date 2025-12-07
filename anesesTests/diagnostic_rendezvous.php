<?php
/**
 * Diagnostic des rendez-vous qui ne s'affichent pas
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔍 DIAGNOSTIC - Problème d'affichage des rendez-vous\n";
echo str_repeat("=", 55) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // Test 1: Vérifier la structure de la table
    echo "1️⃣  STRUCTURE DE LA TABLE liste_rdvs\n";
    echo str_repeat("-", 40) . "\n";

    $columns = \DB::select("DESCRIBE liste_rdvs");
    echo "Colonnes actuelles:\n";
    foreach ($columns as $col) {
        echo "   • {$col->Field} ({$col->Type})\n";
    }

    echo "\n";

    // Test 2: Compter les rendez-vous
    echo "2️⃣  NOMBRE DE RENDEZ-VOUS EN BASE\n";
    echo str_repeat("-", 40) . "\n";

    $totalRdv = \DB::table('liste_rdvs')->count();
    echo "Total des rendez-vous: $totalRdv\n";

    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $todayRdv = \DB::table('liste_rdvs')->whereDate('date', $today)->count();
    echo "Rendez-vous d'aujourd'hui ($today): $todayRdv\n";

    echo "\n";

    // Test 3: Vérifier les données récentes
    echo "3️⃣  DERNIERS RENDEZ-VOUS CRÉÉS\n";
    echo str_repeat("-", 40) . "\n";

    $recentRdv = \DB::table('liste_rdvs')
                   ->orderBy('created_at', 'desc')
                   ->limit(5)
                   ->get();

    if ($recentRdv->count() > 0) {
        foreach ($recentRdv as $rdv) {
            echo "📅 RDV ID: {$rdv->matricule}\n";
            echo "   Date: {$rdv->date}\n";
            echo "   Type médecin: " . ($rdv->type_medecin ?? 'NULL') . "\n";
            echo "   Motif: {$rdv->motif}\n";
            echo "   Service: {$rdv->service}\n";

            // Vérifier si nom/prenom existent dans la table
            if (property_exists($rdv, 'nom')) {
                echo "   Nom: " . ($rdv->nom ?? 'NULL') . "\n";
            } else {
                echo "   ⚠️  Champ 'nom' absent de la table\n";
            }

            if (property_exists($rdv, 'prenom')) {
                echo "   Prénom: " . ($rdv->prenom ?? 'NULL') . "\n";
            } else {
                echo "   ⚠️  Champ 'prenom' absent de la table\n";
            }

            echo "   Créé: {$rdv->created_at}\n";
            echo str_repeat("-", 30) . "\n";
        }
    } else {
        echo "❌ Aucun rendez-vous trouvé\n";
    }

    echo "\n";

    // Test 4: Test de la relation avec students
    echo "4️⃣  TEST DE LA RELATION AVEC STUDENTS\n";
    echo str_repeat("-", 40) . "\n";

    // Utiliser Eloquent avec relation
    $rdvWithStudents = \App\Models\ListeRdv::with('student')->limit(3)->get();

    if ($rdvWithStudents->count() > 0) {
        foreach ($rdvWithStudents as $rdv) {
            echo "🔗 RDV Matricule: {$rdv->matricule}\n";

            if ($rdv->student) {
                echo "   ✅ Étudiant trouvé: {$rdv->student->nom} {$rdv->student->prenom}\n";
                echo "   Section: {$rdv->student->section_id}\n";
            } else {
                echo "   ❌ Aucun étudiant trouvé pour matricule {$rdv->matricule}\n";

                // Vérifier si l'étudiant existe
                $studentExists = \DB::table('students')->where('matricule', $rdv->matricule)->exists();
                echo "   Étudiant existe en base: " . ($studentExists ? 'OUI' : 'NON') . "\n";
            }
            echo str_repeat("-", 30) . "\n";
        }
    } else {
        echo "❌ Aucun rendez-vous avec relation trouvé\n";
    }

    echo "\n";

    // Test 5: Simuler le contrôleur
    echo "5️⃣  SIMULATION DU CONTRÔLEUR\n";
    echo str_repeat("-", 40) . "\n";

    // Simuler la même requête que le contrôleur
    $query = \App\Models\ListeRdv::with('student');
    $query->whereDate('date', \Carbon\Carbon::today());
    $simulatedResults = $query->get();

    echo "Simulation contrôleur (RDV d'aujourd'hui): {$simulatedResults->count()} résultats\n";

    if ($simulatedResults->count() > 0) {
        echo "   ✅ Des rendez-vous devraient s'afficher\n";
    } else {
        echo "   ⚠️  Aucun RDV pour aujourd'hui\n";

        // Tester avec toutes les dates
        $allResults = \App\Models\ListeRdv::with('student')->get();
        echo "   Total tous rendez-vous: {$allResults->count()}\n";

        if ($allResults->count() > 0) {
            $latestDate = $allResults->max('date');
            echo "   Dernière date de RDV: $latestDate\n";
        }
    }

    echo "\n";

    // Test 6: Diagnostic de la vue
    echo "6️⃣  PROBLÈME DÉTECTÉ DANS LA VUE\n";
    echo str_repeat("-", 40) . "\n";

    echo "🐛 PROBLÈME IDENTIFIÉ:\n";
    echo "   La vue liste_rendezvous.blade.php essaie d'accéder à:\n";
    echo "   • \$rdv->nom (n'existe plus dans la table)\n";
    echo "   • \$rdv->prenom (n'existe plus dans la table)\n";
    echo "   • \$rdv->section_id (n'existe plus dans la table)\n\n";

    echo "✅ SOLUTION:\n";
    echo "   La vue doit utiliser la relation 'student':\n";
    echo "   • \$rdv->student->nom\n";
    echo "   • \$rdv->student->prenom\n";
    echo "   • \$rdv->student->section_id\n\n";

    echo "🔧 CORRECTION NÉCESSAIRE:\n";
    echo "   Mettre à jour la vue pour utiliser \$rdv->student->...\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
echo "🏁 Diagnostic terminé!\n";
?>
