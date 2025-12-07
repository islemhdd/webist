<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Student;

echo "Creating additional test data for all grades...\n\n";

$today = now()->toDateString();
$now = now();

// Get students from different grades
$grade1Students = Student::where('grade', '1')->limit(3)->get();
$grade2Students = Student::where('grade', '2')->limit(3)->get();

echo "Selected students:\n";
echo "Grade 1: " . $grade1Students->pluck('matricule')->implode(', ') . "\n";
echo "Grade 2: " . $grade2Students->pluck('matricule')->implode(', ') . "\n";

// Create patients for Grade 1 students
$patientData = [];
foreach ($grade1Students as $index => $student) {
    $patientData[] = [
        'matricule' => $student->matricule,
        'valider' => 1,
        'valider_rhp' => $index < 2 ? 1 : 0, // 2 validated, 1 pending
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

// Create patients for Grade 2 students
foreach ($grade2Students as $index => $student) {
    $patientData[] = [
        'matricule' => $student->matricule,
        'valider' => 1,
        'valider_rhp' => $index < 1 ? 1 : 0, // 1 validated, 2 pending
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

DB::table('patients')->insert($patientData);
echo "Created " . count($patientData) . " additional patients\n";

// Create expulsions for Grade 1 students
$expulsionData = [];
$motifs = ['Absence', 'Retard', 'Insubordination'];
foreach ($grade1Students->take(2) as $index => $student) {
    $expulsionData[] = [
        'matricule' => $student->matricule,
        'motif_expulsion' => $motifs[$index],
        'date_expulsion' => $today,
        'description' => 'Grade 1 test expulsion #' . ($index + 1),
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

// Create expulsions for Grade 2 students
foreach ($grade2Students->take(1) as $index => $student) {
    $expulsionData[] = [
        'matricule' => $student->matricule,
        'motif_expulsion' => 'Bavardage',
        'date_expulsion' => $today,
        'description' => 'Grade 2 test expulsion #' . ($index + 1),
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

DB::table('expulsions')->insert($expulsionData);
echo "Created " . count($expulsionData) . " additional expulsions\n";

echo "\nUpdated statistics summary:\n";
echo "Total patients today: " . DB::table('patients')->whereDate('created_at', $today)->count() . "\n";

// Statistics by grade
foreach ([1, 2, 3] as $grade) {
    $patientCount = DB::table('patients')
        ->whereDate('created_at', $today)
        ->join('students', 'patients.matricule', '=', 'students.matricule')
        ->where('students.grade', $grade)
        ->count();
        
    $expulsionCount = DB::table('expulsions')
        ->whereDate('date_expulsion', $today)
        ->join('students', 'expulsions.matricule', '=', 'students.matricule')
        ->where('students.grade', $grade)
        ->count();
    
    echo "Grade $grade: $patientCount patients, $expulsionCount expulsions\n";
}

echo "\nAdditional test data creation completed!\n";
