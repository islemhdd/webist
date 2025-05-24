<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Convoncu;
use Illuminate\Http\Request;

class FicheController extends Controller
{
    /**
     * Display the specified student's file.
     */
    public function show($matricule)
    {
        $Student = Student::where('matricule', $matricule)->firstOrFail();
        $convoncu = Convoncu::where('matricule', $matricule)->first();

        return view('infermerie.fiche', compact('Student', 'convoncu'));
    }

    /**
     * Update the specified student's file.
     */
    public function update(Request $request, $matricule)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'section_id' => 'required|string|max:255',
            // Add other fields as needed
        ]);

        $Student = Student::where('matricule', $matricule)
            ->firstOrFail();

        $Student->update($validated);

        return redirect()->back()->with('success', 'Fiche mise à jour avec succès');
    }
}
