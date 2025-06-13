<?php

echo "🔍 VÉRIFICATION DE LA STRUCTURE DES TABLES EXISTANTES\n";
echo "=====================================================\n\n";

try {
    // Connexion directe à la base de données
    $dsn = "mysql:host=127.0.0.1;dbname=webistIslem;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base de données réussie\n\n";

    // Vérifier la structure de la table patients
    echo "📊 STRUCTURE DE LA TABLE PATIENTS\n";
    echo "---------------------------------\n";

    $stmt = $pdo->query("DESCRIBE patients");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "📌 {$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']} - {$row['Default']}\n";
    }

    echo "\n";

    // Vérifier la structure de la table liste_rdvs (rendez-vous existants)
    echo "📅 STRUCTURE DE LA TABLE LISTE_RDVS\n";
    echo "-----------------------------------\n";

    try {
        $stmt = $pdo->query("DESCRIBE liste_rdvs");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "📌 {$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']} - {$row['Default']}\n";
        }
        echo "\n";

        // Vérifier s'il y a une colonne type_medecin dans liste_rdvs
        $stmt = $pdo->query("SHOW COLUMNS FROM liste_rdvs LIKE 'type_medecin'");
        $type_medecin_exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$type_medecin_exists) {
            echo "⚠️  Colonne 'type_medecin' manquante dans liste_rdvs\n";
            echo "🔧 AJOUT DE LA COLONNE TYPE_MEDECIN\n";
            echo "-----------------------------------\n";

            $alter_query = "ALTER TABLE liste_rdvs ADD COLUMN type_medecin ENUM('médecin générale', 'dentiste', 'psycho') DEFAULT 'médecin générale' AFTER motif";
            $pdo->exec($alter_query);
            echo "✅ Colonne type_medecin ajoutée avec succès\n\n";
        } else {
            echo "✅ Colonne type_medecin existe déjà dans liste_rdvs\n\n";
        }

    } catch (PDOException $e) {
        echo "❌ Erreur lors de la vérification de liste_rdvs: " . $e->getMessage() . "\n\n";
    }

    // Vérifier la structure de la table users
    echo "📊 STRUCTURE DE LA TABLE USERS\n";
    echo "------------------------------\n";

    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "📌 {$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']} - {$row['Default']}\n";
    }

    echo "\n";

    // Vérifier les nouveaux rôles médicaux
    echo "👥 VÉRIFICATION DES RÔLES MÉDICAUX\n";
    echo "---------------------------------\n";

    $stmt = $pdo->query("SELECT id, name FROM roles WHERE name IN ('Medecin', 'Psychologue', 'Dentiste', 'Medecin general')");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "👨‍⚕️ Rôle {$row['id']}: {$row['name']}\n";
    }

    echo "\n";

    // Vérifier les comptes médicaux spécialisés
    echo "👥 VÉRIFICATION DES COMPTES MÉDICAUX\n";
    echo "-----------------------------------\n";

    $stmt = $pdo->query("
        SELECT u.id, u.nom, u.email, r.name as role_name
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE r.name IN ('Medecin', 'Psychologue', 'Dentiste', 'Medecin general')
        ORDER BY r.id
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "👨‍⚕️ ID {$row['id']}: {$row['nom']} ({$row['email']}) - {$row['role_name']}\n";
    }

    echo "\n";

    echo "🎉 VÉRIFICATION TERMINÉE - SYSTÈME PRÊT POUR LES SPÉCIALITÉS MÉDICALES !\n";

} catch (PDOException $e) {
    echo "❌ Erreur PDO: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
