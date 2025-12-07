<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// Boot the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\Patient;
use Carbon\Carbon;

echo "=== MATRICULES ANALYSIS ===\n";
$today = Carbon::today();

// Get patient matricules for today
$patientMatricules = Patient::whereDate('created_at', $today)->pluck('matricule')->unique();
echo "Patient matricules today: " . $patientMatricules->implode(', ') . "\n\n";

// Check which grade each patient matricule belongs to
foreach($patientMatricules as $matricule) {
    $student = Student::where('matricule', $matricule)->first();
    if ($student) {
        echo "Matricule $matricule -> Grade: " . $student->grade . "\n";
    } else {
        echo "Matricule $matricule -> NOT FOUND in students table\n";
    }
}

echo "\n=== GRADE 3 STUDENTS SAMPLE ===\n";
$grade3Students = Student::where('grade', '3')->take(5)->pluck('matricule');
echo "Grade 3 matricules: " . $grade3Students->implode(', ') . "\n";

echo "\n=== CHECKING SEEDED DATA ===\n";
// Check if there's overlap between patient and student matricules
$allStudentMatricules = Student::pluck('matricule');
$overlap = $patientMatricules->intersect($allStudentMatricules);
echo "Overlapping matricules: " . $overlap->implode(', ') . "\n";
echo "Number of overlapping matricules: " . $overlap->count() . "\n";
