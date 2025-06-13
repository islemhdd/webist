<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\Officer;
use Illuminate\Support\Facades\Hash;

echo "=== CRÉATION DES RÔLES MÉDICAUX ===\n";

try {
    // Créer les nouveaux rôles médicaux
    $rolesData = [
        ['name' => 'Psychologue', 'description' => 'Service de psychologie'],
        ['name' => 'Dentiste', 'description' => 'Service dentaire'],
        ['name' => 'Medecin general', 'description' => 'Service de médecine générale']
    ];

    $createdRoles = [];

    foreach ($rolesData as $roleData) {
        $role = Role::firstOrCreate(
            ['name' => $roleData['name']],
            $roleData
        );

        $createdRoles[] = $role;
        echo "✅ Rôle '{$role->name}' créé avec ID: {$role->id}\n";
    }

    echo "\n=== CRÉATION DES COMPTES MÉDICAUX ===\n";

    // Créer les comptes pour chaque rôle médical
    $officersData = [
        [
            'role_name' => 'Psychologue',
            'nom' => 'Dr. Ahmed',
            'prenom' => 'Psychologue',
            'matricule' => 'PSY001',
            'email' => 'psychologue@enpei.dz',
            'password' => 'password123'
        ],
        [
            'role_name' => 'Dentiste',
            'nom' => 'Dr. Fatima',
            'prenom' => 'Dentiste',
            'matricule' => 'DENT001',
            'email' => 'dentiste@enpei.dz',
            'password' => 'password123'
        ],
        [
            'role_name' => 'Medecin general',
            'nom' => 'Dr. Mohamed',
            'prenom' => 'Generaliste',
            'matricule' => 'MG001',
            'email' => 'medecin.general@enpei.dz',
            'password' => 'password123'
        ]
    ];

    foreach ($officersData as $officerData) {
        $role = Role::where('name', $officerData['role_name'])->first();

        if ($role) {
            $officer = Officer::firstOrCreate(
                ['email' => $officerData['email']],
                [
                    'nom' => $officerData['nom'],
                    'prenom' => $officerData['prenom'],
                    'matricule' => $officerData['matricule'],
                    'email' => $officerData['email'],
                    'password' => Hash::make($officerData['password']),
                    'role_id' => $role->id
                ]
            );

            echo "✅ Compte '{$officer->nom} {$officer->prenom}' créé pour le rôle '{$role->name}'\n";
            echo "   📧 Email: {$officer->email}\n";
            echo "   🔑 Mot de passe: {$officerData['password']}\n";
            echo "   🆔 Matricule: {$officer->matricule}\n\n";
        }
    }

    echo "=== RÉSUMÉ DES RÔLES MÉDICAUX ===\n";
    $medicalRoles = Role::whereIn('name', ['Medecin', 'Psychologue', 'Dentiste', 'Medecin general'])->get();

    foreach ($medicalRoles as $role) {
        $officersCount = Officer::where('role_id', $role->id)->count();
        echo "🏥 {$role->name}: {$officersCount} compte(s)\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
