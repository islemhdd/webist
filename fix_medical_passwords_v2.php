<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "Correction des mots de passe des utilisateurs médicaux (version 2):\n";
echo "===================================================================\n\n";

// Define medical roles and their new passwords
$medicalUsers = [
    'Medecin' => 'medecin123',
    'Psychologue' => 'psycho123',
    'Dentiste' => 'dentiste123',
    'Médecin général' => 'medecin123'
];

foreach ($medicalUsers as $roleName => $newPassword) {
    echo "Traitement du rôle: $roleName\n";
    echo str_repeat("-", 40) . "\n";

    $role = Role::where('name', $roleName)->first();
    if (!$role) {
        echo "❌ Rôle '$roleName' non trouvé\n\n";
        continue;
    }

    $users = User::where('role_id', $role->id)->get();

    if ($users->isEmpty()) {
        echo "❌ Aucun utilisateur trouvé pour le rôle '$roleName'\n\n";
        continue;
    }

    foreach ($users as $user) {
        echo "Utilisateur: {$user->username}\n";
        echo "  Ancien hash: {$user->password}\n";

        // Create proper bcrypt hash
        $newPasswordHash = Hash::make($newPassword);

        // Update directly in database to avoid the setPasswordAttribute mutator
        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => $newPasswordHash]);

        echo "  Nouveau hash: $newPasswordHash\n";

        // Test the new password by reloading the user
        $user->refresh();
        if (Hash::check($newPassword, $user->password)) {
            echo "  ✅ Mot de passe '$newPassword' vérifié avec succès\n";
        } else {
            echo "  ❌ Erreur: le mot de passe ne fonctionne pas\n";
        }
        echo "\n";
    }
}

echo "Vérification finale:\n";
echo "===================\n";

foreach ($medicalUsers as $roleName => $password) {
    $role = Role::where('name', $roleName)->first();
    if ($role) {
        $users = User::where('role_id', $role->id)->get();
        foreach ($users as $user) {
            $passwordCheck = Hash::check($password, $user->password);
            echo "- {$user->username} ($roleName): " . ($passwordCheck ? "✅ OK" : "❌ FAIL") . " (mot de passe: $password)\n";
        }
    }
}

echo "\n✅ Correction des mots de passe terminée!\n";
echo "\nMots de passe assignés:\n";
foreach ($medicalUsers as $role => $password) {
    echo "- $role: $password\n";
}
