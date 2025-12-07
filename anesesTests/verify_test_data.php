<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$today = now()->toDateString();
echo "Patients d'aujourd'hui ($today):\n";
echo "================================\n";

$patients = DB::table('patients')
    ->join('students', 'patients.matricule', '=', 'students.matricule')
    ->select('patients.matricule', 'students.grade', 'students.nom', 'students.prenom', 'patients.valider', 'patients.valider_rhp')
    ->whereDate('patients.created_at', '=', $today)
    ->orderBy('students.grade')
    ->get();

$gradeStats = [1 => [], 2 => [], 3 => []];

foreach ($patients as $p) {
    echo "- {$p->matricule} ({$p->nom} {$p->prenom}) Grade:{$p->grade} Valider:{$p->valider} RHP:{$p->valider_rhp}\n";

    if (isset($gradeStats[$p->grade])) {
        $gradeStats[$p->grade][] = $p;
    }
}

echo "\nStatistiques par grade:\n";
foreach ($gradeStats as $grade => $patients) {
    $total = count($patients);
    $pending = count(array_filter($patients, fn($p) => in_array($p->valider, [1, 2]) && $p->valider_rhp == 0));
    $validated = count(array_filter($patients, fn($p) => in_array($p->valider, [1, 2]) && $p->valider_rhp == 1));

    echo "Grade $grade: $total patients (En attente RHP: $pending, Validé RHP: $validated)\n";
}
