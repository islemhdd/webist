<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Looking for DE users...\n";

$deUser = User::whereHas('role', function($query) {
    $query->where('name', 'Directeur des etudes');
})->first();

if ($deUser) {
    echo "DE User found:\n";
    echo "- Name: {$deUser->nom} {$deUser->prenom}\n";
    echo "- ID: {$deUser->id}\n";
    echo "- Email: {$deUser->email}\n";
    echo "- Role: {$deUser->role->name}\n";
} else {
    echo "No DE user found. Let's check all users:\n";
    $users = User::with('role')->get();
    foreach ($users as $user) {
        echo "- {$user->nom} {$user->prenom} ({$user->role->name})\n";
    }
}
