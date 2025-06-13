<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Student;
use App\Models\ListeRdv;
use Illuminate\Http\Request;

class ListeRdvController extends Controller
{
    public function index(Request $request)
    {
        $query = ListeRdv::with('student');

        // Filtre par motif si spécifié
        if ($request->has('motif') && $request->motif != '') {
            $query->where('motif', $request->motif);
        }

        // Filtre par date
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } else {
            // Par défaut, affiche les rendez-vous d'aujourd'hui
            $query->whereDate('date', Carbon::today());
        }

        $rendezvous = $query->orderBy('date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('infermerie.liste_rendezvous', [
            'rendezvous' => $rendezvous
        ]);
    }

    public function create()
    {
        $services = [
            'cardiologie' => 'Cardiologie',
            'pneumologie' => 'Pneumologie',
            'gastroenterologie' => 'Gastro-entérologie',
            'chirurgie_generale' => 'Chirurgie Générale',
            'orthopedie' => 'Orthopédie',
            'neurologie' => 'Neurologie',
            'dermatologie' => 'Dermatologie',
            'gynecologie' => 'Gynécologie',
            'urologie' => 'Urologie',
            'ophtalmologie' => 'Ophtalmologie',
            'orl' => 'ORL (Oto-Rhino-Laryngologie)',
            'psychiatrie' => 'Psychiatrie',
            'radiologie' => 'Radiologie',
            'anesthesie' => 'Anesthésie',
            'reanimation' => 'Réanimation',
        ];

        return view('infermerie.createRendezvous', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => ['required', 'digits:7'],
            'motif' => ['required', 'in:consultation,urgences'],
            'service' => ['required', 'string'],
            'date' => ['required', 'date', 'after_or_equal:now']
        ]);

        $StudentExists = Student::where('matricule', $validated['matricule'])->exists();

        if (!$StudentExists) {
            return back()->withErrors([
                'matricule' => 'Aucun élève trouvé avec ce matricule.'
            ])->withInput();
        }

        $validated['date'] = Carbon::parse($validated['date'])->format('Y-m-d H:i:s');

        $exists = ListeRdv::where('matricule', $validated['matricule'])
            ->where('motif', $validated['motif'])
            ->where('service', $validated['service'])
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'duplicate' => 'Ce rendez-vous existe déjà pour cet élève.'
            ])->withInput();
        }

        // Déterminer automatiquement le type_medecin selon le rôle de l'utilisateur connecté
        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        $typeMedecin = match($userRole) {
            'Psychologue' => 'psycho',
            'Dentiste' => 'dentiste',
            'Médecin général' => 'médecin générale',
            'Medecin' => 'chef_médecin', // Médecin chef
            default => 'médecin générale' // Par défaut pour les comptes admin ou non définis
        };

        $validated['type_medecin'] = $typeMedecin;

        ListeRdv::create($validated);

        return redirect()->route('liste_rdv.create')->with('success', 'Rendez-vous créé avec succès.');
    }

    public function destroy(Request $request)
    {
        $rdv = ListeRdv::findOrFail($request->id);
        $rdv->delete();
        return redirect()->route('liste_rdv.index')->with('success', 'Rendez-vous supprimé avec succès.');
    }
}
