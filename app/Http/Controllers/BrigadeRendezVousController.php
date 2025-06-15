<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\ListeRdv;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class BrigadeRendezVousController extends Controller
{
    /**
     * Display the list of tomorrow's rendez-vous
     */
    public function index(Officer $id)
    {

        $officer = $id;

        // Get students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);

        $studentMatricules = $studentsQuery->pluck('matricule');


        // Get tomorrow's rendez-vous
        $tomorrow = now()->addDay()->startOfDay()->format('Y-m-d');



        $rdvs = ListeRdv::whereIn('matricule', $studentMatricules)
            ->where('date', $tomorrow)
            ->orderBy('date', 'asc')
            ->get();


        // Check if this is an AJAX request
        if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'rdvs' => $rdvs->map(function ($rdv) {
                    return [
                        'id' => $rdv->id,
                        'matricule' => $rdv->matricule,
                        'student_name' => $rdv->student->nom . ' ' . $rdv->student->prenom,
                        'section' => $rdv->student->section_id ?? 'N/A',
                        'motif' => $rdv->motif,
                        'service' => $rdv->service,
                        // 'date' => $rdv->date->format('Y-m-d H:i'),
                        // 'formatted_date' => $rdv->date->format('d/m/Y H:i'),
                        'created_at' => $rdv->created_at->format('d/m/Y')
                    ];
                })
            ]);
        }

        return view('brigade.rdv-list', ['officer' => $officer, 'rdvs' => collect()]);
    }

    /**
     * Search rendez-vous based on query
     */
    public function search(Request $request, Officer $id)
    {
        $officer = $id;
        $query = $request->input('search', '');

        // Get students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);

        $studentMatricules = $studentsQuery->pluck('matricule');

        // Get tomorrow's rendez-vous

        $rdvsQuery = ListeRdv::whereIn('matricule', $studentMatricules)
            ->with(['student.section']);

        $rd = $rdvsQuery->get();



        if (!empty($query)) {
            $rdvsQuery->where(function ($q) use ($query) {
                $q->where('matricule', 'like', "%{$query}%")
                    ->orWhere('motif', 'like', "%{$query}%")
                    ->orWhere('service', 'like', "%{$query}%")
                    ->orWhereHas('student', function ($studentQuery) use ($query) {
                        $studentQuery->where('nom', 'like', "%{$query}%")
                            ->orWhere('prenom', 'like', "%{$query}%");
                    });
            });
        }

        $rdvs = $rdvsQuery->orderBy('date', 'asc')->get();

        return response()->json([
            'rdvs' => $rdvs->map(function ($rdv) {
                return [

                    'id' => $rdv->id,
                    'matricule' => $rdv->matricule,
                    'student_name' => $rdv->student->nom . ' ' . $rdv->student->prenom,
                    'section' => $rdv->student->section_id ?? 'N/A',
                    'motif' => $rdv->motif,
                    'service' => $rdv->service,


                ];
            })
        ]);
    }

    /**
     * Get students query based on officer role
     */
    private function getStudentsQueryByRole(Officer $officer)
    {
        if ($officer->role->name === 'Chef de compagnie') {

            // Chef de compagnie sees only their section students
            $officerSections = Section::where("officer_id", $officer->id)->get()->pluck('id');

            return Student::wherein('section_id', $officerSections);
        } elseif ($officer->role->name === 'Chef de bataillant') {
            // Chef de bataillant sees all students in their battalion

            return Student::where('bat', $officer->bat);
        } else {
            // Other roles see all students
            return Student::query();
        }
    }
}
