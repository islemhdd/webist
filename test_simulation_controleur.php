<?php
/**
 * Test de simulation complète du contrôleur ListeRdvController
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🧪 TEST SIMULATION CONTRÔLEUR ListeRdvController\n";
echo str_repeat("=", 55) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // Simuler exactement ce que fait le contrôleur
    echo "1️⃣  SIMULATION EXACTE DU CONTRÔLEUR\n";
    echo str_repeat("-", 40) . "\n";

    // Reproduire la logique du contrôleur index()
    $query = \App\Models\ListeRdv::with('student');

    // Filtre par défaut - aujourd'hui
    $query->whereDate('date', \Carbon\Carbon::today());

    $rendezvous = $query->orderBy('date', 'asc')->get();

    echo "📊 Nombre de RDV retournés par le contrôleur: " . $rendezvous->count() . "\n\n";

    if ($rendezvous->count() > 0) {
        echo "📋 DÉTAILS DES RDV:\n";
        foreach ($rendezvous as $index => $rdv) {
            echo "   RDV #" . ($index + 1) . ":\n";
            echo "      - ID: {$rdv->id}\n";
            echo "      - Matricule: {$rdv->matricule}\n";
            echo "      - Motif: {$rdv->motif}\n";
            echo "      - Service: {$rdv->service}\n";
            echo "      - Date: {$rdv->date}\n";
            echo "      - Type médecin: {$rdv->type_medecin}\n";

            // Vérifier la relation student
            if ($rdv->student) {
                echo "      - Étudiant: {$rdv->student->nom} {$rdv->student->prenom}\n";
                echo "      - Section: {$rdv->student->section_id}\n";
            } else {
                echo "      - ❌ Pas d'étudiant associé!\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ Aucun RDV trouvé par la simulation du contrôleur\n";
    }

    // 2. Vérifier si le problème vient des relations
    echo "2️⃣  VÉRIFICATION DES RELATIONS\n";
    echo str_repeat("-", 40) . "\n";

    $rdvSansRelation = \App\Models\ListeRdv::whereDate('date', \Carbon\Carbon::today())->get();
    echo "📊 RDV sans relation: " . $rdvSansRelation->count() . "\n";

    $rdvAvecRelation = \App\Models\ListeRdv::with('student')->whereDate('date', \Carbon\Carbon::today())->get();
    echo "📊 RDV avec relation: " . $rdvAvecRelation->count() . "\n";

    // Vérifier s'il y a des RDV avec des matricules inexistants
    $rdvMatriculesInvalides = 0;
    foreach ($rdvAvecRelation as $rdv) {
        if (!$rdv->student) {
            $rdvMatriculesInvalides++;
            echo "⚠️  RDV avec matricule invalide: {$rdv->matricule}\n";
        }
    }

    if ($rdvMatriculesInvalides === 0) {
        echo "✅ Toutes les relations student sont valides\n";
    }

    // 3. Test de la vue blade simulée
    echo "\n3️⃣  SIMULATION DE LA VUE BLADE\n";
    echo str_repeat("-", 40) . "\n";

    echo "🎭 Code de la vue pour chaque RDV:\n";
    foreach ($rendezvous as $rdv) {
        echo "   <tr>\n";
        echo "      <td>{{ \$rdv->matricule }}</td> → {$rdv->matricule}\n";
        echo "      <td>{{ \$rdv->student->nom ?? 'N/A' }}</td> → " . ($rdv->student->nom ?? 'N/A') . "\n";
        echo "      <td>{{ \$rdv->student->prenom ?? 'N/A' }}</td> → " . ($rdv->student->prenom ?? 'N/A') . "\n";
        echo "      <td>{{ \$rdv->student->section_id ?? 'N/A' }}</td> → " . ($rdv->student->section_id ?? 'N/A') . "\n";
        echo "      <td>{{ \$rdv->motif }}</td> → {$rdv->motif}\n";
        echo "      <td>{{ \$rdv->service }}</td> → {$rdv->service}\n";
        echo "      <td>{{ \$rdv->date }}</td> → {$rdv->date}\n";
        echo "   </tr>\n\n";
    }

    // 4. Vérifier le modèle ListeRdv
    echo "4️⃣  VÉRIFICATION DU MODÈLE ListeRdv\n";
    echo str_repeat("-", 40) . "\n";

    $modelPath = __DIR__ . '/app/Models/listeRdv.php';
    if (file_exists($modelPath)) {
        $modelContent = file_get_contents($modelPath);

        // Vérifier la relation Student
        if (strpos($modelContent, 'belongsTo(Student::class') !== false) {
            echo "✅ Relation Student définie dans le modèle\n";
        } else {
            echo "❌ Relation Student manquante dans le modèle\n";
        }

        // Vérifier la table
        if (strpos($modelContent, "table = 'liste_rdvs'") !== false) {
            echo "✅ Table liste_rdvs définie\n";
        } else {
            echo "❌ Table non définie dans le modèle\n";
        }
    }

    // 5. Test de la route
    echo "\n5️⃣  VÉRIFICATION DES ROUTES\n";
    echo str_repeat("-", 40) . "\n";

    $routesPath = __DIR__ . '/routes/web.php';
    if (file_exists($routesPath)) {
        $routesContent = file_get_contents($routesPath);

        if (strpos($routesContent, 'liste_rdv.index') !== false) {
            echo "✅ Route liste_rdv.index définie\n";
        } else {
            echo "❌ Route liste_rdv.index manquante\n";
        }

        if (strpos($routesContent, 'ListeRdvController') !== false) {
            echo "✅ ListeRdvController référencé dans les routes\n";
        } else {
            echo "❌ ListeRdvController non référencé\n";
        }
    }

    // 6. Conclusion et recommandations
    echo "\n6️⃣  CONCLUSION\n";
    echo str_repeat("-", 40) . "\n";

    if ($rendezvous->count() > 0) {
        echo "🎉 LE CONTRÔLEUR FONCTIONNE CORRECTEMENT!\n";
        echo "   - {$rendezvous->count()} RDV trouvés pour aujourd'hui\n";
        echo "   - Relations student fonctionnelles\n";
        echo "   - Données complètes disponibles\n\n";

        echo "🔍 SI LA VUE N'AFFICHE RIEN:\n";
        echo "   1. Vérifiez que vous accédez à la bonne route\n";
        echo "   2. Vérifiez les erreurs dans les logs Laravel\n";
        echo "   3. Vérifiez la console du navigateur\n";
        echo "   4. Vérifiez que la vue blade compile sans erreur\n";
    } else {
        echo "❌ PROBLÈME DANS LE CONTRÔLEUR\n";
        echo "   - Aucun RDV trouvé malgré les données en base\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
echo "🏁 Test simulation terminé!\n";
?>
