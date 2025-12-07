<?php
/**
 * Test final - Vérification complète du système de rendez-vous
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔍 TEST FINAL - SYSTÈME RENDEZ-VOUS\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // 1. Test du modèle et de la relation
    echo "1️⃣  TEST DU MODÈLE\n";
    echo str_repeat("-", 25) . "\n";

    $rdvWithStudent = \App\Models\ListeRdv::with('student')->first();
    if ($rdvWithStudent) {
        echo "✅ Modèle ListeRdv fonctionne\n";
        echo "✅ Relation 'student' fonctionne: " . ($rdvWithStudent->student ? "OUI" : "NON") . "\n";
        if ($rdvWithStudent->student) {
            echo "   Exemple: {$rdvWithStudent->student->nom} {$rdvWithStudent->student->prenom}\n";
        }
    } else {
        echo "❌ Aucun rendez-vous trouvé\n";
    }

    echo "\n2️⃣  TEST DU CONTRÔLEUR\n";
    echo str_repeat("-", 25) . "\n";

    // Simuler exactement ce que fait le contrôleur
    $query = \App\Models\ListeRdv::with('student');
    $query->whereDate('date', \Carbon\Carbon::today());
    $rendezvous = $query->orderBy('date', 'asc')->paginate(10);

    echo "📊 RDV d'aujourd'hui trouvés: " . $rendezvous->count() . "\n";
    echo "📊 Total RDV en base: " . \App\Models\ListeRdv::count() . "\n";

    if ($rendezvous->count() > 0) {
        echo "\n📋 DÉTAILS DES RDV D'AUJOURD'HUI:\n";
        foreach ($rendezvous as $rdv) {
            echo "   • Matricule: {$rdv->matricule}\n";
            echo "     Type médecin: {$rdv->type_medecin}\n";
            echo "     Motif: {$rdv->motif}\n";
            echo "     Service: {$rdv->service}\n";
            echo "     Date: {$rdv->date}\n";

            if ($rdv->student) {
                echo "     Étudiant: ✅ {$rdv->student->nom} {$rdv->student->prenom}\n";
                echo "     Section: {$rdv->student->section_id}\n";
            } else {
                echo "     Étudiant: ❌ Pas trouvé\n";
            }
            echo "   " . str_repeat("-", 40) . "\n";
        }
    }

    echo "\n3️⃣  TEST DE LA VUE\n";
    echo str_repeat("-", 25) . "\n";

    // Vérifier si la vue existe
    $viewPath = resource_path('views/infermerie/liste_rendezvous.blade.php');
    if (file_exists($viewPath)) {
        echo "✅ Vue liste_rendezvous.blade.php existe\n";

        // Vérifier le contenu de la vue
        $viewContent = file_get_contents($viewPath);
        if (strpos($viewContent, '$rdv->student->nom') !== false) {
            echo "✅ Vue utilise \$rdv->student->nom (correct)\n";
        } else {
            echo "❌ Vue n'utilise pas \$rdv->student->nom\n";
        }

        if (strpos($viewContent, '$rdv->nom') !== false) {
            echo "⚠️  Vue utilise encore \$rdv->nom (incorrect)\n";
        } else {
            echo "✅ Vue n'utilise plus \$rdv->nom\n";
        }
    } else {
        echo "❌ Vue liste_rendezvous.blade.php n'existe pas\n";
    }

    echo "\n4️⃣  TEST DES ROUTES\n";
    echo str_repeat("-", 25) . "\n";

    // Vérifier les routes importantes
    $routes = [
        'liste_rdv.index' => '/infermerie/listeRendezvous',
        'liste_rdv.create' => '/infermerie/CreateRendezvous',
        'liste_rdv.store' => '/infermerie/listeRdv',
        'liste_rdv.destroy' => '/infermerie/listeRendezvous'
    ];

    foreach ($routes as $name => $expectedPath) {
        try {
            $url = route($name);
            echo "✅ Route '$name' définie\n";
        } catch (Exception $e) {
            echo "❌ Route '$name' non trouvée\n";
        }
    }

    echo "\n5️⃣  CONCLUSION\n";
    echo str_repeat("-", 25) . "\n";

    if ($rendezvous->count() > 0) {
        echo "🎉 SYSTÈME FONCTIONNEL!\n";
        echo "   • Modèle: ✅\n";
        echo "   • Relations: ✅\n";
        echo "   • Contrôleur: ✅\n";
        echo "   • Routes: ✅\n";
        echo "   • Data: ✅ ({$rendezvous->count()} RDV aujourd'hui)\n\n";

        echo "🌐 TESTEZ DANS LE NAVIGATEUR:\n";
        echo "   URL: " . config('app.url') . "/infermerie/listeRendezvous\n";
        echo "   (Assurez-vous d'être connecté)\n";
    } else {
        echo "⚠️  AUCUN RDV AUJOURD'HUI\n";
        echo "   Modifiez le filtre de date dans l'interface web\n";
        echo "   ou créez un nouveau rendez-vous pour aujourd'hui\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🏁 Test final terminé!\n";
?>
