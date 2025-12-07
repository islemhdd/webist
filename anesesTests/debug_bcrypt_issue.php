<?php

// Debug script to check why "This password does not use the Bcrypt algorithm" error is occurring
require_once __DIR__ . '/vendor/autoload.php';

// Import namespaces before any code
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "\n=== TESTING PASSWORD VERIFICATION ===\n";

    // 1. Create a test user in memory with a Bcrypt hashed password
    $testUsername = "test_user_" . mt_rand(1000, 9999);
    $testPassword = "test_password";
    $hashedPassword = Hash::make($testPassword);

    echo "Test Username: $testUsername\n";
    echo "Test Password: $testPassword\n";
    echo "Hashed Password: $hashedPassword\n\n";

    // 2. Test hashing and verifying with Laravel's Hash facade
    echo "TESTING HASH VERIFICATION:\n";
    if (Hash::check($testPassword, $hashedPassword)) {
        echo "✓ Hash verification works correctly\n";
    } else {
        echo "✗ Hash verification failed!\n";
    }

    // 3. Try to authenticate a real user
    echo "\nTESTING REAL AUTHENTICATION:\n";
    $user = DB::table('users')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->select('users.id', 'users.username', 'users.password', 'roles.name as role_name')
        ->first();

    if ($user) {
        echo "Using real user for testing: {$user->username} (Role: {$user->role_name})\n";
        echo "Password hash: {$user->password}\n";

        // Check if password is actually using Bcrypt
        if (strpos($user->password, '$2y$') === 0) {
            echo "✓ Password is using Bcrypt format\n";
        } else {
            echo "✗ Password is NOT using Bcrypt format!\n";
        }

        // Check login with common passwords
        $commonPasswords = ['password123', 'password', '123456', 'admin', $user->username];
        echo "\nTrying common passwords:\n";
        foreach ($commonPasswords as $password) {
            $result = Auth::attempt([
                'username' => $user->username,
                'password' => $password
            ]);
            echo "Password '$password': " . ($result ? "✓ Success" : "✗ Failed") . "\n";
        }
    } else {
        echo "No users found in database!\n";
    }

    echo "\n=== CHECKING HASHING CONFIGURATION ===\n";

    // Check if config/hashing.php exists
    $hashingConfigFile = __DIR__ . '/config/hashing.php';
    if (file_exists($hashingConfigFile)) {
        echo "Hashing config file exists\n";
        include $hashingConfigFile;
    } else {
        echo "Hashing config file not found, using Laravel defaults\n";
    }

    echo "\nDefault hashing driver: " . config('hashing.driver', 'bcrypt') . "\n";
    echo "Bcrypt rounds: " . config('hashing.bcrypt.rounds', '10') . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
