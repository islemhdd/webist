<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\listeRdv;
use App\Models\Convoncu;
use App\Models\Exemption;
use Carbon\Carbon;

$today = Carbon::today();

echo "Clearing old test data...\n";

// Delete test data created today
Patient::whereDate('created_at', $today)->delete();
listeRdv::whereDate('date', $today)->delete();
Convoncu::whereIn('matricule', ['2022055', '2022056', '2022064', '2022096', '2022120', '2022131', '2022002', '2022003', '2022004'])->delete();
Exemption::whereDate('date_debut', $today)->delete();

echo "Old test data cleared successfully\n";
