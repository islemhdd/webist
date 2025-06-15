<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Script pour créer des étudiants de test
try {
    echo "Création d'étudiants de test...\n";

    // Connexion directe à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=webistislem', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si la table students existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'students'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Table 'students' non trouvée!\n";
        exit(1);
    }

    // Vérifier la structure de la table
    $stmt = $pdo->query("DESCRIBE students");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Colonnes de la table students: " . implode(', ', $columns) . "\n\n";

    // Vérifier le nombre d'étudiants existants
    $stmt = $pdo->query("SELECT COUNT(*) FROM students");
    $count = $stmt->fetchColumn();
    echo "📊 Nombre d'étudiants existants: $count\n";

    if ($count < 10) {
        echo "🏗️ Création d'étudiants de test...\n";

        // Créer des étudiants de test
        $testStudents = [
            ['2022260', 'BELKACEM', 'Ahmed', '2TI1', 2],
            ['2022251', 'BRAHIMI', 'Fatima', '2TI2', 2],
            ['2022252', 'CHERIF', 'Mohamed', '1TI1', 1],
            ['2022253', 'DAOUD', 'Amina', '1TI2', 1],
            ['2022254', 'FERHAT', 'Karim', '3TI1', 3],
            ['2022255', 'GHAZI', 'Leila', '3TI2', 3],
            ['2022256', 'HADJ', 'Omar', '2TI1', 2],
            ['2022257', 'IDRIS', 'Sara', '2TI2', 2],
            ['2022258', 'JAZIRI', 'Youssef', '1TI1', 1],
            ['2022259', 'KHELIFI', 'Nadia', '1TI2', 1],
        ];

        foreach ($testStudents as $student) {
            try {
                $stmt = $pdo->prepare("
                    INSERT IGNORE INTO students (matricule, nom, prenom, section_id, grade, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                ");
                $stmt->execute($student);
                echo "✅ Créé: {$student[0]} - {$student[1]} {$student[2]}\n";
            } catch (Exception $e) {
                echo "❌ Erreur pour {$student[0]}: " . $e->getMessage() . "\n";
            }
        }
    }

    // Afficher les étudiants actuels
    echo "\n📋 Étudiants dans la base de données:\n";
    echo "=====================================\n";
    $stmt = $pdo->query("SELECT matricule, nom, prenom, section_id, grade FROM students ORDER BY matricule LIMIT 20");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf(
            "Matricule: %-10s | %-15s %-15s | Section: %-8s | Grade: %s\n",
            $row['matricule'],
            $row['nom'],
            $row['prenom'],
            $row['section_id'] ?? 'NULL',
            $row['grade'] ?? 'NULL'
        );
    }

    echo "\n✅ Script terminé avec succès!\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
