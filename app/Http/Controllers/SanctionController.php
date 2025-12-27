<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Sanction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Officer $id, Request $request)
    {

        $query = $id->officerSanctions();



        if ($request->has('type') && $request->type != '') {

            // If type filter is specified, show all sanctions of that type
            $query->where('sanctions.type', $request->type);
        }
        // Default view: consignes for this weekend and active/upcoming arrets
        // dd($sanctions)
        $sanctions = $query->orderBy('date_debut', 'asc')->paginate(15);


        return view('brigade.sanctions', [
            'sanctions' => $sanctions,
            'id' => $id->id,
            'selectedType' => $request->type
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Officer $id)
    {
        return view('brigade.sanction_create', [
            'officer' => $id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Officer $id, Request $request)
    {
        // Règles de validation de base

        $rules = [
            'matricule' => 'required|exists:students,matricule',
            'type' => 'required|in:consigne,arret,blame,avert',
            'motif' => 'required|string'
        ];

        // Ajouter les règles de validation des dates uniquement pour les arrêts
        if ($request->type === 'arret') {
            $rules['from'] = 'required|date';
            $rules['to'] = 'required|date|after_or_equal:from';
        }

        $validated = $request->validate($rules);

        // Préparer les données de la sanction
        $sanctionData = [
            'matricule' => $validated['matricule'],
            'type' => $validated['type'],
            'motif' => $validated['motif'],
        ];

        // Ajouter les dates si c'est un arrêt
        if ($validated['type'] === 'arret') {
            $sanctionData['date_debut'] = $validated['from'];
            $sanctionData['date_fin'] = $validated['to'];
        } elseif ($validated['type'] === 'consigne') {
            // Pour consigne, date_debut = prochain vendredi, date_fin = samedi suivant
            $now = now();
            // Prochain vendredi
            $friday = $now->copy()->next(Carbon::FRIDAY);
            // Samedi qui suit
            $saturday = $friday->copy()->addDay();
            $sanctionData['date_debut'] = $friday;
            $sanctionData['date_fin'] = $saturday;
        } else {
            // Pour les autres types, date_debut = aujourd'hui, date_fin = aujourd'hui
            $sanctionData['date_debut'] = now();
            $sanctionData['date_fin'] = now();
        }

        $sanction = new Sanction($sanctionData);
        $sanction->save();

        return redirect()->route('sanctions.index', $id)
            ->with('success', 'Sanction créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Officer $id, Sanction $sanction, Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|exists:students,matricule',
            'type' => 'required|in:consigne,arret,blame,avert',
            'motif' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);

        $sanction->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sanction mise à jour avec succès',
                'sanction' => $sanction
            ]);
        }

        return redirect()->route('sanctions.index', $id)
            ->with('success', 'Sanction mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Officer $id, Sanction $sanction)
    {
        $sanction->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Sanction supprimée avec succès'
            ]);
        }

        return redirect()->route('sanctions.index', $id)
            ->with('success', 'Sanction supprimée avec succès');
    }
}
