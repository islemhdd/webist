<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Expulsion;

$today = now()->toDateString();

// Create test expulsions
Expulsion::create([
    'matricule' => 'TEST001',
    'motif_expulsion' => 'Retard',
    'date_expulsion' => $today
]);

Expulsion::create([
    'matricule' => 'TEST002',
    'motif_expulsion' => 'Absence non justifiée',
    'date_expulsion' => $today
]);

Expulsion::create([
    'matricule' => 'TEST003',
    'motif_expulsion' => 'Retard',
    'date_expulsion' => $today
]);

echo "Created test expulsions for today: $today\n";
