<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Testing DE user authentication...\n";

$user = User::where('username', 'DE')->first();

if ($user) {
    echo "✓ DE User found\n";
    echo "  Username: {$user->username}\n";
    echo "  Role: {$user->role}\n";
    echo "  Email: {$user->email}\n";

    $passwordCheck = Hash::check('123456789', $user->password);
    echo "  Password check: " . ($passwordCheck ? "✓ Valid" : "✗ Invalid") . "\n";
} else {
    echo "✗ DE user not found\n";
}

echo "\nDone!\n";
