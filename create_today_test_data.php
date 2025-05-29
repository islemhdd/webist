<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Creating test data for today's date...\n";

// Get some random student matricules
$students = DB::table('students')->limit(10)->get();

if ($students->isEmpty()) {
    echo "No students found in database!\n";
    exit(1);
}

$today = now()->toDateString();
$now = now();

echo "Today's date: $today\n";

// Create test patients for today
$patientData = [];
foreach ($students->take(6) as $index => $student) {
    $patientData[] = [
        'matricule' => $student->matricule,
        'valider' => 1, // Validated by infirmary
        'valider_rhp' => $index < 4 ? 1 : 0, // 4 validated by RHP, 2 pending
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

DB::table('patients')->insert($patientData);
echo "Created " . count($patientData) . " patients for today\n";

// Create test expulsions for today
$expulsionData = [];
$motifs = ['Bagarre', 'Retard répété', 'Manque de respect', 'Absence non justifiée'];
foreach ($students->take(4) as $index => $student) {
    $expulsionData[] = [
        'matricule' => $student->matricule,
        'motif_expulsion' => $motifs[$index % count($motifs)],
        'date_expulsion' => $today,
        'description' => 'Test expulsion #' . ($index + 1),
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

DB::table('expulsions')->insert($expulsionData);
echo "Created " . count($expulsionData) . " expulsions for today\n";

echo "Test data creation completed!\n";
echo "Statistics summary:\n";
echo "- Total patients today: " . DB::table('patients')->whereDate('created_at', $today)->count() . "\n";
echo "- RHP validated: " . DB::table('patients')->whereDate('created_at', $today)->where('valider_rhp', 1)->count() . "\n";
echo "- RHP pending: " . DB::table('patients')->whereDate('created_at', $today)->where('valider_rhp', 0)->count() . "\n";
echo "- Total expulsions today: " . DB::table('expulsions')->whereDate('date_expulsion', $today)->count() . "\n";

// Show expulsions by reason
$expulsionsByReason = DB::table('expulsions')
    ->whereDate('date_expulsion', $today)
    ->selectRaw('motif_expulsion, count(*) as count')
    ->groupBy('motif_expulsion')
    ->get();

echo "Expulsions by reason:\n";
foreach ($expulsionsByReason as $exp) {
    echo "  - {$exp->motif_expulsion}: {$exp->count}\n";
}
