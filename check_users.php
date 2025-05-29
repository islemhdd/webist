<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Utilisateurs avec leurs rôles:\n";
echo "================================\n";

$users = \App\Models\User::with('role')->get();

foreach ($users as $user) {
    echo "ID: " . $user->id . "\n";
    echo "Username: " . $user->username . "\n";
    echo "Rôle: " . $user->role->name . "\n";

    // Check if user is an officer
    $officer = $user->isOfficer();
    if ($officer) {
        echo "Officer ID: " . $officer->id . "\n";
        echo "Officer Battalion: " . ($officer->bat ?? 'N/A') . "\n";
    } else {
        echo "Not an officer\n";
    }
    echo "--------------------------------\n";
}

echo "\nRôles disponibles:\n";
echo "==================\n";
$roles = \App\Models\Role::all();
foreach ($roles as $role) {
    echo "ID: " . $role->id . " - Name: " . $role->name . "\n";
}

echo "\nTable des officiers:\n";
echo "====================\n";
try {
    $officers = \App\Models\Officer::with('role')->get();
    foreach ($officers as $officer) {
        echo "Officer ID: " . $officer->id . "\n";
        echo "Username: " . $officer->username . "\n";
        echo "Rôle: " . $officer->role->name . "\n";
        echo "Battalion: " . ($officer->bat ?? 'N/A') . "\n";
        echo "--------------------------------\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des officiers: " . $e->getMessage() . "\n";
}

echo "\nVérification de la structure User->isOfficer():\n";
echo "===============================================\n";
$problematicUsers = \App\Models\User::whereHas('role', function($q) {
    $q->whereIn('name', ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général']);
})->get();

foreach ($problematicUsers as $user) {
    echo "User ID: " . $user->id . " - " . $user->username . " (" . $user->role->name . ")\n";

    // Direct check in officers table
    try {
        $officerExists = \App\Models\Officer::where('id', $user->id)->first();
        if ($officerExists) {
            echo "  -> Existe dans table officers: OUI\n";
        } else {
            echo "  -> Existe dans table officers: NON\n";
        }
    } catch (Exception $e) {
        echo "  -> Erreur vérification: " . $e->getMessage() . "\n";
    }

    $officer = $user->isOfficer();
    if ($officer) {
        echo "  -> isOfficer() retourne: Officer object (ID: " . $officer->id . ")\n";
    } else {
        echo "  -> isOfficer() retourne: NULL\n";
    }
    echo "\n";
}
