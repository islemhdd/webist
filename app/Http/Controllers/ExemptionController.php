<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Exemption;

class ExemptionController extends Controller
{
    public function create()
    {
        return view('infermerie.createExemption');
    }
    public function index(Request $request)
    {
        $aujourdHui = now()->toDateString();

        $query = Exemption::query();

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereDate('date_debut', '>=', $request->date_debut)
                ->whereDate('date_fin', '<=', $request->date_fin);
        } else if ($request->filled('date_debut')) {
            $query->whereDate('date_debut', '=', $request->date_debut);
        } else if ($request->filled('date_fin')) {
            $query->whereDate('date_fin', '=', $request->date_fin);
        } {
            $query->where('date_fin', '>', $aujourdHui);
        }

        $exemptions = $query->get();

        return view('infermerie.liste_exemption', compact('exemptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required',
            'motif' => [
                'required',
                'in:exemption effort physique,exemption rasage barbe,exemption rangers,prolongation arret,reprise travail'
            ],
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);
        $StudentExists = Student::where('matricule', $validated['matricule'])->exists();

        if (!$StudentExists) {
            return back()->withErrors([
                'matricule' => 'Aucun élève trouvé avec ce matricule.'
            ])->withInput();
        }


        Exemption::create($validated);

        return redirect()->route('exemptions.index')->with('success', 'Exemption ajoutée');
    }
}
