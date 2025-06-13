<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "=== CRÉATION DES UTILISATEURS MÉDICAUX ===\n\n";

try {
    // 1. Créer les rôles médicaux s'ils n'existent pas
    echo "1. Création des rôles médicaux:\n";
    
    $medicalRoles = [
        'Medecin chef',
        'Psychologue', 
        'Dentiste',
        'Medecin generale'
    ];
    
    foreach ($medicalRoles as $roleName) {
        $role = Role::firstOrCreate(['name' => $roleName]);
        echo "✅ Rôle '{$roleName}' créé/vérifié (ID: {$role->id})\n";
    }
    echo "\n";
    
    // 2. Créer les utilisateurs médicaux
    echo "2. Création des utilisateurs médicaux:\n";
    
    $medicalUsers = [
        [
            'username' => 'Medecin chef',
            'password' => '123456789',
            'phone' => 12345001,
            'role_name' => 'Medecin chef',
            'bat' => '0'
        ],
        [
            'username' => 'Psychologue',
            'password' => '123456789', 
            'phone' => 12345002,
            'role_name' => 'Psychologue',
            'bat' => '0'
        ],
        [
            'username' => 'Dentiste',
            'password' => '123456789',
            'phone' => 12345003, 
            'role_name' => 'Dentiste',
            'bat' => '0'
        ],
        [
            'username' => 'Medecin generale',
            'password' => '123456789',
            'phone' => 12345004,
            'role_name' => 'Medecin generale', 
            'bat' => '0'
        ]
    ];
    
    foreach ($medicalUsers as $userData) {
        $role = Role::where('name', $userData['role_name'])->first();
        
        if ($role) {
            $user = User::updateOrCreate(
                ['username' => $userData['username']],
                [
                    'username' => $userData['username'],
                    'password' => Hash::make($userData['password']), // Hash manually since model mutator might not work
                    'phone' => $userData['phone'],
                    'role_id' => $role->id,
                    'bat' => $userData['bat']
                ]
            );
            
            echo "✅ Utilisateur '{$userData['username']}' créé/mis à jour (ID: {$user->id})\n";
            echo "   - Rôle: {$role->name} (ID: {$role->id})\n";
            echo "   - Téléphone: {$user->phone}\n";
        } else {
            echo "❌ Rôle '{$userData['role_name']}' non trouvé\n";
        }
    }
    echo "\n";
    
    // 3. Vérifier que les utilisateurs peuvent s'authentifier
    echo "3. Test d'authentification:\n";
    
    foreach ($medicalUsers as $userData) {
        $user = User::where('username', $userData['username'])->first();
        if ($user) {
            $passwordCheck = Hash::check($userData['password'], $user->password);
            echo "✅ {$userData['username']}: " . ($passwordCheck ? "Mot de passe OK" : "❌ Mot de passe incorrect") . "\n";
        }
    }
    echo "\n";
    
    // 4. Afficher un résumé
    echo "4. Résumé des utilisateurs médicaux:\n";
    
    $medicalRoleIds = Role::whereIn('name', $medicalRoles)->pluck('id');
    $medicalUsersCount = User::whereIn('role_id', $medicalRoleIds)->count();
    
    echo "Total utilisateurs médicaux: {$medicalUsersCount}\n";
    
    $users = User::with('role')->whereIn('role_id', $medicalRoleIds)->get();
    foreach ($users as $user) {
        echo "- {$user->username} ({$user->role->name})\n";
    }
    
    echo "\n✅ UTILISATEURS MÉDICAUX CRÉÉS AVEC SUCCÈS!\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "CRÉATION TERMINÉE\n";
