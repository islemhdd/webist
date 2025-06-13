<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Exemption;
use App\Models\Student;
use Illuminate\Http\Request;

class BrigadeExemptionController extends Controller
{
    /**
     * Display the list of active exemptions
     */
    public function index(Officer $id)
    {
        $officer = $id;

        // Get students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        // Get active exemptions (where current date is between date_debut and date_fin)
        $today = now()->toDateString();

        $exemptions = Exemption::whereIn('matricule', $studentMatricules)
            ->where('date_debut', '<=', $today)
            ->where('date_fin', '>=', $today)
            ->with(['student.section'])
            ->orderBy('date_debut', 'desc')
            ->get();

        return view('brigade.exemption-list', compact('officer', 'exemptions'));
    }

    /**
     * Search exemptions based on query
     */
    public function search(Request $request, Officer $id)
    {
        $officer = $id;
        $query = $request->input('search', '');

        // Get students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        // Get active exemptions
        $today = now()->toDateString();

        $exemptionsQuery = Exemption::whereIn('matricule', $studentMatricules)
            ->where('date_debut', '<=', $today)
            ->where('date_fin', '>=', $today)
            ->with(['student.section']);

        if (!empty($query)) {
            $exemptionsQuery->where(function ($q) use ($query) {
                $q->where('matricule', 'like', "%{$query}%")
                    ->orWhere('motif', 'like', "%{$query}%")
                    ->orWhereHas('student', function ($studentQuery) use ($query) {
                        $studentQuery->where('nom', 'like', "%{$query}%")
                            ->orWhere('prenom', 'like', "%{$query}%");
                    });
            });
        }

        $exemptions = $exemptionsQuery->orderBy('date_debut', 'desc')->get();

        return response()->json([
            'exemptions' => $exemptions->map(function ($exemption) {
                return [
                    'id' => $exemption->id,
                    'matricule' => $exemption->matricule,
                    'student_name' => $exemption->student->nom . ' ' . $exemption->student->prenom,
                    'section' => $exemption->student->section->name ?? 'N/A',
                    'motif' => $exemption->motif,
                    'date_debut' => $exemption->date_debut,
                    'date_fin' => $exemption->date_fin,
                    'formatted_debut' => date('d/m/Y', strtotime($exemption->date_debut)),
                    'formatted_fin' => date('d/m/Y', strtotime($exemption->date_fin)),
                    'duration_days' => now()->parse($exemption->date_debut)->diffInDays(now()->parse($exemption->date_fin)) + 1,
                    'days_remaining' => now()->diffInDays(now()->parse($exemption->date_fin)),
                    'created_at' => $exemption->created_at->format('d/m/Y')
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
            return Student::where('section_id', $officer->section_id);
        } elseif ($officer->role->name === 'Chef de bataillant') {
            // Chef de bataillant sees all students in their battalion
            $sectionIds = $officer->battalion->sections->pluck('id');
            return Student::whereIn('section_id', $sectionIds);
        } else {
            // Other roles see all students
            return Student::query();
        }
    }
}
