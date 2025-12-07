<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Analyse du champ grade/section ===\n\n";

    // Vérifier la structure de la table students
    echo "Structure de la table students:\n";
    $studentsColumns = DB::select("DESCRIBE students");
    foreach ($studentsColumns as $column) {
        if (in_array($column->Field, ['grade', 'section_id', 'section', 'classe'])) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
    }

    echo "\n";

    // Vérifier les valeurs réelles des grades/sections
    echo "Valeurs distinctes de grade:\n";
    $grades = DB::table('students')->distinct()->pluck('grade')->filter();
    foreach ($grades as $grade) {
        echo "- Grade: '$grade'\n";
    }

    echo "\nValeurs distinctes de section_id:\n";
    $sectionIds = DB::table('students')->distinct()->pluck('section_id')->filter();
    foreach ($sectionIds as $sectionId) {
        echo "- Section ID: '$sectionId'\n";
    }

    // Vérifier les patients avec leurs informations d'étudiants
    echo "\n=== Échantillon de patients avec infos étudiants ===\n";
    $patients = DB::table('patients')
        ->join('students', 'patients.matricule', '=', 'students.matricule')
        ->select('patients.id', 'patients.matricule', 'students.grade', 'students.section_id', 'students.nom', 'students.prenom')
        ->limit(10)
        ->get();

    foreach ($patients as $patient) {
        echo "Patient {$patient->matricule}: Grade='{$patient->grade}', Section='{$patient->section_id}', Nom={$patient->nom} {$patient->prenom}\n";
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
