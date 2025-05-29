<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Vérification des mots de passe des utilisateurs:\n";
echo "================================================\n";

$users = \App\Models\User::with('role')->get();

foreach ($users as $user) {
    echo "Username: " . $user->username . "\n";
    echo "Rôle: " . $user->role->name . "\n";
    echo "Password Hash: " . $user->password . "\n";

    // Test with common passwords
    $testPasswords = ['password', '123456', 'admin', $user->username, strtolower($user->username)];

    foreach ($testPasswords as $testPassword) {
        if (\Illuminate\Support\Facades\Hash::check($testPassword, $user->password)) {
            echo "✅ Mot de passe trouvé: '" . $testPassword . "'\n";
            break;
        }
    }
    echo "--------------------------------\n";
}
