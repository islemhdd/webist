<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\User;
use App\Models\Officer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== CRÉATION DES COMPTES MÉDICAUX SPÉCIALISÉS ===\n\n";

try {
    DB::beginTransaction();

    // 1. Créer les nouveaux rôles médicaux spécialisés
    $medicalRoles = [
        'Psychologue' => 'Spécialiste en psychologie',
        'Dentiste' => 'Spécialiste en médecine dentaire',
        'Médecin général' => 'Médecin généraliste'
    ];

    echo "📋 Création des rôles médicaux...\n";
    foreach ($medicalRoles as $roleName => $description) {
        $existingRole = Role::where('name', $roleName)->first();
        if (!$existingRole) {
            $role = Role::create([
                'name' => $roleName
            ]);
            echo "✅ Rôle '$roleName' créé avec succès (ID: {$role->id})\n";
        } else {
            echo "ℹ️  Rôle '$roleName' existe déjà (ID: {$existingRole->id})\n";
        }
    }

    echo "\n👥 Création des comptes utilisateurs...\n";

    // 2. Créer les comptes utilisateurs pour chaque spécialité
    $medicalAccounts = [
        [
            'name' => 'Dr. Sarah BENAISSA',
            'email' => 'psychologue@enpei.edu.dz',
            'role' => 'Psychologue',
            'specialty' => 'psy',
            'department' => 'Service de Psychologie'
        ],
        [
            'name' => 'Dr. Ahmed KHELIFI',
            'email' => 'dentiste@enpei.edu.dz',
            'role' => 'Dentiste',
            'specialty' => 'dentaire',
            'department' => 'Service Dentaire'
        ],
        [
            'name' => 'Dr. Fatima BOUMEDIENE',
            'email' => 'medecin.general@enpei.edu.dz',
            'role' => 'Médecin général',
            'specialty' => 'general',
            'department' => 'Médecine Générale'
        ]
    ];

    foreach ($medicalAccounts as $account) {
        // Vérifier si l'utilisateur existe déjà
        $existingUser = User::where('email', $account['email'])->first();

        if (!$existingUser) {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $account['name'],
                'email' => $account['email'],
                'password' => Hash::make('enpei2025'), // Mot de passe par défaut
                'email_verified_at' => now()
            ]);

            // Récupérer le rôle
            $role = Role::where('name', $account['role'])->first();

            if ($role) {
                // Créer l'officer
                $officer = Officer::create([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                    'department' => $account['department'],
                    'specialty' => $account['specialty'],
                    'phone' => '+213-' . rand(500000000, 799999999),
                    'office_location' => 'Bloc Médical - ' . $account['department'],
                    'is_active' => true
                ]);

                echo "✅ Compte {$account['role']} créé :\n";
                echo "   👤 Utilisateur: {$account['name']} ({$account['email']})\n";
                echo "   🔑 Mot de passe: enpei2025\n";
                echo "   🏥 Département: {$account['department']}\n";
                echo "   🔬 Spécialité: {$account['specialty']}\n\n";
            } else {
                echo "❌ Rôle '{$account['role']}' non trouvé pour {$account['name']}\n";
            }
        } else {
            echo "ℹ️  Utilisateur {$account['email']} existe déjà\n";
        }
    }

    echo "\n📊 Mise à jour de la table patients pour les types médicaux...\n";

    // 3. Vérifier si la colonne type_medecin existe dans la table patients
    if (!\Illuminate\Support\Facades\Schema::hasColumn('patients', 'type_medecin')) {
        echo "⚠️  La colonne 'type_medecin' n'existe pas dans la table patients.\n";
        echo "💡 Une migration sera nécessaire pour ajouter cette colonne.\n";
    } else {
        echo "✅ La colonne 'type_medecin' existe déjà dans la table patients.\n";
    }

    // 4. Vérifier la structure de la table appointments pour les rendez-vous
    if (!\Illuminate\Support\Facades\Schema::hasTable('appointments')) {
        echo "⚠️  La table 'appointments' n'existe pas.\n";
        echo "💡 Une migration sera nécessaire pour créer cette table.\n";
    } else {
        echo "✅ La table 'appointments' existe déjà.\n";
    }

    DB::commit();

    echo "\n🎉 CRÉATION DES COMPTES MÉDICAUX TERMINÉE !\n";
    echo "=" . str_repeat("=", 50) . "\n";

    echo "\n📋 RÉSUMÉ DES COMPTES CRÉÉS :\n";
    echo "1. 🧠 Psychologue (psychologue@enpei.edu.dz)\n";
    echo "2. 🦷 Dentiste (dentiste@enpei.edu.dz)\n";
    echo "3. 🩺 Médecin général (medecin.general@enpei.edu.dz)\n";

    echo "\n🔐 Mot de passe par défaut pour tous : enpei2025\n";

    echo "\n⚡ PROCHAINES ÉTAPES :\n";
    echo "1. Créer une migration pour ajouter 'type_medecin' à la table patients\n";
    echo "2. Créer une migration pour la table appointments si nécessaire\n";
    echo "3. Modifier les contrôleurs pour filtrer par spécialité\n";
    echo "4. Adapter la sidebar pour chaque type de médecin\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "❌ Erreur lors de la création : " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "📁 Fichier: " . $e->getFile() . "\n";
}
