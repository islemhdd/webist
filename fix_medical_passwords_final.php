<?php

// Fix medical user passwords to use proper Bcrypt hashing
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== Fixing Medical User Passwords ===\n";

// Get all medical users
$medicalUsers = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
    ->select('users.id', 'users.username', 'users.password', 'roles.name as role_name')
    ->get();

echo "Found " . count($medicalUsers) . " medical users to update:\n";

foreach ($medicalUsers as $user) {
    echo "User: {$user->username} ({$user->role_name})\n";
    echo "Current password: {$user->password}\n";

    // Generate a proper Bcrypt hash for 'password123'
    $newPassword = Hash::make('password123');

    // Update directly in database
    $updated = DB::table('users')
        ->where('id', $user->id)
        ->update(['password' => $newPassword]);

    if ($updated) {
        echo "✅ Password updated successfully\n";
        echo "New hash: {$newPassword}\n";
    } else {
        echo "❌ Failed to update password\n";
    }
    echo "---\n";
}

echo "\n=== Verification - Testing Login ===\n";
// Re-fetch users to verify
$updatedUsers = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->whereIn('roles.name', ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])
    ->select('users.id', 'users.username', 'users.password', 'roles.name as role_name')
    ->get();

foreach ($updatedUsers as $user) {
    try {
        $canLogin = Hash::check('password123', $user->password);
        echo "User: {$user->username} - Can login with 'password123': " . ($canLogin ? '✅ YES' : '❌ NO') . "\n";
    } catch (Exception $e) {
        echo "User: {$user->username} - Error checking password: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Medical User Passwords Fixed Successfully ===\n";
