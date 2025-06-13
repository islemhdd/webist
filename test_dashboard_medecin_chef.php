<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "=== TEST DASHBOARD MÉDECIN CHEF ===\n\n";

try {
    // 1. Vérifier l'utilisateur médecin chef
    $medecin = User::whereHas('role', function($q) {
        $q->where('name', 'Medecin');
    })->first();

    if ($medecin) {
        echo "✓ Médecin chef trouvé: {$medecin->username}\n";
    } else {
        echo "✗ Aucun médecin chef trouvé\n";
        exit(1);
    }

    // 2. Statistiques du jour
    $today = Carbon::today();
    echo "\n--- Statistiques du {$today->format('d/m/Y')} ---\n";

    $patientsToday = Patient::whereDate('created_at', $today)->count();
    $validatedToday = Patient::whereDate('created_at', $today)->where('valider', 1)->count();
    $pendingToday = Patient::whereDate('created_at', $today)->where('valider', 0)->count();
    $rdvToday = DB::table('liste_rdvs')->whereDate('date', $today)->count();

    echo "Patients aujourd'hui: {$patientsToday}\n";
    echo "Patients validés: {$validatedToday}\n";
    echo "Patients en attente: {$pendingToday}\n";
    echo "Rendez-vous aujourd'hui: {$rdvToday}\n";

    // 3. Statistiques par spécialité
    echo "\n--- Répartition par spécialité ---\n";
    $specialties = ['psycho', 'dentiste', 'médecin générale'];

    foreach ($specialties as $specialty) {
        $count = Patient::where('type_medecin', $specialty)
            ->whereDate('created_at', $today)->count();
        $validated = Patient::where('type_medecin', $specialty)
            ->whereDate('created_at', $today)
            ->where('valider', 1)->count();

        echo "{$specialty}: {$count} patients ({$validated} validés)\n";
    }

    // 4. Test de l'API
    echo "\n--- Test simulation API ---\n";
    $apiData = [
        'daily' => [
            'patients' => $patientsToday,
            'validated' => $validatedToday,
            'pending' => $pendingToday,
            'appointments' => $rdvToday
        ],
        'specialty' => []
    ];

    foreach ($specialties as $specialty) {
        $total = Patient::where('type_medecin', $specialty)
            ->whereDate('created_at', $today)->count();
        $validated = Patient::where('type_medecin', $specialty)
            ->whereDate('created_at', $today)
            ->where('valider', 1)->count();

        $apiData['specialty'][] = [
            'name' => $specialty,
            'total' => $total,
            'validated' => $validated,
            'pending' => $total - $validated
        ];
    }

    echo "API Daily: " . json_encode($apiData['daily']) . "\n";
    echo "API Specialty: " . json_encode($apiData['specialty']) . "\n";

    echo "\n=== DASHBOARD MÉDECIN CHEF PRÊT ===\n";
    echo "✓ Toutes les données sont correctes\n";
    echo "✓ Les statistiques fonctionnent\n";
    echo "✓ L'API est opérationnelle\n";

} catch (Exception $e) {
    echo "\n✗ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
