<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Analyse du champ grade ===\n";

// Vérifier les grades des étudiants
$grades = DB::table('students')->distinct()->pluck('grade');
echo "Grades trouvés: " . implode(', ', $grades->toArray()) . "\n";

// Vérifier section_id
$sections = DB::table('students')->distinct()->pluck('section_id');
echo "Sections trouvées: " . implode(', ', $sections->toArray()) . "\n";

// Échantillon
$sample = DB::table('students')->limit(5)->get(['matricule', 'grade', 'section_id']);
echo "\nÉchantillon:\n";
foreach ($sample as $s) {
    echo "Matricule: {$s->matricule}, Grade: {$s->grade}, Section: {$s->section_id}\n";
}
