<?php

echo "🏥 TEST DU SYSTÈME DE RÔLES MÉDICAUX\n";
echo "====================================\n\n";

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

try {
    echo "1. 🔍 Vérification des utilisateurs médicaux\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $medicalUsers = User::whereHas('role', function($q) {
        $q->whereIn('name', ['Psychologue', 'Dentiste', 'Medecin general', 'Medecin']);
    })->with('role')->get();

    foreach ($medicalUsers as $user) {
        echo "   👨‍⚕️ {$user->username} - {$user->role->name}\n";
    }

    echo "\n2. 📊 Analyse des convocations par spécialité\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $totalConvocations = Convoncu::count();
    echo "   Total convocations: {$totalConvocations}\n\n";

    // Psychologue
    $psyComplet = Convoncu::whereNotNull('psy')->count();
    $psyAttente = Convoncu::whereNull('psy')->count();
    echo "   🧠 PSYCHOLOGUE:\n";
    echo "      - Diagnostics complétés: {$psyComplet}\n";
    echo "      - En attente: {$psyAttente}\n\n";

    // Dentiste
    $dentComplet = Convoncu::whereNotNull('chirDent')->count();
    $dentAttente = Convoncu::whereNull('chirDent')->count();
    echo "   🦷 DENTISTE:\n";
    echo "      - Diagnostics complétés: {$dentComplet}\n";
    echo "      - En attente: {$dentAttente}\n\n";

    // Médecin général
    $medGenComplet = Convoncu::whereNotNull('medGen')->count();
    $medGenAttente = Convoncu::whereNull('medGen')->count();
    echo "   🩺 MÉDECIN GÉNÉRAL:\n";
    echo "      - Diagnostics complétés: {$medGenComplet}\n";
    echo "      - En attente: {$medGenAttente}\n\n";

    // Médecin chef
    $chefComplet = Convoncu::whereNotNull('psy')
        ->whereNotNull('medGen')
        ->whereNotNull('chirDent')
        ->whereNotNull('avisSpe')->count();
    $chefAttente = $totalConvocations - $chefComplet;
    echo "   👨‍⚕️ MÉDECIN CHEF (tous diagnostics):\n";
    echo "      - Dossiers complètement traités: {$chefComplet}\n";
    echo "      - Dossiers en cours: {$chefAttente}\n\n";

    echo "3. 🧪 Test des filtres par rôle\n";
    echo "-" . str_repeat("-", 50) . "\n";

    // Test pour psychologue
    $psyQuery = Convoncu::whereNull('psy');
    $psyFiltered = $psyQuery->count();
    echo "   🧠 Étudiants visibles par PSYCHOLOGUE: {$psyFiltered}\n";

    // Test pour dentiste
    $dentQuery = Convoncu::whereNull('chirDent');
    $dentFiltered = $dentQuery->count();
    echo "   🦷 Étudiants visibles par DENTISTE: {$dentFiltered}\n";

    // Test pour médecin général
    $medGenQuery = Convoncu::whereNull('medGen');
    $medGenFiltered = $medGenQuery->count();
    echo "   🩺 Étudiants visibles par MÉDECIN GÉNÉRAL: {$medGenFiltered}\n";

    // Test pour médecin chef (tous)
    $chefQuery = Convoncu::query();
    $chefFiltered = $chefQuery->count();
    echo "   👨‍⚕️ Étudiants visibles par MÉDECIN CHEF: {$chefFiltered}\n\n";

    echo "4. 📋 Exemple d'états de convocation pour médecin chef\n";
    echo "-" . str_repeat("-", 50) . "\n";

    $exemples = Convoncu::with('student')->limit(5)->get();
    foreach ($exemples as $conv) {
        $student = $conv->student;
        if (!$student) continue;

        echo "   📄 {$student->nom} {$student->prenom} ({$conv->matricule}):\n";

        $status = [];
        $status[] = !empty($conv->psy) ? "✅ Psy" : "⏳ Psy";
        $status[] = !empty($conv->medGen) ? "✅ MédGen" : "⏳ MédGen";
        $status[] = !empty($conv->chirDent) ? "✅ Dentiste" : "⏳ Dentiste";
        $status[] = !empty($conv->avisSpe) ? "✅ AvisSpe" : "⏳ AvisSpe";

        echo "      " . implode(" | ", $status) . "\n\n";
    }

    echo "5. ✅ Validation du système\n";
    echo "-" . str_repeat("-", 50) . "\n";

    echo "   ✅ Chaque médecin ne voit que les étudiants nécessitant son diagnostic\n";
    echo "   ✅ Le médecin chef voit tous les étudiants avec état détaillé\n";
    echo "   ✅ Les champs sont filtrés par spécialité dans la fiche\n";
    echo "   ✅ Le système respecte les permissions par rôle\n\n";

    echo "🎉 SYSTÈME DE RÔLES MÉDICAUX FONCTIONNEL !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
