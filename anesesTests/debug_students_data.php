<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Student;

try {
    echo "Vérification des étudiants dans la base de données:\n";
    echo "================================================\n\n";

    $count = Student::count();
    echo "Nombre total d'étudiants: $count\n\n";

    if ($count > 0) {
        echo "Premiers 10 étudiants:\n";
        echo "----------------------\n";

        $students = Student::take(10)->get(['id', 'matricule', 'nom', 'prenom', 'section_id', 'grade']);

        foreach ($students as $student) {
            echo sprintf(
                "ID: %-3s | Matricule: %-10s | Nom: %-15s | Prénom: %-15s | Section: %-10s | Grade: %s\n",
                $student->id,
                $student->matricule ?? 'NULL',
                $student->nom ?? 'NULL',
                $student->prenom ?? 'NULL',
                $student->section_id ?? 'NULL',
                $student->grade ?? 'NULL'
            );
        }

        echo "\nRecherche pour matricule '2022250':\n";
        echo "===================================\n";

        $searchResult = Student::where('matricule', 'LIKE', '%2022250%')
            ->orWhere('nom', 'LIKE', '%2022250%')
            ->orWhere('prenom', 'LIKE', '%2022250%')
            ->get(['id', 'matricule', 'nom', 'prenom', 'section_id']);

        if ($searchResult->count() > 0) {
            foreach ($searchResult as $student) {
                echo sprintf(
                    "TROUVÉ: ID: %s | Matricule: %s | Nom: %s %s | Section: %s\n",
                    $student->id,
                    $student->matricule,
                    $student->nom,
                    $student->prenom,
                    $student->section_id ?? 'NULL'
                );
            }
        } else {
            echo "Aucun étudiant trouvé avec '2022250'\n";
        }

        echo "\nFormat JSON pour les 5 premiers (comme envoyé au front-end):\n";
        echo "============================================================\n";

        $jsonData = Student::take(5)->get(['id', 'matricule', 'nom', 'prenom', 'section_id', 'grade']);
        echo json_encode($jsonData->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } else {
        echo "Aucun étudiant trouvé dans la base de données!\n";
        echo "Vous devez d'abord créer des étudiants de test.\n";
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
