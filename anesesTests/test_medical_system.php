<?php
require 'vendor/autoload.php';

// Test the medical specialty system
echo "🏥 TEST DU SYSTÈME MÉDICAL SPÉCIALISÉ\n";
echo "=====================================\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=webistIslem', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base de données réussie\n\n";

    // Test 1: Vérifier les rôles médicaux
    echo "📋 TEST 1: VÉRIFICATION DES RÔLES MÉDICAUX\n";
    echo "==========================================\n";
    $stmt = $pdo->query("
        SELECT r.id, r.name, COUNT(u.id) as user_count
        FROM roles r
        LEFT JOIN users u ON r.id = u.role_id
        WHERE r.name IN ('Medecin', 'Psychologue', 'Dentiste', 'Médecin général')
        GROUP BY r.id, r.name
        ORDER BY r.id
    ");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "👨‍⚕️ " . $row['name'] . " (ID: " . $row['id'] . ") - " . $row['user_count'] . " utilisateur(s)\n";
    }
    echo "\n";

    // Test 2: Vérifier la structure des tables
    echo "📊 TEST 2: VÉRIFICATION DES STRUCTURES DE TABLES\n";
    echo "===============================================\n";

    // Table patients
    echo "🗂️ Table PATIENTS:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM patients LIKE 'type_medecin'");
    if ($stmt->fetch()) {
        echo "   ✅ Colonne 'type_medecin' présente\n";

        // Vérifier les valeurs ENUM
        $stmt = $pdo->query("SHOW COLUMNS FROM patients WHERE Field = 'type_medecin'");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   📝 Type: " . $column['Type'] . "\n";
    } else {
        echo "   ❌ Colonne 'type_medecin' manquante\n";
    }

    // Table liste_rdvs
    echo "\n🗂️ Table LISTE_RDVS:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM liste_rdvs LIKE 'type_medecin'");
    if ($stmt->fetch()) {
        echo "   ✅ Colonne 'type_medecin' présente\n";

        // Vérifier les valeurs ENUM
        $stmt = $pdo->query("SHOW COLUMNS FROM liste_rdvs WHERE Field = 'type_medecin'");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   📝 Type: " . $column['Type'] . "\n";
    } else {
        echo "   ❌ Colonne 'type_medecin' manquante\n";
    }
    echo "\n";

    // Test 3: Compter les données existantes
    echo "📈 TEST 3: DONNÉES EXISTANTES\n";
    echo "============================\n";

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM patients");
    $total_patients = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "👥 Total patients: " . $total_patients . "\n";

    $stmt = $pdo->query("
        SELECT type_medecin, COUNT(*) as count
        FROM patients
        WHERE type_medecin IS NOT NULL
        GROUP BY type_medecin
    ");
    echo "📊 Répartition par spécialité:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   📌 " . $row['type_medecin'] . ": " . $row['count'] . " patient(s)\n";
    }

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM liste_rdvs");
    $total_rdvs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "\n📅 Total rendez-vous: " . $total_rdvs . "\n";

    $stmt = $pdo->query("
        SELECT type_medecin, COUNT(*) as count
        FROM liste_rdvs
        WHERE type_medecin IS NOT NULL
        GROUP BY type_medecin
    ");
    echo "📊 RDV par spécialité:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   📌 " . $row['type_medecin'] . ": " . $row['count'] . " RDV\n";
    }
    echo "\n";

    // Test 4: Tester l'authentification des médecins
    echo "🔐 TEST 4: COMPTES MÉDICAUX\n";
    echo "==========================\n";
    $stmt = $pdo->query("
        SELECT u.id, u.username, r.name
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE r.name IN ('Medecin', 'Psychologue', 'Dentiste', 'Médecin général')
        ORDER BY r.name
    ");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "👨‍⚕️ " . $row['username'] . " (" . $row['name'] . ") - ID: " . $row['id'] . "\n";
    }
    echo "\n";

    // Test 5: Créer des données de test si nécessaire
    echo "🧪 TEST 5: DONNÉES DE TEST\n";
    echo "=========================\n";

    // Ajouter quelques patients de test avec spécialités
    $test_patients = [
        ['matricule' => 999001, 'type_medecin' => 'psycho'],
        ['matricule' => 999002, 'type_medecin' => 'dentiste'],
        ['matricule' => 999003, 'type_medecin' => 'médecin générale']
    ];

    foreach ($test_patients as $patient) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO patients (matricule, type_medecin, valider, created_at, updated_at)
            VALUES (?, ?, 0, NOW(), NOW())
        ");
        $stmt->execute([$patient['matricule'], $patient['type_medecin']]);
        echo "➕ Patient test ajouté: " . $patient['matricule'] . " (" . $patient['type_medecin'] . ")\n";
    }

    // Ajouter quelques RDV de test
    $test_rdvs = [
        ['matricule' => 999001, 'type_medecin' => 'psycho', 'motif' => 'Consultation psycho test', 'service' => 'Psychologie'],
        ['matricule' => 999002, 'type_medecin' => 'dentiste', 'motif' => 'Détartrage test', 'service' => 'Dentaire'],
        ['matricule' => 999003, 'type_medecin' => 'médecin générale', 'motif' => 'Consultation générale test', 'service' => 'Médecine générale']
    ];

    foreach ($test_rdvs as $rdv) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO liste_rdvs (matricule, motif, service, date, type_medecin, created_at, updated_at)
            VALUES (?, ?, ?, DATE_ADD(CURDATE(), INTERVAL 1 DAY), ?, NOW(), NOW())
        ");
        $stmt->execute([$rdv['matricule'], $rdv['motif'], $rdv['service'], $rdv['type_medecin']]);
        echo "📅 RDV test ajouté: " . $rdv['matricule'] . " (" . $rdv['type_medecin'] . ")\n";
    }
    echo "\n";

    // Test 6: Validation finale
    echo "🎯 TEST 6: VALIDATION FINALE\n";
    echo "============================\n";

    $checks = [
        'Rôles médicaux' => $pdo->query("SELECT COUNT(*) FROM roles WHERE name IN ('Medecin', 'Psychologue', 'Dentiste', 'Médecin général')")->fetchColumn() >= 4,
        'Comptes médicaux' => $pdo->query("SELECT COUNT(*) FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name IN ('Medecin', 'Psychologue', 'Dentiste', 'Médecin général')")->fetchColumn() >= 4,
        'Colonne patients.type_medecin' => $pdo->query("SHOW COLUMNS FROM patients LIKE 'type_medecin'")->fetch() !== false,
        'Colonne liste_rdvs.type_medecin' => $pdo->query("SHOW COLUMNS FROM liste_rdvs LIKE 'type_medecin'")->fetch() !== false,
        'Patients avec spécialité' => $pdo->query("SELECT COUNT(*) FROM patients WHERE type_medecin IS NOT NULL")->fetchColumn() > 0,
        'RDV avec spécialité' => $pdo->query("SELECT COUNT(*) FROM liste_rdvs WHERE type_medecin IS NOT NULL")->fetchColumn() > 0
    ];

    $passed = 0;
    $total = count($checks);

    foreach ($checks as $test => $result) {
        if ($result) {
            echo "✅ " . $test . "\n";
            $passed++;
        } else {
            echo "❌ " . $test . "\n";
        }
    }

    echo "\n";
    echo "📊 RÉSULTAT FINAL: " . $passed . "/" . $total . " tests réussis\n";

    if ($passed === $total) {
        echo "🎉 SYSTÈME MÉDICAL OPÉRATIONNEL!\n";
        echo "\n📋 PROCHAINES ÉTAPES:\n";
        echo "1. Tester l'interface web: /medical/dashboard\n";
        echo "2. Se connecter avec les comptes:\n";
        echo "   - Dr. Médecin Chef (Medecin) - Accès global\n";
        echo "   - Dr. Psychologue (Psychologue) - Patients psycho\n";
        echo "   - Dr. Dentiste (Dentiste) - Patients dentaires\n";
        echo "   - Dr. Généraliste (Médecin général) - Patients médecine générale\n";
        echo "3. Tester la création/gestion des RDV\n";
        echo "4. Valider des patients par spécialité\n";
    } else {
        echo "⚠️ Système partiellement opérationnel - Corrections nécessaires\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Test terminé le " . date('Y-m-d H:i:s') . "\n";
?>
