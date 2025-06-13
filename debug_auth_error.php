<?php

// Test script to debug the "This password does not use the Bcrypt algorithm" error
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "=== AUTHENTICATION ERROR DEBUGGING ===\n\n";

    // Get all users
    $users = \App\Models\User::with('role')->get();

    echo "Found " . count($users) . " users to test.\n\n";

    // 1. Verify if the customized User model has correct methods for authentication
    echo "CHECKING USER MODEL AUTHENTICATION METHODS:\n";
    $userModel = new \App\Models\User();
    echo "- getAuthIdentifierName(): " . $userModel->getAuthIdentifierName() . "\n";
    echo "- Uses standard Authenticatable trait: " . (is_subclass_of($userModel, 'Illuminate\Foundation\Auth\User') ? "Yes" : "No") . "\n\n";

    // 2. Attempt manual authentication for each user with incorrect password to get error message
    echo "SIMULATING LOGIN ATTEMPTS WITH INCORRECT PASSWORD:\n";
    echo "------------------------------------------------\n";

    foreach ($users as $i => $user) {
        if ($i >= 3) break; // Limit to first 3 users

        echo "Testing user: {$user->username} (Role: {$user->role->name})\n";

        // Create fake request and attempt login
        $request = Request::create('/login', 'POST', [
            'username' => $user->username,
            'password' => 'definitely_wrong_password'
        ]);

        // Create a new instance of the auth controller
        $authController = new \App\Http\Controllers\AuthController();

        try {
            // Call the login method directly (this will likely fail, but we can catch the error)
            $response = $authController->login($request);
            echo "  ❌ Expected authentication to fail, but it succeeded!\n";
        } catch (\Exception $e) {
            echo "  ✅ Authentication failed as expected: " . $e->getMessage() . "\n";
        }

        // Now test a direct Auth::attempt
        $result = Auth::attempt([
            'username' => $user->username,
            'password' => 'definitely_wrong_password'
        ]);

        echo "  Direct Auth::attempt result: " . ($result ? "✅ Success (unexpected)" : "❌ Failed (expected)") . "\n";

        // Check if the session has error messages
        if (session()->has('errors')) {
            $errors = session('errors')->all();
            echo "  Session errors: " . implode(", ", $errors) . "\n";
        }

        echo "------------------------------------------------\n";
    }

    // 3. Check Auth configuration
    echo "\nAUTH CONFIGURATION CHECK:\n";
    echo "- Default guard: " . config('auth.defaults.guard') . "\n";
    echo "- Default provider: " . config('auth.guards.' . config('auth.defaults.guard') . '.provider') . "\n";
    echo "- Provider driver: " . config('auth.providers.' . config('auth.guards.' . config('auth.defaults.guard') . '.provider') . '.driver') . "\n";
    echo "- Provider model: " . config('auth.providers.' . config('auth.guards.' . config('auth.defaults.guard') . '.provider') . '.model') . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
