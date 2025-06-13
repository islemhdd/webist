<?php

echo "🏥 CRÉATION DES RÔLES ET COMPTES MÉDICAUX SPÉCIALISÉS\n";
echo "====================================================\n\n";

try {
    // Connexion directe à la base de données
    $dsn = "mysql:host=127.0.0.1;dbname=webistIslem;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base de données réussie\n\n";

    // 1. Créer les nouveaux rôles médicaux
    echo "📋 CRÉATION DES NOUVEAUX RÔLES MÉDICAUX\n";
    echo "---------------------------------------\n";    $roles_to_create = [
        ['name' => 'Psychologue'],
        ['name' => 'Dentiste'],
        ['name' => 'Médecin général']
    ];

    foreach ($roles_to_create as $role) {
        // Vérifier si le rôle existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE name = ?");
        $stmt->execute([$role['name']]);

        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO roles (name, created_at, updated_at) VALUES (?, NOW(), NOW())");
            $stmt->execute([$role['name']]);
            echo "✅ Rôle '{$role['name']}' créé avec succès (ID: " . $pdo->lastInsertId() . ")\n";
        } else {
            echo "⚠️  Rôle '{$role['name']}' existe déjà\n";
        }
    }

    echo "\n";

    // 2. Récupérer les IDs des rôles créés
    echo "🔍 RÉCUPÉRATION DES IDs DES RÔLES\n";
    echo "--------------------------------\n";

    $role_ids = [];
    $stmt = $pdo->query("SELECT id, name FROM roles WHERE name IN ('Psychologue', 'Dentiste', 'Médecin général')");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $role_ids[$row['name']] = $row['id'];
        echo "📌 {$row['name']}: ID {$row['id']}\n";
    }
    echo "\n";

    // 3. Créer les comptes utilisateurs spécialisés
    echo "👥 CRÉATION DES COMPTES UTILISATEURS SPÉCIALISÉS\n";
    echo "-----------------------------------------------\n";

    $users_to_create = [
        [
            'username' => 'Dr. Psychologue',
            'password' => password_hash('psy123', PASSWORD_DEFAULT),
            'role_id' => $role_ids['Psychologue'],
            'phone' => '770001001',
            'bat' => 0
        ],
        [
            'username' => 'Dr. Dentiste',
            'password' => password_hash('dent123', PASSWORD_DEFAULT),
            'role_id' => $role_ids['Dentiste'],
            'phone' => '770001002',
            'bat' => 0
        ],
        [
            'username' => 'Dr. Généraliste',
            'password' => password_hash('gen123', PASSWORD_DEFAULT),
            'role_id' => $role_ids['Médecin général'],
            'phone' => '770001003',
            'bat' => 0
        ]
    ];

    foreach ($users_to_create as $user) {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$user['username']]);

        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("
                INSERT INTO users (username, password, role_id, phone, bat, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([
                $user['username'],
                $user['password'],
                $user['role_id'],
                $user['phone'],
                $user['bat']
            ]);
            echo "✅ Utilisateur '{$user['username']}' créé avec succès (ID: " . $pdo->lastInsertId() . ")\n";
        } else {
            echo "⚠️  Utilisateur '{$user['username']}' existe déjà\n";
        }
    }

    echo "\n";

    // 4. Mise à jour du médecin existant pour devenir médecin chef
    echo "👨‍⚕️ MISE À JOUR DU MÉDECIN EXISTANT EN MÉDECIN CHEF\n";
    echo "------------------------------------------------\n";

    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE role_id = 5 AND username = 'Medecin'");
    $stmt->execute(['Dr. Médecin Chef']);

    if ($stmt->rowCount() > 0) {
        echo "✅ Médecin existant mis à jour en 'Dr. Médecin Chef'\n";
    } else {
        echo "⚠️  Aucun médecin trouvé à mettre à jour\n";
    }

    echo "\n";

    // 5. Afficher le résumé des comptes créés
    echo "📊 RÉSUMÉ DES COMPTES MÉDICAUX\n";
    echo "=============================\n";

    $stmt = $pdo->query("
        SELECT u.id, u.username, r.name as role_name, u.phone, u.created_at
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE r.name IN ('Medecin', 'Psychologue', 'Dentiste', 'Médecin général')
        ORDER BY u.id
    ");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "👤 {$row['username']} ({$row['role_name']})\n";
        echo "   📞 Téléphone: {$row['phone']}\n";
        echo "   📅 Créé le: {$row['created_at']}\n\n";
    }

    // 6. Créer la table appointments si elle n'existe pas
    echo "📅 CRÉATION DE LA TABLE APPOINTMENTS\n";
    echo "-----------------------------------\n";

    $create_appointments_table = "
        CREATE TABLE IF NOT EXISTS appointments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_id INT NOT NULL,
            medecin_id INT NOT NULL,
            type_medecin ENUM('general', 'psy', 'dentaire') NOT NULL,
            date_appointment DATETIME NOT NULL,
            motif TEXT,
            status ENUM('programmé', 'terminé', 'annulé') DEFAULT 'programmé',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
            FOREIGN KEY (medecin_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";

    $pdo->exec($create_appointments_table);
    echo "✅ Table appointments créée avec succès\n\n";

    echo "🎉 CRÉATION TERMINÉE AVEC SUCCÈS !\n";
    echo "================================\n";
    echo "Les nouveaux comptes médicaux spécialisés sont prêts à être utilisés.\n";
    echo "Mots de passe par défaut :\n";
    echo "- Dr. Psychologue: psy123\n";
    echo "- Dr. Dentiste: dent123\n";
    echo "- Dr. Généraliste: gen123\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur PDO: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
