<?php

namespace App\Http\Controllers;

use App\Models\Exemption;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BrigadeExemptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('main');
        }
        $today = Carbon::today();

        if ($user->role->name == "Chef de compagnie") {
            // Récupérer tous les matricules des étudiants dans les sections de l'utilisateur
            $officer = $user->isOfficer();
            $studentMatricules = $officer->sections()
                ->with('students:matricule,section_id')
                ->get()
                ->pluck('students')
                ->flatten()
                ->pluck('matricule');

            // Rechercher les exemptions liées à ces étudiants
            $exemptions = Exemption::whereIn('matricule', $studentMatricules)
                ->whereDate('date_debut', '<=', $today)
                ->whereDate('date_fin', '>=', $today)
                ->get();
        }
        if ($user->role->name == "Chef de brigade") {
            $students = Student::where('matricule', $officer->id)->whereHas('exemption');
        }
    }
}
