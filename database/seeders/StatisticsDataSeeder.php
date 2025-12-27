<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Convoncu;
use App\Models\Exemption;
use Carbon\Carbon;

class StatisticsDataSeeder extends Seeder
{
    public function run()
    {
        $today = Carbon::today();

        // Créer des convocations - différents grades
        Convoncu::create([
            'matricule' => '2022056', // Grade 1
            'psy' => 'Dr. Psychologue',
            'medGen' => null,
            'chirDent' => null,
            'avisSpe' => null
        ]);

        Convoncu::create([
            'matricule' => '2022064', // Grade 1
            'psy' => null,
            'medGen' => 'Dr. Généraliste',
            'chirDent' => null,
            'avisSpe' => null
        ]);

        Convoncu::create([
            'matricule' => '2022131', // Grade 2
            'psy' => 'Dr. Psy Autre',
            'medGen' => null,
            'chirDent' => null,
            'avisSpe' => null
        ]);

        Convoncu::create([
            'matricule' => '2022003', // Grade 3
            'psy' => null,
            'medGen' => 'Dr. Médecin',
            'chirDent' => null,
            'avisSpe' => null
        ]);

        // Créer des exemptions pour aujourd'hui - différents grades
        Exemption::create([
            'matricule' => '2022055', // Grade 1
            'nom' => 'Etudiant',
            'prenom' => 'Test',
            'motif' => 'blessure',
            'date_debut' => $today,
            'date_fin' => $today->copy()->addDays(7)
        ]);

        Exemption::create([
            'matricule' => '2022096', // Grade 2
            'nom' => 'Sport',
            'prenom' => 'Etudiant',
            'motif' => 'maladie',
            'date_debut' => $today,
            'date_fin' => $today->copy()->addDays(3)
        ]);

        Exemption::create([
            'matricule' => '2022002', // Grade 3
            'nom' => 'Student',
            'prenom' => 'Etudiant',
            'motif' => 'chirurgie',
            'date_debut' => $today,
            'date_fin' => $today->copy()->addDays(14)
        ]);

        Exemption::create([
            'matricule' => '2022120', // Grade 2
            'nom' => 'Autre',
            'prenom' => 'Etudiant',
            'motif' => 'maladie',
            'date_debut' => $today,
            'date_fin' => $today->copy()->addDays(5)
        ]);

        Exemption::create([
            'matricule' => '2022064', // Grade 1
            'nom' => 'Blessure',
            'prenom' => 'Etudiant',
            'motif' => 'blessure',
            'date_debut' => $today,
            'date_fin' => $today->copy()->addDays(10)
        ]);
    }
}

