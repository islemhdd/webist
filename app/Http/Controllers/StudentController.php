<?php

namespace App\Http\Controllers;

use App\Models\Convoncu;
use App\Models\Exemption;
use App\Models\ListeRdv;
use App\Models\Officer;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Sanction;
use App\Models\Section;
use App\Models\Sortie;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $query = Student::with('section');

        switch ($user->status) {
            case 'Chef de compagnie':
                // L'utilisateur dirige certaines sections
                $sectionIds = $user->sections()->pluck('id');
                $query->whereIn('section_id', $sectionIds);
                break;

            case 'Chef de batallaint':
                // L'utilisateur est responsable d’un bataillon, comparé à grade
                $query->where('grade', $user->bat);
                break;

            default:
                // Autres statuts : accès complet (aucun filtre)
                break;
        }

        // ✅ Pas de pagination : collection complète
        $students = $query->get();

        return view('brigade.students', compact('students'));
    }




    /**
     * Search for students by matricule
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => ['required', 'string', 'max:10']
        ]);

        $search = trim($request->input("search"));

        // Convert to integer if it's numeric
        if (!is_numeric($search)) {
            return response()->json([
                'students' => [],
                'message' => 'Search term must be numeric'
            ], 400);
        }

        $searchInt = (int)$search;
        $searchLength = strlen($search);

        if ($searchLength == 7) {
            // Exact match for 7-digit matricule
            $students = Student::with('section')
                ->where('matricule', $searchInt)
                ->get();
        } elseif ($searchLength < 7) {
            // Prefix search for partial matricule
            $lowerBound = $searchInt * pow(10, 7 - $searchLength);
            $upperBound = (($searchInt + 1) * pow(10, 7 - $searchLength)) - 1;

            $students = Student::with('section')
                ->whereBetween('matricule', [$lowerBound, $upperBound])
                ->get();
        }

        return response()->json([
            'students' => $students
        ]);
    }

    /**
     * Get complete student information by matricule
     */
    public function getStudentData($matricule)
    {
        try {

            $student = Student::with(['section.officer'])
                ->where('matricule', $matricule)
                ->first();

            if (!$student) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            // Get all related data
            $studentData = ['student' => $this->getBasicStudentInfo($student), 'student' => $this->getBasicStudentInfo($student), 'medical' => $this->getStudentMedicalInfo($matricule), 'exemptions' => $this->getStudentExemptions($matricule), 'rdvs' => $this->getStudentRdvs($matricule), 'sorties' => $this->getStudentSorties($matricule), 'reports' => $this->getStudentReports($matricule)];
            // $studentData = [
            //
            //
            //];
            //     ,
            //
            //
            // ];

            return response()->json($studentData);
        } catch (\Exception $e) {
            Log::error('Error retrieving student data: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Get basic student information
     */
    private function getBasicStudentInfo($student)
    {
        return [
            'matricule' => $student->matricule,
            'nom' => $student->nom,
            'prenom' => $student->prenom,
            'grade' => $student->grade,
            'consigned' => $student->consigned,
            'choix' => $student->choix,
            'section' => [
                'id' => $student->section->id,
                'num' => $student->section->num,
                'companie' => $student->section->companie,
                'bat' => $student->section->bat,
                'officer' => optional($student->section->officer)->username ?? 'N/A'
            ],
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at
        ];
    }

    /**
     * Get student sanctions
     */
    private function getStudentSanctions($matricule)
    {
        $sanctions = Sanction::where('matricule', $matricule)
            ->orderBy('created_at', 'desc')
            ->get();

        $now = now();
        $activeSanctions = [];
        $pastSanctions = [];

        foreach ($sanctions as $sanction) {
            $sanctionData = [
                'id' => $sanction->id,
                'type' => $sanction->type,
                'motif' => $sanction->motif,
                'date_debut' => $sanction->date_debut,
                'date_fin' => $sanction->date_fin,
                'is_active' => $sanction->date_fin >= $now,
                'created_at' => $sanction->created_at
            ];

            if ($sanction->date_fin >= $now) {
                $activeSanctions[] = $sanctionData;
            } else {
                $pastSanctions[] = $sanctionData;
            }
        }

        return [
            'active' => $activeSanctions,
            'past' => $pastSanctions,
            'total' => count($sanctions),
            'stats' => [
                'consigne' => $sanctions->where('type', 'consigne')->count(),
                'arret' => $sanctions->where('type', 'arret')->count(),
                'blame' => $sanctions->where('type', 'blame')->count(),
                'avert' => $sanctions->where('type', 'avert')->count()
            ]
        ];
    }

    /**
     * Get student medical information
     */
    private function getStudentMedicalInfo($matricule)
    {
        // Get patient records
        $patients = Patient::where('matricule', $matricule)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get convocation data
        $convocation = Convoncu::where('matricule', $matricule)->first();

        // Get medical exemptions
        $exemptions = Exemption::where('matricule', $matricule)
            ->orderBy('date_debut', 'desc')
            ->get();

        return [
            'patients' => $patients->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'valider' => $patient->valider,
                    'validated_at' => $patient->validated_at,
                    'type_medecin' => $patient->type_medecin,
                    'avis_medecin' => $patient->avis_medecin,
                    'valider_rhp' => $patient->valider_rhp,
                    'motif_suppression' => $patient->motif_suppression,
                    'created_at' => $patient->created_at
                ];
            }),
            'convocation' => $convocation ? [
                'psy' => $convocation->psy,
                'medGen' => $convocation->medGen,
                'chirDent' => $convocation->chirDent,
                'avisSpe' => $convocation->avisSpe,
                'created_at' => $convocation->created_at
            ] : null,
            'exemptions' => $exemptions->map(function ($exemption) {
                return [
                    'motif' => $exemption->motif,
                    'date_debut' => $exemption->date_debut,
                    'date_fin' => $exemption->date_fin,
                    'is_active' => $exemption->date_fin >= now(),
                    'created_at' => $exemption->created_at
                ];
            })
        ];
    }

    /**
     * Get student reports (patines)
     */
    private function getStudentReports($matricule)
    {
        $reports = Report::where('student_id', $matricule)
            ->with(['owner'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'reports' => $reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'corps' => $report->corps,
                    'status' => $report->status,
                    'is_medical' => $report->is_medical,
                    'motif' => $report->motif,
                    'refused' => $report->refused,
                    'officer' => optional($report->officer)->username ?? 'N/A',
                    'avis' => [
                        'chef_compagnie' => $report->AvisChef_de_compagnie,
                        'chef_batallaint' => $report->AvisChef_de_batallaint,
                        'chef_brigade' => $report->AvisChef_de_brigade,
                        'chef_division' => $report->AvisChef_division,
                        'general' => $report->Avis
                    ],
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at
                ];
            }),
            'stats' => [
                'pending' => $reports->whereNotIn('status', ['DONE', 'REFUSED'])->count(),
                'validated' => $reports->where('status', 'DONE')->count(),
                'refused' => $reports->where('status', 'REFUSED')->count(),
                'total' => $reports->count()
            ]
        ];
    }

    /**
     * Get student exemptions
     */
    private function getStudentExemptions($matricule)
    {
        $exemptions = Exemption::where('matricule', $matricule)
            ->orderBy('date_debut', 'desc')
            ->get();

        $now = now();
        $activeExemptions = [];
        $pastExemptions = [];

        foreach ($exemptions as $exemption) {
            $exemptionData = [
                'motif' => $exemption->motif,
                'date_debut' => $exemption->date_debut,
                'date_fin' => $exemption->date_fin,
                'is_active' => $exemption->date_fin >= $now,
                'created_at' => $exemption->created_at
            ];

            if ($exemption->date_fin >= $now) {
                $activeExemptions[] = $exemptionData;
            } else {
                $pastExemptions[] = $exemptionData;
            }
        }

        return [
            'active' => $activeExemptions,
            'past' => $pastExemptions,
            'total' => count($exemptions)
        ];
    }

    /**
     * Get student sorties (weekend permissions)
     */
    private function getStudentSorties($matricule)
    {
        $sorties = Sortie::where('student_id', $matricule)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'sorties' => $sorties->map(function ($sortie) {
                return [
                    'id' => $sortie->id,
                    'choix' => $sortie->choix,
                    'remarque' => $sortie->remarque,
                    'from' => $sortie->from,
                    'to' => $sortie->to,
                    'created_at' => $sortie->created_at
                ];
            }),
            'stats' => [
                'vendredi' => $sorties->where('choix', 'ven')->count(),
                'samedi' => $sorties->where('choix', 'sam')->count(),
                'h48' => $sorties->where('choix', '48h')->count(),
                'h36' => $sorties->where('choix', '36h')->count(),
                'total' => $sorties->count()
            ]
        ];
    }

    /**
     * Get student RDVs (appointments)
     */
    private function getStudentRdvs($matricule)
    {
        $rdvs = ListeRdv::where('matricule', $matricule)
            ->orderBy('date', 'desc')
            ->get();

        $now = now();
        $upcomingRdvs = [];
        $pastRdvs = [];

        foreach ($rdvs as $rdv) {
            $rdvData = [
                'motif' => $rdv->motif,
                'service' => $rdv->service,
                'date' => $rdv->date,
                'is_upcoming' => $rdv->date > $now,
                'created_at' => $rdv->created_at
            ];

            if ($rdv->date > $now) {
                $upcomingRdvs[] = $rdvData;
            } else {
                $pastRdvs[] = $rdvData;
            }
        }

        return [
            'upcoming' => $upcomingRdvs,
            'past' => $pastRdvs,
            'stats' => [
                'consultation' => $rdvs->where('motif', 'consultation')->count(),
                'urgences' => $rdvs->where('motif', 'urgences')->count(),
                'total' => $rdvs->count()
            ]
        ];
    }

    /**
     * Get student summary (lightweight version)
     */
    public function getStudentSummary($matricule)
    {
        try {
            $student = Student::with('section')->where('matricule', $matricule)->first();

            if (!$student) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            $now = now();

            // Get active sanctions count
            $activeSanctions = Sanction::where('matricule', $matricule)
                ->where('date_fin', '>=', $now)
                ->count();

            // Get pending reports count
            $pendingReports = Report::where('student_id', $matricule)
                ->whereNotIn('status', ['DONE', 'REFUSED'])
                ->count();

            // Get active exemptions count
            $activeExemptions = Exemption::where('matricule', $matricule)
                ->where('date_fin', '>=', $now)
                ->count();

            // Get upcoming RDVs count
            $upcomingRdvs = ListeRdv::where('matricule', $matricule)
                ->where('date', '>', $now)
                ->count();

            $summary = [
                'student' => [
                    'matricule' => $student->matricule,
                    'nom' => $student->nom,
                    'prenom' => $student->prenom,
                    'grade' => $student->grade,
                    'consigned' => $student->consigned,
                    'choix' => $student->choix,
                    'section_id' => $student->section_id
                ],
                'counters' => [
                    'active_sanctions' => $activeSanctions,
                    'pending_reports' => $pendingReports,
                    'active_exemptions' => $activeExemptions,
                    'upcoming_rdvs' => $upcomingRdvs
                ]
            ];

            return response()->json($summary);
        } catch (\Exception $e) {
            Log::error('Error retrieving student summary: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Check if officer can access student data
     */
    private function canAccessStudent($matricule, Officer $officer)
    {
        $student = Student::where('matricule', $matricule)->first();

        if (!$student) {
            return false;
        }

        // Check if officer has a role
        if (!$officer->role) {
            return false;
        }

        switch ($officer->role->name) {
            case 'Chef de compagnie':
                // Can only access students in their sections
                $officerSections = Section::where('officer_id', $officer->id)->pluck('id');
                return $officerSections->contains($student->section_id);

            case 'Chef de batallaint':
                // Can access students in their battalion
                return $student->grade == $officer->bat;

            case 'Chef de brigade':
            case 'Chef division':
            case 'Directeur général':
                // Can access all students
                return true;

            case 'Medecin':
                // Can access students with medical records
                return Patient::where('matricule', $matricule)->exists() ||
                    Convoncu::where('matricule', $matricule)->exists();

            default:
                return false;
        }
    }

    /**
     * Get student data with access control
     */
    public function show($matricule)
    {
        try {
            // Get the authenticated user's officer record
            $user = auth()->user();

            if (!$user || !$user->isOfficer()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }


            $officer = $user->isOfficer();


            if (!$this->canAccessStudent($matricule, $officer)) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            return $this->getStudentData($matricule);           //!working , problem in displaying data
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
