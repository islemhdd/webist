<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$matricules = DB::table('students')->limit(15)->pluck('matricule');
echo "Matricules existants: " . implode(', ', $matricules->toArray()) . "\n";
