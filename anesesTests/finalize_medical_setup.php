<?php

echo "🔧 CORRECTION DES TYPES DE MÉDECINS\n";
echo "===================================\n\n";

try {
    // Connexion directe à la base de données
    $dsn = "mysql:host=127.0.0.1;dbname=webistIslem;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base de données réussie\n\n";

    // Corriger la table appointments pour utiliser les bons types
    echo "📅 CORRECTION DES TYPES DANS LA TABLE APPOINTMENTS\n";
    echo "--------------------------------------------------\n";

    $alter_appointments = "
        ALTER TABLE appointments
        MODIFY COLUMN type_medecin ENUM('médecin générale', 'dentiste', 'psycho') NOT NULL
    ";

    $pdo->exec($alter_appointments);
    echo "✅ Types de médecins corrigés dans la table appointments\n\n";

    // Afficher le résumé final
    echo "📊 RÉSUMÉ FINAL DES COMPTES MÉDICAUX CRÉÉS\n";
    echo "==========================================\n";

    $stmt = $pdo->query("
        SELECT u.id, u.username, r.name as role_name, u.phone
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE r.name IN ('Medecin', 'Psychologue', 'Dentiste', 'Médecin général')
        ORDER BY u.id
    ");

    echo "👥 COMPTES MÉDICAUX DISPONIBLES :\n";
    echo "---------------------------------\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $specialite = "";
        switch($row['role_name']) {
            case 'Medecin': $specialite = "👨‍⚕️ MÉDECIN CHEF (toutes spécialités)"; break;
            case 'Psychologue': $specialite = "🧠 PSYCHOLOGUE (patients 'psycho')"; break;
            case 'Dentiste': $specialite = "🦷 DENTISTE (patients 'dentiste')"; break;
            case 'Médecin général': $specialite = "🩺 MÉDECIN GÉNÉRAL (patients 'médecin générale')"; break;
        }
        echo "🔸 {$row['username']} - {$specialite}\n";
        echo "   📞 {$row['phone']}\n\n";
    }

    echo "🔑 MOTS DE PASSE PAR DÉFAUT :\n";
    echo "-----------------------------\n";
    echo "🔸 Dr. Psychologue: psy123\n";
    echo "🔸 Dr. Dentiste: dent123\n";
    echo "🔸 Dr. Généraliste: gen123\n";
    echo "🔸 Dr. Médecin Chef: (mot de passe existant inchangé)\n\n";

    echo "✅ CONFIGURATION DES COMPTES MÉDICAUX TERMINÉE !\n";
    echo "================================================\n";
    echo "Prochaines étapes :\n";
    echo "1. ✅ Rôles médicaux créés\n";
    echo "2. ✅ Comptes utilisateurs créés\n";
    echo "3. ✅ Table appointments créée\n";
    echo "4. 🔄 Modification des contrôleurs pour filtrage par spécialité\n";
    echo "5. 🔄 Adaptation de la sidebar selon le type de médecin\n";
    echo "6. 🔄 Implémentation des restrictions d'accès\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur PDO: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
