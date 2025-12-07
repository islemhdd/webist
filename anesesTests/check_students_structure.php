<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Structure complète de la table students ===\n";
$columns = DB::select('DESCRIBE students');
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\n=== Test de données de la table students ===\n";
$students = DB::table('students')->limit(3)->get();
foreach ($students as $student) {
    echo "- Matricule: {$student->matricule}, Nom: {$student->nom} {$student->prenom}\n";
}
