<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Test de connexion avec mot de passe 'password':\n";
echo "===============================================\n";

$hash = '$2y$12$inS/lAZ.0W36vSGOvvMA/ehAWfC6.3fTzR1i2H3gMGRerWNw1ddJK';

if (\Illuminate\Support\Facades\Hash::check('password', $hash)) {
    echo "✅ Le mot de passe 'password' fonctionne!\n";
} else {
    echo "❌ Le mot de passe 'password' ne fonctionne pas.\n";
}

// Test autres mots de passe communs
$passwords = ['123456', 'admin', 'test', '12345678', 'qwerty'];
foreach ($passwords as $pwd) {
    if (\Illuminate\Support\Facades\Hash::check($pwd, $hash)) {
        echo "✅ Le mot de passe '" . $pwd . "' fonctionne!\n";
    }
}

// Test avec le deuxième hash
echo "\nTest avec le hash différent:\n";
$hash2 = '$2y$12$eUPdWJdXQV2qChUux4fm1uv3u57kYKMQK6PqqdJTiHqtJfu7HwHlK';
if (\Illuminate\Support\Facades\Hash::check('password', $hash2)) {
    echo "✅ Le mot de passe 'password' fonctionne pour le Directeur des études!\n";
} else {
    echo "❌ Le mot de passe 'password' ne fonctionne pas pour le Directeur des études.\n";
}
