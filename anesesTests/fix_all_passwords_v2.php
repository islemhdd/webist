<?php

// Fix all user passwords to use proper Bcrypt hashing
require_once __DIR__ . '/vendor/autoload.php';

// Import namespaces before any code
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "=== Fixing All User Passwords to Use Bcrypt ===\n";

    try {
        // Get all users
        $users = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', 'users.username', 'users.password', 'roles.name as role_name')
            ->get();

        echo "Found " . count($users) . " users to check:\n";

        $updated = 0;
        $skipped = 0;

        foreach ($users as $user) {
            echo "\nUser: {$user->username} (Role: " . ($user->role_name ?? 'Unknown') . ")\n";

            // Check if password starts with $2y$ (Bcrypt hash)
            if (strpos($user->password, '$2y$') === 0) {
                echo "✓ Password already using Bcrypt. Skipping.\n";
                $skipped++;
            } else {
                echo "Password does not use Bcrypt: {$user->password}\n";

                // Set a default password 'password123' for all users
                $newPassword = Hash::make('password123');

                try {
                    // Update directly in database
                    $result = DB::table('users')
                        ->where('id', $user->id)
                        ->update(['password' => $newPassword]);

                    if ($result) {
                        echo "✅ Password updated successfully to: {$newPassword}\n";
                        $updated++;
                    } else {
                        echo "❌ Failed to update password\n";
                    }
                } catch (\Exception $e) {
                    echo "❌ Error updating password: " . $e->getMessage() . "\n";
                }
            }
            echo "---\n";
        }

        echo "\n=== Summary ===\n";
        echo "Total users checked: " . count($users) . "\n";
        echo "Already using Bcrypt: {$skipped}\n";
        echo "Updated to Bcrypt: {$updated}\n";

    } catch (\Exception $e) {
        echo "Error retrieving users: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
} catch (\Exception $e) {
    echo "Bootstrap error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
