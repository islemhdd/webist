<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\listeRdv;
use App\Models\Convoncu;
use App\Models\Exemption;
use Carbon\Carbon;

echo "Creating test data for all grades...\n";

$today = Carbon::today();

// Créer des patients pour aujourd'hui - Grade 1
Patient::create([
    'matricule' => '2022055',
    'valider' => true,
    'validated_at' => $today,
    'created_at' => $today
]);

Patient::create([
    'matricule' => '2022056',
    'valider' => false,
    'validated_at' => null,
    'created_at' => $today
]);

// Grade 2
Patient::create([
    'matricule' => '2022096',
    'valider' => true,
    'validated_at' => $today,
    'created_at' => $today
]);

Patient::create([
    'matricule' => '2022120',
    'valider' => false,
    'validated_at' => null,
    'created_at' => $today
]);

Patient::create([
    'matricule' => '2022131',
    'valider' => true,
    'validated_at' => $today,
    'created_at' => $today
]);

// Grade 3
Patient::create([
    'matricule' => '2022002',
    'valider' => true,
    'validated_at' => $today,
    'created_at' => $today
]);

Patient::create([
    'matricule' => '2022003',
    'valider' => false,
    'validated_at' => null,
    'created_at' => $today
]);

Patient::create([
    'matricule' => '2022004',
    'valider' => false,
    'validated_at' => null,
    'created_at' => $today
]);

echo "Patients created successfully\n";

// Créer des rendez-vous pour aujourd'hui - différents grades
listeRdv::create([
    'matricule' => '2022055', // Grade 1
    'nom' => 'Test',
    'prenom' => 'Patient',
    'section_id' => '1A',
    'motif' => 'consultation',
    'service' => 'Médecine générale',
    'date' => $today->format('Y-m-d')
]);

listeRdv::create([
    'matricule' => '2022096', // Grade 2
    'nom' => 'Urgence',
    'prenom' => 'Test',
    'section_id' => '2B',
    'motif' => 'urgences',
    'service' => 'Urgences',
    'date' => $today->format('Y-m-d')
]);

listeRdv::create([
    'matricule' => '2022002', // Grade 3
    'nom' => 'Consultation',
    'prenom' => 'Test',
    'section_id' => '1C',
    'motif' => 'consultation',
    'service' => 'Cardiologie',
    'date' => $today->format('Y-m-d')
]);

listeRdv::create([
    'matricule' => '2022120', // Grade 2
    'nom' => 'Urgence2',
    'prenom' => 'Test',
    'section_id' => '3A',
    'motif' => 'urgences',
    'service' => 'Urgences',
    'date' => $today->format('Y-m-d')
]);

echo "Rendez-vous created successfully\n";

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

echo "Convocations created successfully\n";

// Créer des exemptions pour aujourd'hui - différents grades
Exemption::create([
    'matricule' => '2022055', // Grade 1
    'motif' => 'blessure',
    'date_debut' => $today,
    'date_fin' => $today->copy()->addDays(7)
]);

Exemption::create([
    'matricule' => '2022096', // Grade 2
    'motif' => 'maladie',
    'date_debut' => $today,
    'date_fin' => $today->copy()->addDays(3)
]);

Exemption::create([
    'matricule' => '2022002', // Grade 3
    'motif' => 'chirurgie',
    'date_debut' => $today,
    'date_fin' => $today->copy()->addDays(14)
]);

Exemption::create([
    'matricule' => '2022120', // Grade 2
    'motif' => 'maladie',
    'date_debut' => $today,
    'date_fin' => $today->copy()->addDays(5)
]);

Exemption::create([
    'matricule' => '2022064', // Grade 1
    'motif' => 'blessure',
    'date_debut' => $today,
    'date_fin' => $today->copy()->addDays(10)
]);

echo "Exemptions created successfully\n";
echo "All test data created successfully for all grades!\n";
