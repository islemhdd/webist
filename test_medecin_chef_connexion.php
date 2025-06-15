<?php

echo "🧪 SIMULATION CONNEXION MÉDECIN CHEF\n";
echo "====================================\n\n";

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Convoncu;
use App\Models\Student;

try {
    // Trouver un utilisateur médecin chef
    $medecin = User::whereHas('role', function($q) {
        $q->where('name', 'Medecin');
    })->first();

    if (!$medecin) {
        echo "❌ Aucun médecin chef trouvé\n";
        exit;
    }

    echo "👨‍⚕️ Médecin chef trouvé: {$medecin->username} (ID: {$medecin->id})\n";

    // Trouver une convocation avec tous les diagnostics complétés
    $convocation = Convoncu::whereNotNull('psy')
        ->whereNotNull('medGen')
        ->whereNotNull('chirDent')
        ->first();

    if (!$convocation) {
        echo "❌ Aucune convocation complète trouvée\n";
        exit;
    }

    $student = Student::where('matricule', $convocation->matricule)->first();

    echo "📋 Convocation trouvée pour: {$student->nom} {$student->prenom} ({$convocation->matricule})\n";
    echo "   - Psy: '{$convocation->psy}'\n";
    echo "   - MedGen: '{$convocation->medGen}'\n";
    echo "   - ChirDent: '{$convocation->chirDent}'\n";
    echo "   - AvisSpe: '{$convocation->avisSpe}'\n\n";

    echo "🔗 URL à tester:\n";
    echo "   http://127.0.0.1:8000/fiche/{$convocation->matricule}\n\n";

    echo "📝 Instructions:\n";
    echo "   1. Connectez-vous avec: {$medecin->username}\n";
    echo "   2. Allez sur l'URL ci-dessus\n";
    echo "   3. Essayez de soumettre un avis spécialisé\n";
    echo "   4. Observez la sortie du dd() dans le navigateur\n\n";

    echo "✅ Tous les diagnostics sont complétés, donc ça devrait marcher !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
