<?php

// Final fix script for password issues
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "=== FINAL PASSWORD HASHING FIX ===\n\n";

    // 1. Get all users
    $users = DB::table('users')->get();

    echo "Found " . count($users) . " users to check.\n\n";

    $updated = 0;
    $skipped = 0;
    $errors = 0;

    // 2. Diagnostic mode first to see what's going on with each user
    echo "CHECKING ALL USERS FOR PASSWORD ISSUES:\n";
    echo "--------------------------------------\n";

    foreach ($users as $user) {
        echo "User ID: {$user->id}, Username: " . ($user->username ?? $user->name ?? 'Unknown') . "\n";

        // Skip blank/null passwords
        if (empty($user->password)) {
            echo "  ⚠️ Empty password, setting default password\n";

            try {
                $newPassword = Hash::make('password123');
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $newPassword]);
                echo "  ✅ Set default password successfully\n";
                $updated++;
            } catch (\Exception $e) {
                echo "  ❌ Error setting default password: " . $e->getMessage() . "\n";
                $errors++;
            }
            continue;
        }

        // Check if password hash format is correct
        if (substr($user->password, 0, 4) === '$2y$') {
            echo "  ✅ Password hash is already using Bcrypt ($2y$)\n";
            $skipped++;
        }
        // Check if using legacy hash format $2a$ (old bcrypt)
        else if (substr($user->password, 0, 4) === '$2a$') {
            echo "  ⚠️ Password using old Bcrypt format ($2a$), updating...\n";

            try {
                // Convert the old hash to a new hash
                $newPassword = Hash::make('password123');
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $newPassword]);
                echo "  ✅ Updated to modern Bcrypt format\n";
                $updated++;
            } catch (\Exception $e) {
                echo "  ❌ Error updating password: " . $e->getMessage() . "\n";
                $errors++;
            }
        }
        // Handle MD5 hashes
        else if (strlen($user->password) === 32 && ctype_xdigit($user->password)) {
            echo "  ⚠️ Password appears to be MD5 hash, updating to Bcrypt...\n";

            try {
                $newPassword = Hash::make('password123');
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $newPassword]);
                echo "  ✅ Converted MD5 to Bcrypt\n";
                $updated++;
            } catch (\Exception $e) {
                echo "  ❌ Error updating MD5 password: " . $e->getMessage() . "\n";
                $errors++;
            }
        }
        // Handle plaintext or unknown format
        else {
            echo "  ⚠️ Password is in unknown format, updating...\n";
            echo "  Current hash: " . $user->password . "\n";

            try {
                // Create a new bcrypt hash
                $newPassword = Hash::make('password123');
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $newPassword]);
                echo "  ✅ Created new Bcrypt hash\n";
                $updated++;
            } catch (\Exception $e) {
                echo "  ❌ Error creating new hash: " . $e->getMessage() . "\n";
                $errors++;
            }
        }
    }

    echo "\n=== SUMMARY ===\n";
    echo "Total users checked: " . count($users) . "\n";
    echo "Already using correct Bcrypt: {$skipped}\n";
    echo "Updated passwords: {$updated}\n";
    echo "Errors encountered: {$errors}\n";

    // 3. Verify correct authentication configuration
    echo "\nCHECKING AUTHENTICATION CONFIGURATION:\n";
    echo "- Default bcrypt rounds: " . config('hashing.bcrypt.rounds', 12) . "\n";
    echo "- Default hashing driver: " . config('hashing.driver', 'bcrypt') . "\n";

    // 4. Clear cached configurations to make sure changes take effect
    echo "\nCLEARING CACHE:\n";
    try {
        Artisan::call('cache:clear');
        echo "✅ Cache cleared\n";
    } catch (\Exception $e) {
        echo "❌ Error clearing cache: " . $e->getMessage() . "\n";
    }

    echo "\nFINAL PASSWORD FIX COMPLETED\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
