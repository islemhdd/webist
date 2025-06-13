<?php
/**
 * Test rapide pour vérifier l'affichage des rendez-vous
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔍 TEST AFFICHAGE RENDEZ-VOUS\n";
echo str_repeat("=", 40) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // Test exactement comme le contrôleur
    echo "📋 TEST CONTRÔLEUR ListeRdv::index()\n";
    echo str_repeat("-", 35) . "\n";

    $query = \App\Models\ListeRdv::with('student');
    $query->whereDate('date', \Carbon\Carbon::today());
    $rendezvous = $query->orderBy('date', 'asc')->get();

    echo "📊 Nombre de RDV d'aujourd'hui: " . $rendezvous->count() . "\n\n";

    if ($rendezvous->count() > 0) {
        foreach ($rendezvous as $rdv) {
            echo "🩺 RDV Matricule: {$rdv->matricule}\n";
            echo "   Motif: {$rdv->motif}\n";
            echo "   Service: {$rdv->service}\n";
            echo "   Type médecin: {$rdv->type_medecin}\n";
            echo "   Date: {$rdv->date}\n";

            if ($rdv->student) {
                echo "   ✅ Étudiant: {$rdv->student->nom} {$rdv->student->prenom}\n";
                echo "   Section: {$rdv->student->section_id}\n";
            } else {
                echo "   ❌ Pas d'étudiant trouvé\n";
            }
            echo str_repeat("-", 30) . "\n";
        }

        echo "\n✅ CONCLUSION: Les rendez-vous devraient s'afficher!\n";
        echo "Si ça ne marche pas dans le navigateur, vérifiez:\n";
        echo "1. L'authentification (êtes-vous connecté?)\n";
        echo "2. Les routes (URL correcte?)\n";
        echo "3. Les erreurs Laravel (vérifiez les logs)\n";

    } else {
        echo "❌ Aucun rendez-vous trouvé pour aujourd'hui\n";

        // Vérifier s'il y a des RDV en général
        $totalRdv = \App\Models\ListeRdv::count();
        echo "📊 Total RDV en base: $totalRdv\n";

        if ($totalRdv > 0) {
            $latestRdv = \App\Models\ListeRdv::orderBy('date', 'desc')->first();
            echo "📅 Dernier RDV le: {$latestRdv->date}\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 40) . "\n";
echo "🏁 Test terminé!\n";
?>
