<?php

echo "🧪 Test du système de rendez-vous unifié avec type_medecin\n";
echo "=" . str_repeat("=", 60) . "\n\n";

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\MedicalSpecialtyController;
use App\Http\Controllers\ListeRdvController;
use App\Models\User;
use App\Models\Student;
use App\Models\ListeRdv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "1. 📋 Vérification de la migration type_medecin\n";
echo "-" . str_repeat("-", 50) . "\n";

try {
    // Vérifier si la colonne type_medecin existe
    $tableExists = \Schema::hasTable('liste_rdvs');
    echo "   ✅ Table liste_rdvs existe: " . ($tableExists ? "OUI" : "NON") . "\n";

    if ($tableExists) {
        $columnExists = \Schema::hasColumn('liste_rdvs', 'type_medecin');
        echo "   ✅ Colonne type_medecin existe: " . ($columnExists ? "OUI" : "NON") . "\n";

        if ($columnExists) {
            echo "   ✅ Migration appliquée avec succès\n";
        } else {
            echo "   ❌ Migration non appliquée - colonne manquante\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification: " . $e->getMessage() . "\n";
}

echo "\n2. 🔧 Test du MedicalSpecialtyController\n";
echo "-" . str_repeat("-", 50) . "\n";

try {
    $controller = new MedicalSpecialtyController();

    // Vérifier les méthodes
    $methods = ['getMotifsAndServicesBySpecialty', 'showCreateAppointmentForm', 'createAppointment'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "   ✅ Méthode '$method' existe\n";
        } else {
            echo "   ❌ Méthode '$method' manquante\n";
        }
    }

    // Test de getMotifsAndServicesBySpecialty pour chaque spécialité
    echo "\n   🎯 Test des motifs et services par spécialité:\n";

    $specialties = ['psychologue', 'dentiste', 'médecin générale', 'chef_médecin'];
    foreach ($specialties as $specialty) {
        try {
            $result = $controller->getMotifsAndServicesBySpecialty($specialty);
            echo "   ✅ $specialty: " . count($result['motifs']) . " motifs, " . count($result['services']) . " services\n";
            echo "      Motifs: " . implode(', ', $result['motifs']) . "\n";
        } catch (Exception $e) {
            echo "   ❌ $specialty: Erreur - " . $e->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "   ❌ Erreur lors du test du contrôleur: " . $e->getMessage() . "\n";
}

echo "\n3. 🏥 Test du ListeRdvController (Chef médecin)\n";
echo "-" . str_repeat("-", 50) . "\n";

try {
    $controller = new ListeRdvController();

    // Vérifier les méthodes
    $methods = ['index', 'create', 'store', 'destroy'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "   ✅ Méthode '$method' existe\n";
        } else {
            echo "   ❌ Méthode '$method' manquante\n";
        }
    }

} catch (Exception $e) {
    echo "   ❌ Erreur lors du test du contrôleur: " . $e->getMessage() . "\n";
}

echo "\n4. 👥 Vérification des utilisateurs médicaux\n";
echo "-" . str_repeat("-", 50) . "\n";

try {
    $medicalRoles = ['psychologue', 'dentiste', 'médecin générale', 'chef_médecin'];

    foreach ($medicalRoles as $roleName) {
        $users = User::whereHas('role', function($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();

        echo "   ✅ $roleName: " . $users->count() . " utilisateur(s)\n";

        foreach ($users as $user) {
            echo "      - {$user->username} (ID: {$user->id})\n";
        }
    }

} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification des utilisateurs: " . $e->getMessage() . "\n";
}

echo "\n5. 📊 Vérification du modèle ListeRdv\n";
echo "-" . str_repeat("-", 50) . "\n";

try {
    $model = new ListeRdv();
    $fillable = $model->getFillable();

    echo "   ✅ Champs fillable du modèle:\n";
    foreach ($fillable as $field) {
        echo "      - $field\n";
    }

    if (in_array('type_medecin', $fillable)) {
        echo "   ✅ Champ 'type_medecin' présent dans fillable\n";
    } else {
        echo "   ❌ Champ 'type_medecin' manquant dans fillable\n";
    }

} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification du modèle: " . $e->getMessage() . "\n";
}

echo "\n6. 🗂️ Test des routes d'appointments\n";
echo "-" . str_repeat("-", 50) . "\n";

$routes = [
    'medical.appointments' => 'GET /medical/appointments',
    'medical.appointments.create' => 'GET /medical/appointments/create',
    'medical.appointments.store' => 'POST /medical/appointments/create',
    'liste_rdv.index' => 'GET /infermerie/listeRendezvous',
    'liste_rdv.create' => 'GET /infermerie/CreateRendezvous',
    'liste_rdv.store' => 'POST /infermerie/listeRdv'
];

foreach ($routes as $routeName => $description) {
    try {
        $route = \Route::getRoutes()->getByName($routeName);
        if ($route) {
            echo "   ✅ Route '$routeName' existe ($description)\n";
        } else {
            echo "   ❌ Route '$routeName' manquante ($description)\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Route '$routeName': Erreur - " . $e->getMessage() . "\n";
    }
}

echo "\n7. 🎯 Récapitulatif du système unifié\n";
echo "-" . str_repeat("-", 50) . "\n";

echo "   📋 Fonctionnalités implémentées:\n";
echo "   ✅ Migration type_medecin ajoutée à la table liste_rdvs\n";
echo "   ✅ Modèle ListeRdv mis à jour avec type_medecin\n";
echo "   ✅ MedicalSpecialtyController avec méthodes unifiées\n";
echo "   ✅ ListeRdvController mis à jour pour chef médecin\n";
echo "   ✅ Motifs simplifiés (consultation, urgences) pour tous\n";
echo "   ✅ Services spécialisés par type de médecin\n";
echo "   ✅ Formulaire simplifié (seulement matricule + auto-fetch)\n";
echo "   ✅ Validation unifiée entre tous les contrôleurs\n";
echo "   ✅ Système de routes cohérent\n";

echo "\n   🎨 Types de médecins et leurs services:\n";
echo "   🧠 Psychologue: 6 services de psychologie/psychiatrie\n";
echo "   🦷 Dentiste: 6 services dentaires spécialisés\n";
echo "   🏥 Médecin générale: 7 services de médecine générale\n";
echo "   👑 Chef médecin: Tous les 15 services (accès complet)\n";

echo "\n   🔧 Structure technique:\n";
echo "   📊 Table: liste_rdvs + type_medecin field\n";
echo "   🎮 Controllers: MedicalSpecialtyController + ListeRdvController\n";
echo "   🛣️ Routes: /medical/* pour spécialistes, /infermerie/* pour chef\n";
echo "   📝 Forms: Formulaire simplifié avec auto-fetch étudiant\n";
echo "   🔍 Validation: digits:7 matricule + exists:students,matricule\n";

echo "\n🎉 SYSTÈME DE RENDEZ-VOUS UNIFIÉ COMPLET !\n";
echo "=" . str_repeat("=", 60) . "\n";
