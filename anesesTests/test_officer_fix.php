<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing officer creation from user data:\n";
echo "======================================\n";

// Find a user with officer role
$user = \App\Models\User::whereHas('role', function($q) {
    $q->whereIn('name', ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général']);
})->first();

if ($user) {
    echo "User found: " . $user->username . " (" . $user->role->name . ")\n";
    echo "User ID: " . $user->id . "\n";
    
    $officer = $user->isOfficer();
    if ($officer) {
        echo "Officer created successfully!\n";
        echo "Officer ID: " . $officer->id . "\n";
        echo "Officer role: " . $officer->role->name . "\n";
        echo "Officer username: " . $officer->username . "\n";
        echo "Officer bat: " . ($officer->bat ?? 'N/A') . "\n";
    } else {
        echo "Failed to create officer object\n";
    }
} else {
    echo "No officer users found\n";
}

echo "\nTesting AuthController redirect:\n";
echo "================================\n";

// Simulate what AuthController does
if ($user) {
    $officer = $user->isOfficer();
    if ($officer) {
        echo "Redirect would be to: brigade.statistics with id=" . $officer->id . "\n";
    }
}
