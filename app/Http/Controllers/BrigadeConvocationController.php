<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Http\Request;

class BrigadeConvocationController extends Controller
{
    /**
     * Display the list of today's convocations
     */
    public function index(Officer $id)
    {
        $officer = $id;

        // Get students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        // Get today's convocations (created today or have any medical requirement)
        $convocations = Convoncu::whereIn('matricule', $studentMatricules)
            ->with(['student.section'])
            ->where(function ($query) {
                $query->where('psy', true)
                    ->orWhere('medGen', true)
                    ->orWhere('chirDent', true)
                    ->orWhere('avisSpe', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if this is an AJAX request
        if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'convocations' => $convocations->map(function ($convocation) {
                    $services = [];
                    if ($convocation->psy) $services[] = 'Psychiatre';
                    if ($convocation->medGen) $services[] = 'Médecin Général';
                    if ($convocation->chirDent) $services[] = 'Chirurgien Dentiste';
                    if ($convocation->avisSpe) $services[] = 'Avis Spécialisé';

                    return [
                        'id' => $convocation->id,
                        'matricule' => $convocation->matricule,
                        'student_name' => $convocation->student->nom . ' ' . $convocation->student->prenom,
                        'section' => $convocation->student->section->name ?? 'N/A',
                        'services' => $services,
                        'services_text' => implode(', ', $services),
                        'psy' => $convocation->psy,
                        'medGen' => $convocation->medGen,
                        'chirDent' => $convocation->chirDent,
                        'avisSpe' => $convocation->avisSpe,
                        'created_at' => $convocation->created_at->format('d/m/Y'),
                        'formatted_date' => $convocation->created_at->format('d/m/Y H:i')
                    ];
                })
            ]);
        }

        return view('brigade.convocation-list', ['officer' => $officer, 'convocations' => collect()]);
    }

    /**
     * Search convocations based on query
     */
    public function search(Request $request, Officer $id)
    {
        $officer = $id;
        $query = $request->input('search', '');

        // Get students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        $convocationsQuery = Convoncu::whereIn('matricule', $studentMatricules)
            ->with(['student.section'])
            ->where(function ($q) {
                $q->where('psy', true)
                    ->orWhere('medGen', true)
                    ->orWhere('chirDent', true)
                    ->orWhere('avisSpe', true);
            });

        if (!empty($query)) {
            $convocationsQuery->where(function ($q) use ($query) {
                $q->where('matricule', 'like', "%{$query}%")
                    ->orWhereHas('student', function ($studentQuery) use ($query) {
                        $studentQuery->where('nom', 'like', "%{$query}%")
                            ->orWhere('prenom', 'like', "%{$query}%");
                    });
            });
        }

        $convocations = $convocationsQuery->orderBy('created_at', 'desc')->get();

        return response()->json([
            'convocations' => $convocations->map(function ($convocation) {
                $services = [];
                if ($convocation->psy) $services[] = 'Psychiatre';
                if ($convocation->medGen) $services[] = 'Médecin Général';
                if ($convocation->chirDent) $services[] = 'Chirurgien Dentiste';
                if ($convocation->avisSpe) $services[] = 'Avis Spécialisé';

                return [
                    'id' => $convocation->id,
                    'matricule' => $convocation->matricule,
                    'student_name' => $convocation->student->nom . ' ' . $convocation->student->prenom,
                    'section' => $convocation->student->section->name ?? 'N/A',
                    'services' => $services,
                    'services_text' => implode(', ', $services),
                    'psy' => $convocation->psy,
                    'medGen' => $convocation->medGen,
                    'chirDent' => $convocation->chirDent,
                    'avisSpe' => $convocation->avisSpe,
                    'created_at' => $convocation->created_at->format('d/m/Y'),
                    'formatted_date' => $convocation->created_at->format('d/m/Y H:i')
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
