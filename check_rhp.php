<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

echo "==== Vérification des patients avec valider_rhp ====\n\n";

// Compter les patients avec valider_rhp = 1
$validated = Patient::where('valider_rhp', 1)->count();
echo "Patients avec valider_rhp = 1: $validated\n";

// Compter les patients avec valider_rhp = 0 et validés par l'infirmerie
$validatable = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 0)->count();
echo "Patients avec valider_rhp = 0 (en attente) et validés (valider = 1 ou 2): $validatable\n";

// Vérifier l'existence de la colonne valider_rhp
$hasColumn = Schema::hasColumn('patients', 'valider_rhp');
echo "La table patients a-t-elle une colonne valider_rhp? " . ($hasColumn ? "OUI" : "NON") . "\n";

// Si aucun patient n'a valider_rhp = 1, mettre à jour quelques patients pour test
if ($validated == 0) {
    $patients = Patient::whereIn('valider', [1, 2])->limit(3)->get();

    if ($patients->count() > 0) {
        echo "\nMise à jour de " . $patients->count() . " patients pour tester:\n";

        foreach ($patients as $patient) {
            $patient->valider_rhp = 1;
            $patient->save();
            echo "Patient ID: " . $patient->id . ", Matricule: " . $patient->matricule . " mis à jour avec valider_rhp = 1\n";
        }

        $newValidated = Patient::where('valider_rhp', 1)->count();
        echo "\nNouveaux patients avec valider_rhp = 1: $newValidated\n";
    } else {
        echo "\nAucun patient valide trouvé pour la mise à jour de test.\n";
    }
}

echo "\n==== Échantillon de patients ====\n";
$samplePatients = Patient::limit(5)->get(['id', 'matricule', 'valider', 'valider_rhp']);
foreach ($samplePatients as $p) {
    echo "ID: {$p->id}, Matricule: {$p->matricule}, Valider: {$p->valider}, Valider RHP: {$p->valider_rhp}\n";
}
