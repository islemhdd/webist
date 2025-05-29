<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Nettoyage des noms de rôles (suppression des espaces):\n";
echo "======================================================\n";

$roles = \App\Models\Role::all();

foreach ($roles as $role) {
    $originalName = $role->name;
    $trimmedName = trim($role->name);

    if ($originalName !== $trimmedName) {
        echo "Rôle ID " . $role->id . ": '" . $originalName . "' -> '" . $trimmedName . "'\n";
        $role->name = $trimmedName;
        $role->save();
    }
}

echo "\nVérification post-nettoyage:\n";
echo "============================\n";

$problematicUsers = \App\Models\User::whereHas('role', function($q) {
    $q->whereIn('name', ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général']);
})->get();

foreach ($problematicUsers as $user) {
    echo "User: " . $user->username . " (" . $user->role->name . ")\n";
    $officer = $user->isOfficer();
    if ($officer) {
        echo "  -> isOfficer() fonctionne: Officer ID " . $officer->id . "\n";
    } else {
        echo "  -> isOfficer() retourne encore NULL\n";
    }
}
