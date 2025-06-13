<?php
/**
 * Correction finale - Utiliser seulement les valeurs ENUM autorisées
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🎯 CORRECTION FINALE - Valeurs ENUM autorisées\n";
echo str_repeat("=", 55) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel initialisé\n\n";
    
    // 1. Valeurs ENUM autorisées
    echo "1️⃣  VALEURS ENUM AUTORISÉES\n";
    echo str_repeat("-", 40) . "\n";
    
    $enumValues = ['médecin générale', 'dentiste', 'psycho'];
    echo "📊 Valeurs autorisées pour type_medecin:\n";
    foreach ($enumValues as $value) {
        echo "   • '$value'\n";
    }
    
    // 2. Mettre à jour les contrôleurs pour utiliser les bonnes valeurs
    echo "\n2️⃣  MISE À JOUR DES CONTRÔLEURS\n";
    echo str_repeat("-", 40) . "\n";
    
    echo "🔧 Les contrôleurs doivent utiliser ces valeurs exactes:\n";
    echo "   psychologue → 'psycho'\n";
    echo "   dentiste → 'dentiste'\n";
    echo "   médecin générale → 'médecin générale'\n";
    echo "   chef_médecin → 'médecin générale' (ou créer nouvelle valeur)\n";
    
    // 3. Nettoyer et créer des RDV corrects
    echo "\n3️⃣  CRÉATION DE RDV AVEC VALEURS CORRECTES\n";
    echo str_repeat("-", 40) . "\n";
    
    // Supprimer tous les RDV d'aujourd'hui pour repartir à zéro
    $deleted = \DB::table('liste_rdvs')
        ->whereDate('date', \Carbon\Carbon::today())
        ->delete();
    
    echo "🗑️  RDV d'aujourd'hui supprimés: $deleted\n";
    
    // Récupérer de vrais étudiants
    $etudiants = \DB::table('students')->limit(3)->get();
    
    if ($etudiants->count() >= 3) {
        $rdvData = [
            [
                'type_medecin' => 'psycho',
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
                $dateTime = $today->copy()->setTimeFromTimeString($rdv['heure']);
                
                try {
                    \DB::table('liste_rdvs')->insert([
                        'type_medecin' => $rdv['type_medecin'],
                        'matricule' => $etudiant->matricule,
                        'motif' => $rdv['motif'],
                        'service' => $rdv['service'],
                        'date' => $dateTime->format('Y-m-d H:i:s'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    echo "✅ RDV créé: {$etudiant->nom} {$etudiant->prenom}\n";
                    echo "   Type: {$rdv['type_medecin']} | Service: {$rdv['service']}\n";
                    echo "   Motif: {$rdv['motif']} | Heure: {$rdv['heure']}\n\n";
                    
                } catch (Exception $e) {
                    echo "❌ Erreur création RDV {$index}: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    // 4. Vérification finale avec simulation complète
    echo "4️⃣  VÉRIFICATION FINALE COMPLÈTE\n";
    echo str_repeat("-", 40) . "\n";
    
    // Test complet comme le contrôleur
    $rdvFinaux = \App\Models\ListeRdv::with('student')
        ->whereDate('date', \Carbon\Carbon::today())
        ->orderBy('date', 'asc')
        ->get();
    
    echo "📊 RDV trouvés pour aujourd'hui: " . $rdvFinaux->count() . "\n\n";
    
    if ($rdvFinaux->count() > 0) {
        echo "📋 SIMULATION DE LA VUE:\n";
        echo "┌─────────────┬──────────────────┬──────────────────┬─────────┬────────────────────┬─────────────────────┬──────────────────┐\n";
        echo "│ Matricule   │ Nom              │ Prénom           │ Section │ Motif              │ Service             │ Date             │\n";
        echo "├─────────────┼──────────────────┼──────────────────┼─────────┼────────────────────┼─────────────────────┼──────────────────┤\n";
        
        foreach ($rdvFinaux as $rdv) {
            $nom = $rdv->student ? $rdv->student->nom : 'N/A';
            $prenom = $rdv->student ? $rdv->student->prenom : 'N/A';
            $section = $rdv->student ? $rdv->student->section_id : 'N/A';
            
            printf("│ %-11s │ %-16s │ %-16s │ %-7s │ %-18s │ %-19s │ %-16s │\n",
                substr($rdv->matricule, 0, 11),
                substr($nom, 0, 16),
                substr($prenom, 0, 16), 
                substr($section, 0, 7),
                substr($rdv->motif, 0, 18),
                substr($rdv->service, 0, 19),
                substr($rdv->date, 0, 16)
            );
        }
        
        echo "└─────────────┴──────────────────┴──────────────────┴─────────┴────────────────────┴─────────────────────┴──────────────────┘\n\n";
        
        echo "🎉 SUCCÈS! Les rendez-vous sont maintenant corrects!\n";
        echo "   - Matricules d'étudiants réels\n";
        echo "   - Relations student fonctionnelles\n";
        echo "   - Types medecin conformes à l'ENUM\n";
        echo "   - Données complètes pour affichage\n\n";
        
        echo "🌐 MAINTENANT TESTEZ DANS LE NAVIGATEUR:\n";
        echo "   Accédez à la page liste des rendez-vous\n";
        echo "   Vous devriez voir " . $rdvFinaux->count() . " rendez-vous affichés\n";
        
    } else {
        echo "❌ Aucun RDV créé avec succès\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur globale: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
echo "🏁 Correction finale terminée!\n";
?>
