<?php

echo "🔍 VÉRIFICATION DE LA STRUCTURE DE LA TABLE ROLES\n";
echo "=================================================\n\n";

try {
    // Connexion directe à la base de données
    $dsn = "mysql:host=127.0.0.1;dbname=webistIslem;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base de données réussie\n\n";

    // Vérifier la structure de la table roles
    echo "📊 STRUCTURE DE LA TABLE ROLES\n";
    echo "------------------------------\n";

    $stmt = $pdo->query("DESCRIBE roles");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "📌 {$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']} - {$row['Default']}\n";
    }

    echo "\n";

    // Afficher les rôles existants
    echo "📋 RÔLES EXISTANTS\n";
    echo "------------------\n";

    $stmt = $pdo->query("SELECT * FROM roles ORDER BY id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "🔸 ID: {$row['id']} - Nom: {$row['name']}\n";
    }

    echo "\n";

} catch (PDOException $e) {
    echo "❌ Erreur PDO: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
