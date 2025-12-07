<?php
/**
 * Correction - Créer des RDV avec des matricules d'étudiants réels
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔧 CORRECTION - Création de RDV avec vrais étudiants\n";
echo str_repeat("=", 55) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // 1. Supprimer les RDV de test avec matricules invalides
    echo "1️⃣  NETTOYAGE DES RDV AVEC MATRICULES INVALIDES\n";
    echo str_repeat("-", 50) . "\n";

    $rdvInvalides = \DB::table('liste_rdvs')
        ->whereIn('matricule', ['999001', '999002', '999003'])
        ->get();

    echo "📊 RDV avec matricules invalides: " . $rdvInvalides->count() . "\n";

    if ($rdvInvalides->count() > 0) {
        \DB::table('liste_rdvs')
            ->whereIn('matricule', ['999001', '999002', '999003'])
            ->delete();

        echo "✅ RDV avec matricules invalides supprimés\n";
    }

    // 2. Récupérer des étudiants réels
    echo "\n2️⃣  RÉCUPÉRATION D'ÉTUDIANTS RÉELS\n";
    echo str_repeat("-", 50) . "\n";

    $etudiants = \DB::table('students')->limit(5)->get();
    echo "👥 Étudiants disponibles: " . $etudiants->count() . "\n";

    if ($etudiants->count() > 0) {
        foreach ($etudiants->take(3) as $index => $etudiant) {
            echo "   Étudiant " . ($index + 1) . ": {$etudiant->matricule} - {$etudiant->nom} {$etudiant->prenom}\n";
        }
    } else {
        echo "❌ Aucun étudiant trouvé!\n";
        return;
    }

    // 3. Créer des RDV pour aujourd'hui avec de vrais étudiants
    echo "\n3️⃣  CRÉATION DE RDV POUR AUJOURD'HUI\n";
    echo str_repeat("-", 50) . "\n";

    $rdvData = [
        [
            'type_medecin' => 'psychologue',
            'motif' => 'consultation',
            'service' => 'psychiatrie',
            'heure' => '09:00'
        ],
        [
            'type_medecin' => 'dentiste',
            'motif' => 'urgences',
            'service' => 'chirurgie_dentaire',
            'heure' => '10:30'
        ],
        [
            'type_medecin' => 'médecin générale',
            'motif' => 'consultation',
            'service' => 'cardiologie',
            'heure' => '14:00'
        ]
    ];

    $today = \Carbon\Carbon::today();

    foreach ($rdvData as $index => $rdv) {
        if (isset($etudiants[$index])) {
            $etudiant = $etudiants[$index];

            // Vérifier si le RDV n'existe pas déjà
            $exists = \DB::table('liste_rdvs')
                ->where('matricule', $etudiant->matricule)
                ->where('motif', $rdv['motif'])
                ->where('service', $rdv['service'])
                ->whereDate('date', $today)
                ->exists();

            if (!$exists) {
                $dateTime = $today->copy()->setTimeFromTimeString($rdv['heure']);

                \DB::table('liste_rdvs')->insert([
                    'type_medecin' => $rdv['type_medecin'],
                    'matricule' => $etudiant->matricule,
                    'motif' => $rdv['motif'],
                    'service' => $rdv['service'],
                    'date' => $dateTime->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                echo "✅ RDV créé: {$etudiant->nom} {$etudiant->prenom} ({$etudiant->matricule})\n";
                echo "   Service: {$rdv['service']} | Motif: {$rdv['motif']} | Heure: {$rdv['heure']}\n";
            } else {
                echo "⚠️  RDV déjà existant pour {$etudiant->nom} {$etudiant->prenom}\n";
            }
        }
    }

    // 4. Vérification finale
    echo "\n4️⃣  VÉRIFICATION FINALE\n";
    echo str_repeat("-", 50) . "\n";

    $rdvAujourdhui = \App\Models\ListeRdv::with('student')
        ->whereDate('date', $today)
        ->get();

    echo "📊 RDV pour aujourd'hui: " . $rdvAujourdhui->count() . "\n\n";

    if ($rdvAujourdhui->count() > 0) {
        echo "📋 LISTE DES RDV AVEC ÉTUDIANTS VALIDES:\n";
        foreach ($rdvAujourdhui as $rdv) {
            $nomComplet = $rdv->student ?
                "{$rdv->student->nom} {$rdv->student->prenom}" :
                "ÉTUDIANT INTROUVABLE";

            echo "   • {$rdv->matricule} - $nomComplet\n";
            echo "     Service: {$rdv->service} | Motif: {$rdv->motif}\n";
            echo "     Date: {$rdv->date} | Type: {$rdv->type_medecin}\n\n";
        }

        echo "🎉 RENDEZ-VOUS PRÊTS À ÊTRE AFFICHÉS!\n";
        echo "   Accédez à la page liste des rendez-vous pour les voir\n";
    } else {
        echo "❌ Aucun RDV trouvé après correction\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
echo "🏁 Correction terminée!\n";
?>
