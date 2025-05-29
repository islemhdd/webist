<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\Student;
use App\Models\Expulsion;

echo "Checking grades for today's data...\n\n";

$today = now()->toDateString();

echo "1. Students with patient records today:\n";
$patients = Patient::whereDate('created_at', $today)->get();
foreach ($patients as $patient) {
    $student = Student::where('matricule', $patient->matricule)->first();
    $grade = $student ? $student->grade : 'No student found';
    echo "  Matricule: {$patient->matricule} - Grade: $grade\n";
}

echo "\n2. Students with expulsion records today:\n";
$expulsions = Expulsion::whereDate('date_expulsion', $today)->get();
foreach ($expulsions as $expulsion) {
    $student = Student::where('matricule', $expulsion->matricule)->first();
    $grade = $student ? $student->grade : 'No student found';
    echo "  Matricule: {$expulsion->matricule} - Grade: $grade\n";
}

echo "\n3. Available grades in student table:\n";
$grades = Student::select('grade')->distinct()->pluck('grade')->sort();
foreach ($grades as $grade) {
    $count = Student::where('grade', $grade)->count();
    echo "  Grade $grade: $count students\n";
}

echo "\n4. Sample students from each grade:\n";
foreach ($grades as $grade) {
    $students = Student::where('grade', $grade)->limit(3)->get();
    echo "  Grade $grade:\n";
    foreach ($students as $student) {
        echo "    - Matricule: {$student->matricule}\n";
    }
}

echo "\nGrade analysis completed!\n";
