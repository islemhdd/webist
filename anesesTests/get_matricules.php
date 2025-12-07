<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;

echo "=== SAMPLE MATRICULES FOR EACH GRADE ===\n";
$grade1 = Student::where('grade', '1')->take(3)->pluck('matricule');
$grade2 = Student::where('grade', '2')->take(3)->pluck('matricule');
$grade3 = Student::where('grade', '3')->take(3)->pluck('matricule');

echo "Grade 1 matricules: " . $grade1->implode(', ') . "\n";
echo "Grade 2 matricules: " . $grade2->implode(', ') . "\n";
echo "Grade 3 matricules: " . $grade3->implode(', ') . "\n";
