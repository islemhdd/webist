<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\User;

echo "=== VÉRIFICATION DES RÔLES MÉDICAUX ===\n\n";

// Vérifier tous les rôles existants
echo "1. Tous les rôles dans la base de données:\n";
$roles = Role::all();
foreach($roles as $role) {
    echo "- ID: {$role->id}, Nom: '{$role->name}'\n";
}

echo "\n2. Utilisateurs médicaux et leurs rôles:\n";
$medicalUsers = User::whereHas('role', function($query) {
    $query->whereIn('name', [
        'Psychologue',
        'Dentiste',
        'Médecin général',
        'Medecin general',
        'Medecin',
        'Chef Medecin',
        'Medecin chef'
    ]);
})->with('role')->get();

foreach($medicalUsers as $user) {
    echo "- User: {$user->username}, Rôle: '{$user->role->name}'\n";
}

echo "\n3. Mapping actuel dans MedicalSpecialtyController:\n";
$mapping = [
    'Psychologue' => 'psycho',
    'Dentiste' => 'dentiste',
    'Médecin général' => 'médecin générale',
    'Medecin' => 'all'
];

foreach($mapping as $role => $specialty) {
    $roleExists = Role::where('name', $role)->exists();
    echo "- '{$role}' => '{$specialty}' - " . ($roleExists ? "✅ Existe" : "❌ N'existe pas") . "\n";
}

echo "\n=== FIN VÉRIFICATION ===\n";
