<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;

echo "==== Mise à jour des patients pour valider_rhp ====\n\n";

// Sélectionner quelques patients validés par l'infirmerie (valider = 1 ou 2)
$patients = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 0)->limit(5)->get();

echo "Patients trouvés à mettre à jour: " . $patients->count() . "\n";

// Mettre à jour ces patients pour avoir valider_rhp = 1
foreach ($patients as $patient) {
    echo "Mise à jour du patient ID: " . $patient->id . ", Matricule: " . $patient->matricule . "\n";
    $patient->valider_rhp = 1;
    $patient->save();
}

// Vérification après mise à jour
$validated = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 1)->count();
$pending = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 0)->count();

echo "\n==== Résultats après mise à jour ====\n";
echo "Patients avec valider_rhp = 1: $validated\n";
echo "Patients avec valider_rhp = 0 (en attente) et validés (valider = 1 ou 2): $pending\n";

echo "\n==== Exemple de patients validés par RHP ====\n";
$examples = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 1)->limit(3)->get(['id', 'matricule', 'valider', 'valider_rhp']);
foreach ($examples as $p) {
    echo "ID: {$p->id}, Matricule: {$p->matricule}, Valider: {$p->valider}, Valider RHP: {$p->valider_rhp}\n";
}
