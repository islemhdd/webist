<?php
$pdo = new PDO('mysql:host=localhost;dbname=webistIslem', 'root', '');
echo "📊 STRUCTURE DE LA TABLE ROLES\n";
echo "------------------------------\n";
$stmt = $pdo->query('DESCRIBE roles');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "📌 " . $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n📊 DONNÉES DE LA TABLE ROLES\n";
echo "-----------------------------\n";
$stmt = $pdo->query('SELECT * FROM roles');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "📌 ID: " . $row['id'] . " - Nom: " . (isset($row['name']) ? $row['name'] : $row['role_name']) . "\n";
}
?>
