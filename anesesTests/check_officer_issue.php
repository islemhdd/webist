<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== DIAGNOSTIC DU PROBLÈME OFFICER ===\n";

// 1. Vérifier s'il y a des utilisateurs avec des rôles d'officier
$officerUsers = User::whereHas('role', function($q) {
    $q->whereIn('name', ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général']);
})->with('role')->get();

echo "Utilisateurs avec des rôles d'officier trouvés: " . $officerUsers->count() . "\n\n";

foreach ($officerUsers as $user) {
    echo "User ID: " . $user->id . "\n";
    echo "Username: " . $user->username . "\n";
    echo "Role: " . $user->role->name . "\n";
    
    // Test isOfficer method
    $officer = $user->isOfficer();
    if ($officer) {
        echo "isOfficer() retourne un objet Officer\n";
        echo "Officer ID: " . ($officer->id ?? 'NULL') . "\n";
        echo "Officer attributes: " . json_encode($officer->getAttributes()) . "\n";
    } else {
        echo "isOfficer() retourne NULL\n";
    }
    echo "-----------------------------------\n";
}

// 2. Vérifier la structure de la table officers
echo "\n=== VÉRIFICATION TABLE OFFICERS ===\n";
try {
    $officerExists = \Illuminate\Support\Facades\Schema::hasTable('officers');
    echo "Table 'officers' existe: " . ($officerExists ? 'OUI' : 'NON') . "\n";
} catch (Exception $e) {
    echo "Erreur lors de la vérification de la table officers: " . $e->getMessage() . "\n";
}

// 3. Vérifier la structure de la table users
echo "\n=== STRUCTURE TABLE USERS ===\n";
try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    echo "Colonnes de la table users: " . implode(', ', $columns) . "\n";
} catch (Exception $e) {
    echo "Erreur lors de la récupération des colonnes: " . $e->getMessage() . "\n";
}

// 4. Proposer une solution
echo "\n=== ANALYSE DU PROBLÈME ===\n";
echo "Le problème vient du fait que:\n";
echo "1. La table 'officers' n'existe pas\n";
echo "2. La méthode isOfficer() crée un objet Officer à partir des attributs User\n";
echo "3. Mais l'ID de cet Officer est null car il n'existe pas dans une table officers\n";
echo "4. La route brigade.statistics nécessite un ID valide\n";

echo "\n=== SOLUTIONS POSSIBLES ===\n";
echo "1. Modifier AuthController pour utiliser l'ID utilisateur directement\n";
echo "2. Créer une table officers et migrer les données\n";
echo "3. Modifier la route pour accepter l'ID utilisateur\n";
