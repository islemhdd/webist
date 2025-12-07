<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;
use App\Models\Patient;

// Get first 5 students
$students = Student::limit(5)->get();

foreach($students as $student) {
    // Check if patient already exists
    $existingPatient = Patient::where('matricule', $student->matricule)->first();
    if (!$existingPatient) {
        Patient::create([
            'matricule' => $student->matricule,
            'valider' => 0
        ]);
        echo "Created patient for matricule: " . $student->matricule . "\n";
    } else {
        echo "Patient already exists for matricule: " . $student->matricule . "\n";
    }
}

echo "Done creating test patients!\n";
