<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Test des mots de passe possibles:\n";
echo "=================================\n";

$users = \App\Models\User::with('role')->take(3)->get();

foreach ($users as $user) {
    echo "\nUtilisateur: " . $user->username . "\n";
    echo "Hash: " . $user->password . "\n";

    $passwords = ['usser', 'password', '123456', 'admin', '12345', strtolower($user->username)];

    foreach ($passwords as $testPassword) {
        if (\Illuminate\Support\Facades\Hash::check($testPassword, $user->password)) {
            echo "✅ Mot de passe trouvé: '" . $testPassword . "'\n";
            break;
        }
    }
}

// Test de création d'un nouveau hash pour voir
echo "\n\nTest de hash 'usser':\n";
$newHash = \Illuminate\Support\Facades\Hash::make('usser');
echo "Nouveau hash: " . $newHash . "\n";

// Test si les hashs existants correspondent à 'usser'
$existingHash = '$2y$12$inS/lAZ.0W36vSGOvvMA/ehAWfC6.3fTzR1i2H3gMGRerWNw1ddJK';
if (\Illuminate\Support\Facades\Hash::check('usser', $existingHash)) {
    echo "✅ Le hash existant correspond à 'usser'!\n";
} else {
    echo "❌ Le hash existant ne correspond pas à 'usser'.\n";
}
