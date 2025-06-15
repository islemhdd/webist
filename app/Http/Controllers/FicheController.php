<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Convoncu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FicheController extends Controller
{
    /**
     * Display the specified student's file.
     */
    public function show($matricule)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        $Student = Student::where('matricule', $matricule)->firstOrFail();
        $convoncu = Convoncu::where('matricule', $matricule)->first();

        // Déterminer quels champs peuvent être modifiés selon le rôle
        $editableFields = $this->getEditableFields($userRole);
        $isReadOnly = in_array($userRole, ['Medecin', 'Medecin chef']); // Médecin chef peut voir mais pas modifier

        return view('infermerie.fiche', compact('Student', 'convoncu', 'userRole', 'editableFields', 'isReadOnly'));
    }

    /**
     * Déterminer les champs modifiables selon le rôle médical
     */
    private function getEditableFields($userRole)
    {
        switch ($userRole) {
            case 'Psychologue':
                return ['psy'];

            case 'Dentiste':
                return ['chirDent'];

            case 'Medecin general':
            case 'Médecin général':
            case 'Medecin generale':
                return ['medGen'];

            case 'Medecin':
            case 'Medecin chef':
                // Médecin chef peut voir tous les champs mais les modifier seulement pour avisSpe
                return ['avisSpe'];

            default:
                return [];
        }
    }

    /**
     * Update the specified student's file.
     */
    public function update(Request $request, $matricule)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        $editableFields = $this->getEditableFields($userRole);

        if (empty($editableFields)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier cette fiche.');
        }

        // Validation spéciale pour le médecin chef
        if (in_array($userRole, ['Medecin', 'Medecin chef'])) {
            // Le médecin chef doit avoir tous les autres diagnostics avant de pouvoir enregistrer le sien
            $convoncu = Convoncu::where('matricule', $matricule)->first();

            if (!$convoncu) {
                return redirect()->back()->with('error',
                    "Aucune convocation trouvée pour cet étudiant. Les médecins spécialisés doivent d'abord saisir leurs diagnostics."
                );
            }

            // Validation stricte avec nettoyage des espaces
            $psyValue = trim($convoncu->psy ?? '');
            $medGenValue = trim($convoncu->medGen ?? '');
            $chirDentValue = trim($convoncu->chirDent ?? '');

            $psyMissing = $psyValue === '' || strlen($psyValue) < 3;
            $medGenMissing = $medGenValue === '' || strlen($medGenValue) < 3;
            $chirDentMissing = $chirDentValue === '' || strlen($chirDentValue) < 3;

            if ($psyMissing || $medGenMissing || $chirDentMissing) {
                $missing = [];
                if ($psyMissing) $missing[] = "Psychologue (actuel: '{$psyValue}')";
                if ($medGenMissing) $missing[] = "Médecin Général (actuel: '{$medGenValue}')";
                if ($chirDentMissing) $missing[] = "Dentiste (actuel: '{$chirDentValue}')";

                $missingText = implode(', ', $missing);
                return redirect()->back()->with('error',
                    "Impossible d'enregistrer votre avis spécialisé. Les diagnostics suivants sont insuffisants : {$missingText}.
                     Chaque diagnostic doit contenir au moins 3 caractères significatifs."
                );
            }
        }

        // DEBUG: Voir exactement ce qui est envoyé
        \Log::info('FICHE DEBUG - Données reçues:', [
            'user_role' => $userRole,
            'editable_fields' => $editableFields,
            'request_data' => $request->all(),
            'matricule' => $matricule
        ]);

        // Validation basée sur les champs modifiables UNIQUEMENT
        $rules = [];
        foreach ($editableFields as $field) {
            $rules[$field] = 'required|string|min:10'; // Au minimum 10 caractères pour un diagnostic
        }

        // Valider SEULEMENT les champs modifiables pour ce rôle
        $dataToValidate = $request->only($editableFields);

        // Vérifier que nous avons bien les données attendues
        foreach ($editableFields as $field) {
            if (!isset($dataToValidate[$field]) || trim($dataToValidate[$field]) === '') {
                return redirect()->back()->with('error', "Le champ {$field} est requis mais n'a pas été fourni.");
            }
        }

        $validated = \Validator::make($dataToValidate, $rules, [
            'required' => 'Le diagnostic est obligatoire.',
            'min' => 'Le diagnostic doit contenir au minimum 10 caractères.'
        ])->validate();

        $Student = Student::where('matricule', $matricule)->firstOrFail();

        // Mettre à jour ou créer la convocation
        $convoncu = Convoncu::updateOrCreate(
            ['matricule' => $matricule],
            $validated
        );

        // Message de succès personnalisé selon le rôle
        $successMessage = match($userRole) {
            'Psychologue' => 'Diagnostic psychologique enregistré avec succès',
            'Dentiste' => 'Diagnostic dentaire enregistré avec succès',
            'Medecin general' => 'Diagnostic médical enregistré avec succès',
            'Medecin' => 'Avis spécialisé du médecin chef enregistré avec succès',
            default => 'Diagnostic enregistré avec succès'
        };

        return redirect()->route('liste_convoncu')->with('success', $successMessage);
    }
}
