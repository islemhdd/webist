<?php
/**
 * Diagnostic complet - Pourquoi aucun rendez-vous ne s'affiche
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔍 DIAGNOSTIC COMPLET - Rendez-vous non affichés\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // 1. Vérifier le contenu de la table liste_rdvs
    echo "1️⃣  CONTENU DE LA TABLE liste_rdvs\n";
    echo str_repeat("-", 40) . "\n";

    $rdvs = \DB::table('liste_rdvs')->get();
    echo "📊 Nombre total de RDV: " . $rdvs->count() . "\n";

    if ($rdvs->count() > 0) {
        echo "\n📋 Premiers 5 RDV:\n";
        foreach ($rdvs->take(5) as $rdv) {
            echo "   ID: {$rdv->matricule}, Motif: {$rdv->motif}, Date: {$rdv->date}\n";
        }
    } else {
        echo "   ⚠️  Aucun rendez-vous dans la table!\n";
    }

    // 2. Vérifier les dates
    echo "\n📅 RÉPARTITION PAR DATE:\n";
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $rdvsToday = \DB::table('liste_rdvs')->whereDate('date', $today)->count();
    $rdvsFuture = \DB::table('liste_rdvs')->whereDate('date', '>', $today)->count();
    $rdvsPast = \DB::table('liste_rdvs')->whereDate('date', '<', $today)->count();

    echo "   Aujourd'hui ($today): $rdvsToday\n";
    echo "   Futur: $rdvsFuture\n";
    echo "   Passé: $rdvsPast\n";

    // 3. Tester la logique du contrôleur
    echo "\n2️⃣  TEST DE LA LOGIQUE DU CONTRÔLEUR\n";
    echo str_repeat("-", 40) . "\n";

    // Simuler une requête sans filtres
    $query = \App\Models\ListeRdv::with('student');
    $query->whereDate('date', \Carbon\Carbon::today());
    $rdvsController = $query->get();

    echo "📊 RDV trouvés par le contrôleur (aujourd'hui): " . $rdvsController->count() . "\n";

    // Tester avec toutes les dates
    $queryAll = \App\Models\ListeRdv::with('student');
    $rdvsAll = $queryAll->get();
    echo "📊 RDV trouvés sans filtre de date: " . $rdvsAll->count() . "\n";

    // 4. Vérifier la relation avec students
    echo "\n3️⃣  VÉRIFICATION DE LA RELATION STUDENT\n";
    echo str_repeat("-", 40) . "\n";

    $rdvWithStudent = \DB::table('liste_rdvs')
        ->join('students', 'liste_rdvs.matricule', '=', 'students.matricule')
        ->select('liste_rdvs.*', 'students.nom', 'students.prenom')
        ->first();

    if ($rdvWithStudent) {
        echo "   ✅ Relation avec students fonctionne\n";
        echo "   Exemple: {$rdvWithStudent->nom} {$rdvWithStudent->prenom} ({$rdvWithStudent->matricule})\n";
    } else {
        echo "   ❌ Problème de relation avec students\n";

        // Vérifier si il y a des matricules qui ne correspondent pas
        $rdvMatricules = \DB::table('liste_rdvs')->pluck('matricule')->unique();
        $studentMatricules = \DB::table('students')->pluck('matricule')->unique();

        $missingStudents = $rdvMatricules->diff($studentMatricules);
        if ($missingStudents->count() > 0) {
            echo "   ⚠️  Matricules de RDV sans étudiant correspondant: " . $missingStudents->implode(', ') . "\n";
        }
    }

    // 5. Test du filtre de date par défaut
    echo "\n4️⃣  PROBLÈME POTENTIEL: FILTRE DE DATE\n";
    echo str_repeat("-", 40) . "\n";

    echo "📅 Date d'aujourd'hui: $today\n";

    // Vérifier les dates des RDV existants
    $distinctDates = \DB::table('liste_rdvs')
        ->selectRaw('DATE(date) as date_only, COUNT(*) as count')
        ->groupBy('date_only')
        ->orderBy('date_only')
        ->get();

    echo "📊 Dates avec des RDV:\n";
    foreach ($distinctDates as $dateInfo) {
        $isToday = $dateInfo->date_only === $today ? " ← AUJOURD'HUI" : "";
        echo "   {$dateInfo->date_only}: {$dateInfo->count} RDV$isToday\n";
    }

    // 6. Solution proposée
    echo "\n5️⃣  SOLUTION RECOMMANDÉE\n";
    echo str_repeat("-", 40) . "\n";

    if ($rdvsToday === 0 && $rdvs->count() > 0) {
        echo "🎯 PROBLÈME IDENTIFIÉ:\n";
        echo "   Le contrôleur filtre par défaut sur aujourd'hui\n";
        echo "   Mais tous les RDV sont à d'autres dates\n\n";

        echo "💡 SOLUTIONS:\n";
        echo "   1. Modifier le contrôleur pour afficher tous les RDV par défaut\n";
        echo "   2. Ou créer des RDV de test pour aujourd'hui\n";
        echo "   3. Ou modifier la vue pour indiquer qu'il faut changer la date\n";
    }

    if ($rdvs->count() === 0) {
        echo "🎯 PROBLÈME IDENTIFIÉ:\n";
        echo "   Aucun rendez-vous dans la base de données\n\n";

        echo "💡 SOLUTION:\n";
        echo "   Créer des données de test\n";
    }

    // 7. Créer des données de test si nécessaire
    if ($rdvs->count() === 0) {
        echo "\n6️⃣  CRÉATION DE DONNÉES DE TEST\n";
        echo str_repeat("-", 40) . "\n";

        // Vérifier qu'il y a des étudiants
        $studentsCount = \DB::table('students')->count();
        echo "👥 Étudiants disponibles: $studentsCount\n";

        if ($studentsCount > 0) {
            $student = \DB::table('students')->first();

            try {
                \DB::table('liste_rdvs')->insert([
                    'type_medecin' => 'chef_médecin',
                    'matricule' => $student->matricule,
                    'motif' => 'consultation',
                    'service' => 'cardiologie',
                    'date' => \Carbon\Carbon::today()->addHours(10)->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                echo "   ✅ RDV de test créé pour aujourd'hui\n";
                echo "   Matricule: {$student->matricule}\n";
                echo "   Date: " . \Carbon\Carbon::today()->addHours(10)->format('Y-m-d H:i:s') . "\n";

            } catch (Exception $e) {
                echo "   ❌ Erreur création: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ⚠️  Aucun étudiant disponible pour créer un RDV de test\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🏁 Diagnostic terminé!\n";
?>
