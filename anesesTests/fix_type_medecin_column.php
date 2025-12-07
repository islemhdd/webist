<?php
/**
 * Vérification et correction de la colonne type_medecin
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🔧 VÉRIFICATION ET CORRECTION DE LA COLONNE type_medecin\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel initialisé\n\n";

    // 1. Vérifier la structure actuelle
    echo "1️⃣  STRUCTURE ACTUELLE DE LA TABLE\n";
    echo str_repeat("-", 40) . "\n";

    $columns = \DB::select("DESCRIBE liste_rdvs");

    foreach ($columns as $col) {
        if ($col->Field == 'type_medecin') {
            echo "📊 Colonne type_medecin:\n";
            echo "   Type: {$col->Type}\n";
            echo "   Null: {$col->Null}\n";
            echo "   Default: {$col->Default}\n";

            // Vérifier si c'est trop court
            if (strpos($col->Type, 'varchar') !== false) {
                preg_match('/varchar\((\d+)\)/', $col->Type, $matches);
                $length = isset($matches[1]) ? (int)$matches[1] : 0;

                echo "   Longueur actuelle: $length caractères\n";

                if ($length < 20) {
                    echo "   ⚠️  Trop court pour 'psychologue' (11 caractères)\n";
                    $needsResize = true;
                } else {
                    echo "   ✅ Longueur suffisante\n";
                    $needsResize = false;
                }
            }
            break;
        }
    }

    // 2. Modifier la colonne si nécessaire
    if (isset($needsResize) && $needsResize) {
        echo "\n2️⃣  MODIFICATION DE LA COLONNE\n";
        echo str_repeat("-", 40) . "\n";

        echo "🔧 Agrandissement de la colonne type_medecin...\n";

        try {
            \DB::statement("ALTER TABLE liste_rdvs MODIFY COLUMN type_medecin VARCHAR(50) DEFAULT 'chef_médecin'");
            echo "✅ Colonne modifiée avec succès\n";
        } catch (Exception $e) {
            echo "❌ Erreur modification: " . $e->getMessage() . "\n";
            return;
        }
    }

    // 3. Nettoyer les données invalides
    echo "\n3️⃣  NETTOYAGE DES DONNÉES\n";
    echo str_repeat("-", 40) . "\n";

    // Supprimer RDV avec matricules de test
    $deleted = \DB::table('liste_rdvs')
        ->whereIn('matricule', ['999001', '999002', '999003'])
        ->delete();

    echo "🗑️  RDV de test supprimés: $deleted\n";

    // 4. Créer des RDV avec des valeurs plus courtes si nécessaire
    echo "\n4️⃣  CRÉATION DE RDV CORRECTS\n";
    echo str_repeat("-", 40) . "\n";

    // Utiliser des valeurs plus courtes si la colonne est encore limitée
    $typeMapping = [
        'psycho' => 'consultation',
        'dentiste' => 'urgences',
        'medecin' => 'consultation'
    ];

    $services = [
        'psychiatrie',
        'chirurgie_dentaire',
        'cardiologie'
    ];

    // Récupérer de vrais étudiants
    $etudiants = \DB::table('students')->limit(3)->get();

    if ($etudiants->count() >= 3) {
        $today = \Carbon\Carbon::today();

        foreach ($etudiants->take(3) as $index => $etudiant) {
            $typeKeys = array_keys($typeMapping);
            $typeKey = $typeKeys[$index];
            $motif = $typeMapping[$typeKey];
            $service = $services[$index];

            // Vérifier si le RDV n'existe pas déjà
            $exists = \DB::table('liste_rdvs')
                ->where('matricule', $etudiant->matricule)
                ->whereDate('date', $today)
                ->exists();

            if (!$exists) {
                $dateTime = $today->copy()->addHours(9 + $index);

                \DB::table('liste_rdvs')->insert([
                    'type_medecin' => $typeKey,
                    'matricule' => $etudiant->matricule,
                    'motif' => $motif,
                    'service' => $service,
                    'date' => $dateTime->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                echo "✅ RDV créé: {$etudiant->nom} {$etudiant->prenom}\n";
                echo "   Type: $typeKey | Service: $service | Motif: $motif\n";
            } else {
                echo "⚠️  RDV déjà existant pour {$etudiant->nom}\n";
            }
        }
    }

    // 5. Vérification finale
    echo "\n5️⃣  VÉRIFICATION FINALE\n";
    echo str_repeat("-", 40) . "\n";

    $rdvFinaux = \App\Models\ListeRdv::with('student')
        ->whereDate('date', \Carbon\Carbon::today())
        ->get();

    echo "📊 RDV pour aujourd'hui: " . $rdvFinaux->count() . "\n\n";

    if ($rdvFinaux->count() > 0) {
        echo "📋 RENDEZ-VOUS DISPONIBLES:\n";
        foreach ($rdvFinaux as $rdv) {
            $studentInfo = $rdv->student ?
                "{$rdv->student->nom} {$rdv->student->prenom}" :
                "ÉTUDIANT INTROUVABLE";

            echo "   • {$rdv->matricule} - $studentInfo\n";
            echo "     Type: {$rdv->type_medecin} | Service: {$rdv->service}\n";
            echo "     Motif: {$rdv->motif} | Date: {$rdv->date}\n\n";
        }

        echo "🎉 SYSTÈME PRÊT!\n";
        echo "   Les rendez-vous devraient maintenant s'afficher dans la liste\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🏁 Correction terminée!\n";
?>
