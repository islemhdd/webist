<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;

echo "Checking Student model structure...\n\n";

$student = Student::first();

if ($student) {
    echo "Sample student data:\n";
    $studentArray = $student->toArray();
    foreach ($studentArray as $key => $value) {
        echo "  {$key}: {$value}\n";
    }

    echo "\nChecking for 'grade' field: " . (array_key_exists('grade', $studentArray) ? "✓ Found" : "✗ Not found") . "\n";
    echo "Checking for 'niveau' field: " . (array_key_exists('niveau', $studentArray) ? "✓ Found" : "✗ Not found") . "\n";
    echo "Checking for 'annee' field: " . (array_key_exists('annee', $studentArray) ? "✓ Found" : "✗ Not found") . "\n";
} else {
    echo "No students found in database.\n";
}

echo "\nDone!\n";
