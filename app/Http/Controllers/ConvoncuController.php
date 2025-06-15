<?php

namespace App\Http\Controllers;

use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConvoncuController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        // Démarrer la requête de base
        $query = Convoncu::query();

        // Filtrer selon le rôle médical pour afficher seulement les étudiants concernés
        switch ($userRole) {
            case 'Psychologue':
                // Afficher seulement les étudiants avec champ psy NULL
                $query->whereNull('psy');
                break;

            case 'Dentiste':
                // Afficher seulement les étudiants avec champ chirDent NULL
                $query->whereNull('chirDent');
                break;

            case 'Medecin general':
            case 'Médecin général':
            case 'Medecin generale':
                // Afficher seulement les étudiants avec champ medGen NULL
                $query->whereNull('medGen');
                break;

            case 'Medecin':
            case 'Medecin chef':
                // Médecin chef : afficher les étudiants qui ont AU MOINS UN champ NULL
                $query->where(function($q) {
                    $q->whereNull('psy')
                      ->orWhereNull('medGen')
                      ->orWhereNull('chirDent')->orWhereNull('avisSpe');
                });
                break;

            default:
                // Pour les autres rôles, filtrer comme psychologue par défaut
                $query->whereNull('psy');
                break;
        }

        // Ajouter recherche si demandée
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('matricule', 'like', "%{$searchTerm}%")
                  ->orWhereHas('student', function($sq) use ($searchTerm) {
                      $sq->where('nom', 'like', "%{$searchTerm}%")
                        ->orWhere('prenom', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Join avec la table Students pour obtenir les informations de l'étudiant
        $convoncus = $query->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
            ->select('convoncus.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
            ->orderBy('convoncus.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
            // dd($convoncus);

        return view('infermerie.liste_convoncu', compact('convoncus', 'userRole'));
    }

    public function update(Request $request, $matricule)
    {
        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        // Validation des champs selon le rôle de l'utilisateur
        $validationRules = [];

        switch ($userRole) {
            case 'Psychologue':
                $validationRules = [
                    'psy' => 'required|string|min:3',
                ];
                break;

            case 'Dentiste':
                $validationRules = [
                    'chirDent' => 'required|string|min:3',
                ];
                break;

            case 'Medecin general':
            case 'Médecin général':
            case 'Medecin generale':
                $validationRules = [
                    'medGen' => 'required|string|min:3',
                ];
                break;

            case 'Medecin':
            case 'Medecin chef':
                $validationRules = [
                    'avisSpe' => 'required|string|min:3',
                ];
                break;

            default:
                return redirect()->route('liste_convoncu')->with('error', 'Accès non autorisé');
        }

        $validated = $request->validate($validationRules);

        $convoncu = Convoncu::where('matricule', $matricule)->firstOrFail();
        $convoncu->update($validated);

        return redirect()->route('liste_convoncu')->with('success', 'Diagnostic enregistré avec succès');
    }
}
