<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=webistIslem', 'root', '');
    $stmt = $pdo->query('SHOW COLUMNS FROM liste_rdvs WHERE Field = "type_medecin"');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "Colonne type_medecin trouvée:\n";
        print_r($result);
    } else {
        echo "Colonne type_medecin non trouvée!\n";
    }

    // Vérifier les valeurs existantes
    $stmt = $pdo->query('SELECT DISTINCT type_medecin FROM liste_rdvs');
    $values = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "\nValeurs existantes dans type_medecin:\n";
    print_r($values);

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>
