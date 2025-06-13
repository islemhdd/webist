<?php
// Définir le chemin racine de Laravel
define('LARAVEL_START', microtime(true));

// Charger l'autoloader de Composer
require __DIR__.'/vendor/autoload.php';

// Bootstrapper l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Créer un kernel pour la console
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC DE L'ERREUR ROUTE BRIGADE.STATISTICS ===\n\n";

// 1. Vérifier la table officers
echo "1. Vérification de la table officers:\n";
try {
    $officers = \DB::select('DESCRIBE officers');
    echo "   ✓ Table officers trouvée:\n";
    foreach ($officers as $column) {
        echo "     - {$column->Field} ({$column->Type})\n";
    }
    
    $officerCount = \DB::table('officers')->count();
    echo "   - Nombre d'officers: $officerCount\n";
    
} catch (Exception $e) {
    echo "   ✗ Erreur table officers: " . $e->getMessage() . "\n";
}

// 2. Vérifier les utilisateurs avec rôles d'officier
echo "\n2. Utilisateurs avec rôles d'officier:\n";
$officerRoles = ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général'];

foreach ($officerRoles as $role) {
    $users = \DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('roles.name', $role)
        ->select('users.id', 'users.username', 'roles.name as role_name')
        ->get();
    
    echo "   - $role: " . $users->count() . " utilisateur(s)\n";
    foreach ($users as $user) {
        echo "     * ID: {$user->id}, Username: {$user->username}\n";
    }
}

// 3. Tester la méthode isOfficer pour un utilisateur
echo "\n3. Test de la méthode isOfficer():\n";
$userWithOfficerRole = \DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->whereIn('roles.name', $officerRoles)
    ->select('users.*')
    ->first();

if ($userWithOfficerRole) {
    echo "   - Test avec utilisateur ID: {$userWithOfficerRole->id}\n";
    
    $user = \App\Models\User::find($userWithOfficerRole->id);
    $officer = $user->isOfficer();
    
    if ($officer) {
        echo "   ✓ isOfficer() retourne un objet\n";
        echo "   - Officer ID: " . ($officer->id ?? 'NULL') . "\n";
        echo "   - Officer attributes: " . json_encode($officer->getAttributes()) . "\n";
    } else {
        echo "   ✗ isOfficer() retourne null\n";
    }
} else {
    echo "   ✗ Aucun utilisateur avec rôle d'officier trouvé\n";
}

// 4. Vérifier la structure de la relation User-Officer
echo "\n4. Vérification de la relation User-Officer:\n";
try {
    // Voir si les utilisateurs ont des entrées correspondantes dans officers
    $usersWithOfficers = \DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('officers', 'users.id', '=', 'officers.user_id')
        ->whereIn('roles.name', $officerRoles)
        ->select('users.id as user_id', 'users.username', 'officers.id as officer_id', 'roles.name as role_name')
        ->get();
    
    echo "   Correspondances User-Officer:\n";
    foreach ($usersWithOfficers as $row) {
        echo "   - User {$row->user_id} ({$row->username}, {$row->role_name}) → Officer {$row->officer_id}\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Erreur lors de la vérification: " . $e->getMessage() . "\n";
}

// 5. Proposer une solution
echo "\n5. Solution recommandée:\n";
echo "   Le problème semble être que la méthode isOfficer() dans User.php\n";
echo "   crée un nouvel objet Officer sans ID valide.\n\n";

echo "   Options de correction:\n";
echo "   A. Utiliser l'ID de l'utilisateur directement dans la route\n";
echo "   B. Créer des entrées dans la table officers pour chaque utilisateur officier\n";
echo "   C. Modifier la méthode isOfficer() pour retourner l'ID utilisateur\n";

echo "\n=== FIN DU DIAGNOSTIC ===\n";
