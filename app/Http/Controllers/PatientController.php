<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Patient;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        // Filter by validation status if specified
        if ($request->filled('validation')) {
            switch ($request->validation) {
                case '1': // Validé
                    $query->where('valider', 1);
                    break;
                case '0': // Non validé
                    $query->where('valider', 0);
                    break;
                case '2': // Supprimé
                    $query->where('valider', 2);
                    break;
                default: // Tout
                    break;
            }
        }

        // Déterminer le nombre d'éléments par page
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [15, 25, 50, 100]) ? $perPage : 15;

        // Join with Students table to get student information
        $patients = $query->join('Students', 'patients.matricule', '=', 'Students.matricule')
            ->select('patients.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
            ->orderBy('patients.created_at', 'desc')
            ->paginate($perPage) // Utiliser le nombre d'éléments par page sélectionné
            ->appends(request()->query()); // Préserver les paramètres de requête

        return view('infermerie.liste_patient', compact('patients'));
    }

    public function showValidationForm($id)
    {
        $patient = Patient::join('Students', 'patients.matricule', '=', 'Students.matricule')
            ->select('patients.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
            ->where('patients.id', $id)
            ->firstOrFail();

        return view('infermerie.validation_form', compact('patient'));
    }

    public function valider($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $patient->valider = 1;
            $patient->validated_at = Carbon::now();
            $patient->save();

            // Retourner JSON pour les requêtes AJAX
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Patient validé avec succès.']);
            }

            return redirect()->back()->with('success', 'Patient validé avec succès.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la validation: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
        }
    }

    public function validateWithDiagnosis(Request $request, $id)
    {
        try {
            $request->validate([
                'avis_medecin' => 'required|string|max:2000',
                'type_medecin' => 'nullable|in:médecin générale,dentiste,psycho',
            ]);

            $patient = Patient::findOrFail($id);

            // Valider le patient ET enregistrer le diagnostic
            $patient->valider = 1;
            $patient->validated_at = Carbon::now();
            $patient->avis_medecin = $request->avis_medecin;

            // Ajouter le type médecin s'il est fourni
            if ($request->type_medecin) {
                $patient->type_medecin = $request->type_medecin;
            }

            $patient->save();

            return redirect()->back()->with('success', 'Patient validé avec diagnostic médical avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
        }
    }

    public function softDelete(Request $request, $id)
    {
        $request->validate([
            'motif_suppression' => 'required|string|max:1000',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->valider = 2; // Supprimé
        $patient->motif_suppression = $request->motif_suppression;
        $patient->save();

        return redirect()->back()->with('success', 'Patient supprimé avec succès.');
    }

    public function updateMedicalInfo(Request $request, $id)
    {
        $request->validate([
            'type_medecin' => 'nullable|in:médecin générale,dentiste,psycho',
            'avis_medecin' => 'nullable|string|max:2000',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->type_medecin = $request->type_medecin;
        $patient->avis_medecin = $request->avis_medecin;
        $patient->save();

        // Retourner JSON pour les requêtes AJAX
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Informations médicales mises à jour avec succès.']);
        }

        return redirect()->back()->with('success', 'Informations médicales mises à jour avec succès.');
    }

    public function list(Officer $id, Request $request)
    {
        $officer = $id;
        $patients = $officer->patients();
        $query = Patient::query();

        // Filter by validation status if specified
        if ($request->filled('validation')) {
            switch ($request->validation) {
                case '1': // Validé
                    $query->where('valider', 1);
                    break;
                case '0': // Non validé
                    $query->where('valider', 0);
                    break;
                case '2': // Supprimé
                    $query->where('valider', 2);
                    break;
                default: // Tout
                    break;
            }
        }

        $query->orderBy("created_at", "desc");

        $patients = $query->get();

        return view('brigade.liste_patient', compact('patients', 'officer'));
    }

    public function add(Officer $id, Request $request)
    {
        $officer = $id;
        $request->validate([
            'matricule' => ['required', 'exists:students,matricule'],
        ]);

        $pateint = new Patient(["matricule" => $request->input("matricule")]);
        $pateint->save();
        return response()->json($pateint, 201);
        return redirect()->back()->with('success', 'Patient ajouté avec succès.');
    }

    public function delete(Officer $id, Request $request)
    {

        $request->validate([
            'matricule' => ['required', 'exists:students,matricule'],
        ]);

        $pateint = Patient::where("matricule", $request->input("matricule"))->first();
        if ($pateint && !$pateint->valide) {
            $pateint->delete();
            return redirect()->back()->with('success', 'Patient supprimé avec succès.');
        } else {
            return redirect()->back()->with('error', 'Patient introuvable.');
        }
    }
}
