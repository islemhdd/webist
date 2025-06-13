<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Vérification des rôles médicaux ===\n";
$medicalRoles = DB::table('roles')
    ->whereIn('name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
    ->get();

foreach ($medicalRoles as $role) {
    echo "Rôle: {$role->name} (ID: {$role->id})\n";
}

echo "\n=== Utilisateurs médicaux ===\n";
$medicalUsers = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
    ->select('users.id', 'users.username', 'users.role_id', 'roles.name as role_name')
    ->get();

foreach ($medicalUsers as $user) {
    echo "User: {$user->username} - Rôle: {$user->role_name} (ID: {$user->role_id})\n";
}

echo "\n=== Test du Dr. Médecin Chef ===\n";
$drMedecin = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->where('users.username', 'Dr. Médecin Chef')
    ->select('users.*', 'roles.name as role_name')
    ->first();

if ($drMedecin) {
    echo "Username: {$drMedecin->username}\n";
    echo "Role ID: {$drMedecin->role_id}\n";
    echo "Role Name: {$drMedecin->role_name}\n";
} else {
    echo "Utilisateur 'Dr. Médecin Chef' non trouvé!\n";
}
