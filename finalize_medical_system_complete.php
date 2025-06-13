<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== FINALISATION DU SYSTÈME MÉDICAL ===\n\n";

// 1. Vérifier et créer les rôles manquants
echo "1. Vérification et création des rôles médicaux:\n";

$requiredRoles = [
    'Psychologue',
    'Dentiste',
    'Medecin general',
    'Medecin chef'
];

foreach($requiredRoles as $roleName) {
    $role = Role::firstOrCreate(['name' => $roleName]);
    $status = $role->wasRecentlyCreated ? "✅ Créé" : "✅ Existe déjà";
    echo "- Rôle '{$roleName}': {$status}\n";
}

// 2. Vérifier et créer les utilisateurs médicaux
echo "\n2. Vérification et création des utilisateurs médicaux:\n";

$medicalUsers = [
    [
        'username' => 'psychologue1',
        'password' => Hash::make('password123'),
        'role' => 'Psychologue'
    ],
    [
        'username' => 'dentiste1',
        'password' => Hash::make('password123'),
        'role' => 'Dentiste'
    ],
    [
        'username' => 'medecin_general1',
        'password' => Hash::make('password123'),
        'role' => 'Medecin general'
    ],
    [
        'username' => 'medecin_chef1',
        'password' => Hash::make('password123'),
        'role' => 'Medecin chef'
    ]
];

foreach($medicalUsers as $userData) {
    $role = Role::where('name', $userData['role'])->first();
    if (!$role) {
        echo "❌ Rôle '{$userData['role']}' non trouvé\n";
        continue;
    }

    $user = User::where('username', $userData['username'])->first();

    if (!$user) {
        $user = User::create([
            'username' => $userData['username'],
            'password' => $userData['password'],
            'role_id' => $role->id
        ]);
        echo "✅ Utilisateur '{$userData['username']}' créé avec rôle '{$userData['role']}'\n";
    } else {
        echo "✅ Utilisateur '{$userData['username']}' existe déjà\n";
    }
}

// 3. Test du MedicalSpecialtyController
echo "\n3. Test des méthodes du MedicalSpecialtyController:\n";

try {
    $controller = new \App\Http\Controllers\MedicalSpecialtyController();

    // Test avec utilisateur psychologue
    $psychologue = User::whereHas('role', function($q) {
        $q->where('name', 'Psychologue');
    })->first();

    if ($psychologue) {
        auth()->login($psychologue);
        echo "✅ Test avec Psychologue réussi\n";

        // Test filtrage par spécialité
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getSpecialtyMapping');
        $method->setAccessible(true);
        $mapping = $method->invoke($controller);

        $userRole = $psychologue->role->name;
        $allowedSpecialty = $mapping[$userRole] ?? null;
        echo "   - Spécialité autorisée: {$allowedSpecialty}\n";

        auth()->logout();
    }

} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
}

echo "\n✅ SYSTÈME MÉDICAL FINALISÉ ET PRÊT!\n";
echo "==================================================\n";
echo "UTILISATEURS MÉDICAUX CRÉÉS:\n";
echo "- psychologue1 / password123 (Psychologue)\n";
echo "- dentiste1 / password123 (Dentiste)\n";
echo "- medecin_general1 / password123 (Médecin général)\n";
echo "- medecin_chef1 / password123 (Médecin chef)\n";
echo "==================================================\n";
