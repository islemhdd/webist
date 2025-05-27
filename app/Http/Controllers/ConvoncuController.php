<?php

namespace App\Http\Controllers;

use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

class ConvoncuController extends Controller
{
    public function show(Request $request)
    {
       $query = Convoncu::whereNull('psy');


        // Join with Students table to get student information
        $convoncus = $query->join('Students', 'convoncus.matricule', '=', 'Students.matricule')
            ->select('convoncus.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
            ->orderBy('convoncus.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('infermerie.liste_convoncu', compact('convoncus'));
    }

    public function update(Request $request, $matricule)
    {
        $validated = $request->validate([
            'psy' => 'required|string',
            'medGen' => 'required|string',
            'chirDent' => 'required|string',
            'avisSpe' => 'required|string',
        ]);

        $convoncu = Convoncu::where('matricule', $matricule)->firstOrFail();
        $convoncu->update($validated);

        return redirect()->route('liste_convoncu')->with('success', 'Fiche médicale mise à jour avec succès');
    }
}
