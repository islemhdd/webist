<?php

// Specific script to identify where the Bcrypt algorithm error occurs
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== DEBUGGING BCRYPT ALGORITHM ERROR ===\n\n";

    // Get the first user for testing
    $user = \App\Models\User::first();

    if (!$user) {
        echo "No users found in database!\n";
        exit(1);
    }

    echo "Using user for testing: {$user->username}\n";
    echo "Current password hash: {$user->password}\n";

    // Check hash format
    $isBcrypt = strpos($user->password, '$2y$') === 0;
    echo "Is using Bcrypt (\$2y\$): " . ($isBcrypt ? "Yes" : "No") . "\n\n";

    // Check password verification with Hash directly
    $testResult = Hash::check('password123', $user->password);
    echo "Direct Hash::check with 'password123': " . ($testResult ? "Succeeds" : "Fails") . "\n\n";

    // Test what happens with Auth::attempt directly
    echo "Testing Auth::attempt with correct and incorrect passwords:\n";
    echo "=======================================================\n";

    // First, reset any current session
    if (Auth::check()) {
        Auth::logout();
        echo "Logged out existing user\n";
    }

    // Create credentials for testing
    $credentials = [
        'username' => $user->username,
        'password' => 'invalid_password'
    ];

    // Try with invalid password
    try {
        $result = Auth::attempt($credentials);
        echo "Auth::attempt with invalid password: " . ($result ? "Succeeded (unexpected)" : "Failed (expected)") . "\n";
    } catch (\Exception $e) {
        echo "Exception during Auth::attempt with invalid password: " . $e->getMessage() . "\n";
    }

    // Try with 'password123' - a common test password
    $credentials['password'] = 'password123';
    try {
        $result = Auth::attempt($credentials);
        echo "Auth::attempt with 'password123': " . ($result ? "Succeeded" : "Failed") . "\n";
    } catch (\Exception $e) {
        echo "Exception during Auth::attempt with 'password123': " . $e->getMessage() . "\n";
    }

    // Test if we can login a user manually
    echo "\nTesting manual login with Auth::login():\n";
    try {
        Auth::login($user);
        echo "Auth::login() result: " . (Auth::check() ? "Succeeded" : "Failed") . "\n";
        echo "Authenticated user ID: " . (Auth::id() ?? 'None') . "\n";
    } catch (\Exception $e) {
        echo "Exception during Auth::login(): " . $e->getMessage() . "\n";
    }

    // Check configuration
    echo "\nLaravel configuration related to authentication:\n";
    echo "- APP_KEY defined: " . (env('APP_KEY') ? "Yes" : "No") . "\n";
    echo "- Hash driver: " . config('hashing.driver', 'bcrypt') . "\n";
    echo "- Bcrypt rounds: " . config('hashing.bcrypt.rounds', 10) . "\n";

    // Create a new test user with a known password to verify hashing
    echo "\nCreating a test user with a known password to verify hashing:\n";
    try {
        $testUser = new \App\Models\User();
        $testUser->username = 'test_user_' . time();
        $testUser->password = 'test_password'; // This should trigger the mutator
        echo "Password set through mutator: " . $testUser->password . "\n";

        // Manually hash a password
        $manualHash = Hash::make('test_password');
        echo "Manual Hash::make result: " . $manualHash . "\n";

        // Check that both approaches produce compatible hashes
        $checkResult = Hash::check('test_password', $testUser->password);
        echo "Verification of mutator hash: " . ($checkResult ? "Success" : "Failure") . "\n";
    } catch (\Exception $e) {
        echo "Exception during test user creation: " . $e->getMessage() . "\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
