<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('exemptions');
echo 'Exemptions table columns: ' . implode(', ', $columns) . "\n";
