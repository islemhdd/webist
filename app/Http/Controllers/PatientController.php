<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Patient;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        // Filter by validation status if specified
        if ($request->filled('validation')) {
            switch ($request->validation) {
                case '1': // Validé
                    $query->where('valider', true);
                    break;
                case '0': // Non validé
                    $query->where('valider', false)
                        ->orWhereNull('valider');
                    break;
                default: // Tout
                    break;
            }
        }

        // Join with Students table to get student information
        $patients = $query->join('Students', 'patients.matricule', '=', 'Students.matricule')
            ->select('patients.*', 'Students.nom', 'Students.prenom', 'Students.section_id')
            ->orderBy('patients.created_at', 'desc')
            ->get();

        return view('infermerie.liste_patient', compact('patients'));
    }

    public function valider($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->valider = true;
        $patient->validated_at = Carbon::now();
        $patient->save();

        return redirect()->back()->with('success', 'Patient validé avec succès.');
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
                    $query->where('valider', true);
                    break;
                case '0': // Non validé
                    $query->where('valider', false)
                        ->orWhereNull('valider');
                    break;
                default: // Tout
                    break;
            }
        }
        $patients = $query->get();










        // $patients = Patient::where('matricule', $officer->matricule)->get();
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
    public function delete(Request $request)
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
