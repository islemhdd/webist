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

        if ($request->filled('type')) {
            $query->where('sanctions.type', $request->type);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            if ($isActive) {
                $query->where('sanctions.date_fin', '>=', Carbon::today());
            } else {
                $query->where('sanctions.date_fin', '<', Carbon::today());
            }
        }

        $sanctions = $query->get()
            ->map(function ($sanction) {
                return [
                    'id' => $sanction->id,
                    'matricule' => $sanction->matricule,
                    'full_name' => $sanction->nom . ' ' . $sanction->prenom,
                    'type' => $sanction->type,
                    'motif' => $sanction->motif,
                    'date_debut' => $sanction->date_debut,
                    'date_fin' => $sanction->date_fin,
                    'is_active' => Carbon::parse($sanction->date_fin)->gte(Carbon::today())
                ];
            });

        if ($request->wantsJson()) {
            return response()->json($sanctions);
        }

        return view('brigade.sanctions', compact('sanctions', 'id'));
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
        $validated = $request->validate([
            'matricule' => 'required|exists:students,matricule',
            'type' => 'required|in:consigne,arret,blame,avert',
            'motif' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);

        $sanction = new Sanction($validated);
        $sanction->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sanction créée avec succès',
                'sanction' => $sanction
            ]);
        }

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
