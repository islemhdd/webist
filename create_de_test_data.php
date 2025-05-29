<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\Patient;
use App\Models\Expulsion;
use App\Models\RHP;
use App\Models\Officer;
use Carbon\Carbon;

echo "Creating test data for DE system...\n";

// Get some students
$students = Student::take(10)->get();

if ($students->count() == 0) {
    echo "No students found. Please run student seeder first.\n";
    exit;
}

// Create some patients with different valider_rhp states
foreach ($students->take(5) as $index => $student) {
    $patient = Patient::updateOrCreate(
        ['matricule' => $student->matricule],
        [
            'valider' => $index % 2 == 0 ? 1 : 0, // Some validated, some not
            'valider_rhp' => $index % 3 == 0 ? 1 : 0, // Some RHP validated
            'validated_at' => $index % 2 == 0 ? Carbon::now() : null,
            'created_at' => Carbon::now()->subDays(rand(1, 30))
        ]
    );
    echo "Created/Updated patient for matricule: {$student->matricule}\n";
}

// Create some expulsions
foreach ($students->take(3) as $index => $student) {
    $expulsion = Expulsion::create([
        'matricule' => $student->matricule,
        'motif_expulsion' => [
            'Comportement inapproprié',
            'Absence injustifiée',
            'Non-respect du règlement'
        ][$index],
        'date_expulsion' => Carbon::now()->subDays(rand(1, 15)),
        'description' => 'Description détaillée de l\'expulsion #' . ($index + 1),
        'created_at' => Carbon::now()->subDays(rand(1, 15))
    ]);
    echo "Created expulsion for matricule: {$student->matricule}\n";
}

// Create some RHP assignments
$officers = Officer::take(3)->get();
if ($officers->count() > 0) {
    foreach ($officers as $index => $officer) {
        $rhp = RHP::create([
            'officer_id' => $officer->id,
            'date_assignation' => Carbon::now()->addDays($index),
            'periode' => $index % 2 == 0 ? 'matin' : 'apres_midi',
            'notes' => 'Notes pour l\'assignation #' . ($index + 1)
        ]);
        echo "Created RHP assignment for officer ID: {$officer->id}\n";
    }
} else {
    echo "No officers found. Skipping RHP assignments.\n";
}

echo "Test data created successfully!\n";
