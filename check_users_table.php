<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== USERS TABLE STRUCTURE CHECK ===\n";

try {
    echo "Users table columns:\n";
    $columns = Schema::getColumnListing('users');
    foreach ($columns as $column) {
        echo "- $column\n";
    }

    echo "\nSample user data:\n";
    $user = DB::table('users')->first();
    if ($user) {
        foreach ((array)$user as $key => $value) {
            echo "$key: $value\n";
        }
    } else {
        echo "No users found in database\n";
    }

    echo "\nChecking specific columns:\n";
    echo "Has 'email' column: " . (Schema::hasColumn('users', 'email') ? "YES" : "NO") . "\n";
    echo "Has 'username' column: " . (Schema::hasColumn('users', 'username') ? "YES" : "NO") . "\n";
    echo "Has 'role_id' column: " . (Schema::hasColumn('users', 'role_id') ? "YES" : "NO") . "\n";
    echo "Has 'Role' column: " . (Schema::hasColumn('users', 'Role') ? "YES" : "NO") . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== END CHECK ===\n";
