<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Database\Seeders\StatisticsDataSeeder;

echo "Running StatisticsDataSeeder...\n";

$seeder = new StatisticsDataSeeder();
$seeder->run();

echo "Seeder completed successfully\n";
