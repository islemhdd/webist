<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Models\Expulsion;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        api: __DIR__.'/routes/api.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create test expulsions for today
$today = now()->toDateString();

$expulsions = [
    ['matricule' => 'TEST001', 'motif_expulsion' => 'Retard', 'date_expulsion' => $today],
    ['matricule' => 'TEST002', 'motif_expulsion' => 'Absence non justifiée', 'date_expulsion' => $today],
    ['matricule' => 'TEST003', 'motif_expulsion' => 'Retard', 'date_expulsion' => $today],
    ['matricule' => 'TEST004', 'motif_expulsion' => 'Comportement inapproprié', 'date_expulsion' => $today],
];

foreach ($expulsions as $data) {
    Expulsion::create($data);
    echo "Created expulsion for {$data['matricule']} - {$data['motif_expulsion']}\n";
}

echo "Created " . count($expulsions) . " test expulsions for today ($today)\n";
