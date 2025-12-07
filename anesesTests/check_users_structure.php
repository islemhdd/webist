<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Users table structure:\n";
$columns = DB::select('DESCRIBE users');
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}
