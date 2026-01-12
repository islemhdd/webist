<?php

namespace App\Http\Controllers;

use App\Models\Exemption;
use App\Models\ListeRdv;
use App\Models\Officer;
use App\Models\Report;
use App\Models\Sanction;
use App\Models\Section;
use App\Models\Sortie;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $officer = $user->isOfficer();

            if (!$user) {
                return redirect()->route('login');
            }

            $query = Student::with('section');
            $roleId = $user->role_id;

            if ($roleId === 1) {
                $companies = $officer->companie();
                if (!empty($companies)) {
                    $query->whereHas('section', function ($sectionQuery) use ($companies) {
                        $sectionQuery->whereIn('companie', $companies);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            } elseif ($roleId === 2 && isset($user->bat)) {
                $query->where('grade', $user->bat);
            }

            $students = $query->get();

            if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'students' => $students
                ]);
            }

            return view('brigade.students', ['students' => []]);
        } catch (\Exception $e) {
            Log::error('Error in StudentController index: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unable to load students data'], 500);
            }

            return redirect()->back()->with('error', 'Unable to load students data.');
        }
    }

    /**
     * Search for students by matricule
     */
    public function search(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $officer = $user->isOfficer();
            if (!$officer) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            $request->validate([
                'search' => ['required', 'string', 'max:10']
            ]);

            $search = trim($request->input("search"));

            if (!is_numeric($search)) {
                return response()->json([
                    'students' => [],
                    'message' => 'Search term must be numeric'
                ], 400);
            }

            $searchInt = (int) $search;
            $searchLength = strlen($search);

            $query = Student::with('section');
            $roleId = $user->role_id;

            if ($roleId === 1) {
                $companies = $officer->companie();
                if (!empty($companies)) {
                    $query->whereHas('section', function ($sectionQuery) use ($companies) {
                        $sectionQuery->whereIn('companie', $companies);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            } elseif ($roleId === 2 && isset($officer->bat)) {
                $query->where('grade', $officer->bat);
            }

            if ($searchLength == 7) {
                $students = $query->where('matricule', $searchInt)->get();
            } elseif ($searchLength < 7) {
                $lowerBound = $searchInt * pow(10, 7 - $searchLength);
                $upperBound = (($searchInt + 1) * pow(10, 7 - $searchLength)) - 1;
                $students = $query->whereBetween('matricule', [$lowerBound, $upperBound])->get();
            } else {
                $students = collect();
            }

            return response()->json([
                'students' => $students
            ]);
        } catch (\Exception $e) {
            Log::error('Error in student search: ' . $e->getMessage(), [
                'search' => $request->input("search"),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Search failed'], 500);
        }
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
            $studentData = [
                'student' => $this->getBasicStudentInfo($student),
                'exemptions' => $this->getStudentExemptions($matricule),
                'rdvs' => $this->getStudentRdvs($matricule),
                'sorties' => $this->getStudentSorties($matricule),
                'reports' => $this->getStudentReports($matricule),
                'sanctions' => $this->getStudentSanctions($matricule)
            ];

            return response()->json($studentData);
        } catch (\Exception $e) {
            Log::error('Error retrieving student data: ' . $e->getMessage());
            return response()->json(['error' => 'a error'], 450);
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
                'id' => $student->section->id ?? null,
                'num' => $student->section->num ?? null,
                'companie' => $student->section->companie ?? null,
                'bat' => $student->section->bat ?? null,
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
        $sorties = Sortie::where('matricule', $matricule)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'sorties' => $sorties->map(function ($sortie) {
                return [
                    'id' => $sortie->id,
                    'choix' => $sortie->choix,
                    'remarque' => $sortie->remarque ?? null,
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
        $roleId = $officer->role_id;

        if ($roleId === 1) {
            $companies = $officer->companie();
            return !empty($companies)
                && in_array((int) $student->section->companie, $companies, true);
        }

        if ($roleId === 2) {
            return $student->grade == $officer->bat;
        }

        return true;
    }

    /**
     * Get student data with access control
     */
    public function show($matricule)
    {
        try {
            // Get the authenticated user's officer record
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get the officer record for authorization
            $officer = $user->isOfficer();
            if (!$officer) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            // Validate student access
            if (!$this->canAccessStudent($matricule, $officer)) {
                return response()->json(['error' => 'Access denied for this student'], 403);
            }

            return $this->getStudentData($matricule);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage(), [
                'matricule' => $matricule,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Get graph data for student sorties
     */
    public function getStudentGraphData(Request $request, $matricule)
    {
        try {
            // Get the authenticated user's officer record
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Handle both POST JSON and GET query parameters
            if ($request->isMethod('post')) {
                $from = $request->input('from');
                $to = $request->input('to');
                $unit = $request->input('unit');
            } else {
                $from = $request->query('from');
                $to = $request->query('to');
                $unit = $request->query('unit');
            }

            // Validate required parameters
            if (!$from || !$to || !$unit) {
                Log::error('Student graph data request missing parameters', [
                    'matricule' => $matricule,
                    'from' => $from,
                    'to' => $to,
                    'unit' => $unit,
                    'method' => $request->method()
                ]);
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            // Validate date format
            try {
                Carbon::parse($from);
                Carbon::parse($to);
            } catch (\Exception $e) {
                Log::error('Invalid date format in student graph request', [
                    'from' => $from,
                    'to' => $to,
                    'error' => $e->getMessage()
                ]);
                return response()->json(['error' => 'Invalid date format'], 400);
            }

            Log::info('Generating student sortie graph data', [
                'matricule' => $matricule,
                'from' => $from,
                'to' => $to,
                'unit' => $unit
            ]);

            $data = $this->generateStudentSortieGraphData($matricule, $from, $to, $unit);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error generating student graph data: ' . $e->getMessage(), [
                'matricule' => $matricule,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to generate graph data'], 500);
        }
    }

    /**
     * Generate graph data for student sorties
     */
    private function generateStudentSortieGraphData($matricule, $from, $to, $unit)
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);
        $labels = [];
        $data = [];

        switch ($unit) {
            case 'days':
                $current = $fromDate->copy();
                while ($current->lte($toDate)) {
                    $labels[] = $current->format('d/m');
                    $count = $this->getStudentSortieCountForDate($matricule, $current);
                    $data[] = $count;
                    $current->addDay();
                }
                break;

            case 'weeks':
                $current = $fromDate->copy()->startOfWeek();
                while ($current->lte($toDate)) {
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = $this->getStudentSortieCountForPeriod($matricule, $current, $weekEnd);
                    $data[] = $count;
                    $current->addWeek();
                }
                break;

            case 'months':
                $current = $fromDate->copy()->startOfMonth();
                while ($current->lte($toDate)) {
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = $this->getStudentSortieCountForPeriod($matricule, $current, $monthEnd);
                    $data[] = $count;
                    $current->addMonth();
                }
                break;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get sortie count for specific date
     */
    private function getStudentSortieCountForDate($matricule, $date)
    {
        return Sortie::where('matricule', $matricule)
            ->whereDate('created_at', $date)
            ->count();
    }

    /**
     * Get sortie count for period
     */
    private function getStudentSortieCountForPeriod($matricule, $start, $end)
    {
        return Sortie::where('matricule', $matricule)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    /**
     * Get comprehensive graph data for all student data types
     */
    public function getStudentAllGraphData(Request $request, $matricule)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get the officer record for authorization
            $officer = $user->isOfficer();
            if (!$officer) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            // Handle both dataType and data_type for compatibility
            $dataType = $request->input('dataType') ?? $request->input('data_type'); // sorties, sanctions, medical, reports, rdvs, exemptions
            $graphType = $request->input('graphType') ?? $request->input('graph_type', 'line'); // line, bar, pie, doughnut
            $from = $request->input('from');
            $to = $request->input('to');
            $unit = $request->input('unit', 'days'); // days, weeks, months

            if (!$dataType || !$from || !$to) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            // Validate student access
            if (!$this->canAccessStudent($matricule, $officer)) {
                return response()->json(['error' => 'Access denied for this student'], 403);
            }

            $data = $this->generateGraphDataByType($matricule, $dataType, $graphType, $from, $to, $unit);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating student graph data: ' . $e->getMessage(), [
                'matricule' => $matricule,
                'dataType' => $request->input('dataType') ?? $request->input('data_type'),
                'graphType' => $request->input('graphType') ?? $request->input('graph_type'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'unit' => $request->input('unit'),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate graph data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate graph data based on data type
     */
    private function generateGraphDataByType($matricule, $dataType, $graphType, $from, $to, $unit)
    {
        switch ($dataType) {
            case 'sorties':
                return $this->generateSortiesGraphData($matricule, $graphType, $from, $to, $unit);
            case 'sanctions':
                return $this->generateSanctionsGraphData($matricule, $graphType, $from, $to, $unit);
            case 'reports':
                return $this->generateReportsGraphData($matricule, $graphType, $from, $to, $unit);
            case 'rdvs':
                return $this->generateRdvsGraphData($matricule, $graphType, $from, $to, $unit);
            case 'exemptions':
                return $this->generateExemptionsGraphData($matricule, $graphType, $from, $to, $unit);
            default:
                throw new \Exception('Invalid data type');
        }
    }

    /**
     * Generate sorties graph data
     */
    private function generateSortiesGraphData($matricule, $graphType, $from, $to, $unit)
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($graphType === 'pie' || $graphType === 'doughnut') {
            // For pie/doughnut, show distribution by choix type
            $sorties = Sortie::where('matricule', $matricule)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();

            $distribution = $sorties->groupBy('choix')->map->count();

            return [
                'type' => $graphType,
                'labels' => $distribution->keys()->toArray(),
                'datasets' => [[
                    'data' => $distribution->values()->toArray(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    'label' => 'Distribution des sorties'
                ]]
            ];
        }

        // For line/bar charts, show timeline data
        $labels = [];
        $data = [];
        $current = $fromDate->copy();

        while ($current->lte($toDate)) {
            switch ($unit) {
                case 'days':
                    $labels[] = $current->format('d/m');
                    $count = Sortie::where('matricule', $matricule)
                        ->whereDate('created_at', $current)
                        ->count();
                    $current->addDay();
                    break;
                case 'weeks':
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = Sortie::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $weekEnd])
                        ->count();
                    $current->addWeek();
                    break;
                case 'months':
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = Sortie::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $monthEnd])
                        ->count();
                    $current->addMonth();
                    break;
            }
            $data[] = $count;
        }

        return [
            'type' => $graphType,
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Nombre de sorties',
                'data' => $data,
                'borderColor' => '#36A2EB',
                'backgroundColor' => $graphType === 'bar' ? '#36A2EB' : 'rgba(54, 162, 235, 0.2)',
                'tension' => 0.4
            ]]
        ];
    }

    /**
     * Generate sanctions graph data
     */
    private function generateSanctionsGraphData($matricule, $graphType, $from, $to, $unit)
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($graphType === 'pie' || $graphType === 'doughnut') {
            $sanctions = Sanction::where('matricule', $matricule)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();

            $distribution = $sanctions->groupBy('type')->map->count();

            return [
                'type' => $graphType,
                'labels' => $distribution->keys()->toArray(),
                'datasets' => [[
                    'data' => $distribution->values()->toArray(),
                    'backgroundColor' => ['#FF6384', '#FF9F40', '#FFCD56', '#4BC0C0'],
                    'label' => 'Types de sanctions'
                ]]
            ];
        }

        $labels = [];
        $data = [];
        $current = $fromDate->copy();

        while ($current->lte($toDate)) {
            switch ($unit) {
                case 'days':
                    $labels[] = $current->format('d/m');
                    $count = Sanction::where('matricule', $matricule)
                        ->whereDate('created_at', $current)
                        ->count();
                    $current->addDay();
                    break;
                case 'weeks':
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = Sanction::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $weekEnd])
                        ->count();
                    $current->addWeek();
                    break;
                case 'months':
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = Sanction::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $monthEnd])
                        ->count();
                    $current->addMonth();
                    break;
            }
            $data[] = $count;
        }

        return [
            'type' => $graphType,
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Nombre de sanctions',
                'data' => $data,
                'borderColor' => '#FF6384',
                'backgroundColor' => $graphType === 'bar' ? '#FF6384' : 'rgba(255, 99, 132, 0.2)',
                'tension' => 0.4
            ]]
        ];
    }

    /**
     * Generate reports graph data
     */
    private function generateReportsGraphData($matricule, $graphType, $from, $to, $unit)
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($graphType === 'pie' || $graphType === 'doughnut') {
            $reports = Report::where('student_id', $matricule)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();

            $statusCounts = $reports->groupBy('status')->map->count();

            return [
                'type' => $graphType,
                'labels' => $statusCounts->keys()->toArray(),
                'datasets' => [[
                    'data' => $statusCounts->values()->toArray(),
                    'backgroundColor' => ['#9966FF', '#FF9F40', '#36A2EB', '#FF6384'],
                    'label' => 'État des rapports'
                ]]
            ];
        }

        $labels = [];
        $data = [];
        $current = $fromDate->copy();

        while ($current->lte($toDate)) {
            switch ($unit) {
                case 'days':
                    $labels[] = $current->format('d/m');
                    $count = Report::where('student_id', $matricule)
                        ->whereDate('created_at', $current)
                        ->count();
                    $current->addDay();
                    break;
                case 'weeks':
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = Report::where('student_id', $matricule)
                        ->whereBetween('created_at', [$current, $weekEnd])
                        ->count();
                    $current->addWeek();
                    break;
                case 'months':
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = Report::where('student_id', $matricule)
                        ->whereBetween('created_at', [$current, $monthEnd])
                        ->count();
                    $current->addMonth();
                    break;
            }
            $data[] = $count;
        }

        return [
            'type' => $graphType,
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Nombre de rapports',
                'data' => $data,
                'borderColor' => '#9966FF',
                'backgroundColor' => $graphType === 'bar' ? '#9966FF' : 'rgba(153, 102, 255, 0.2)',
                'tension' => 0.4
            ]]
        ];
    }

    /**
     * Generate RDVs graph data
     */
    private function generateRdvsGraphData($matricule, $graphType, $from, $to, $unit)
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($graphType === 'pie' || $graphType === 'doughnut') {
            $rdvs = ListeRdv::where('matricule', $matricule)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();

            $motifCounts = $rdvs->groupBy('motif')->map->count();

            return [
                'type' => $graphType,
                'labels' => $motifCounts->keys()->toArray(),
                'datasets' => [[
                    'data' => $motifCounts->values()->toArray(),
                    'backgroundColor' => ['#FF9F40', '#4BC0C0', '#36A2EB'],
                    'label' => 'Types de RDV'
                ]]
            ];
        }

        $labels = [];
        $data = [];
        $current = $fromDate->copy();

        while ($current->lte($toDate)) {
            switch ($unit) {
                case 'days':
                    $labels[] = $current->format('d/m');
                    $count = ListeRdv::where('matricule', $matricule)
                        ->whereDate('created_at', $current)
                        ->count();
                    $current->addDay();
                    break;
                case 'weeks':
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = ListeRdv::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $weekEnd])
                        ->count();
                    $current->addWeek();
                    break;
                case 'months':
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = ListeRdv::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $monthEnd])
                        ->count();
                    $current->addMonth();
                    break;
            }
            $data[] = $count;
        }

        return [
            'type' => $graphType,
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Nombre de RDV',
                'data' => $data,
                'borderColor' => '#FF9F40',
                'backgroundColor' => $graphType === 'bar' ? '#FF9F40' : 'rgba(255, 159, 64, 0.2)',
                'tension' => 0.4
            ]]
        ];
    }

    /**
     * Generate exemptions graph data
     */
    private function generateExemptionsGraphData($matricule, $graphType, $from, $to, $unit)
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($graphType === 'pie' || $graphType === 'doughnut') {
            $exemptions = Exemption::where('matricule', $matricule)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();

            $now = now();
            $activeCount = $exemptions->where('date_fin', '>=', $now)->count();
            $expiredCount = $exemptions->where('date_fin', '<', $now)->count();

            return [
                'type' => $graphType,
                'labels' => ['Actives', 'Expirées'],
                'datasets' => [[
                    'data' => [$activeCount, $expiredCount],
                    'backgroundColor' => ['#4BC0C0', '#FF6384'],
                    'label' => 'État des exemptions'
                ]]
            ];
        }

        $labels = [];
        $data = [];
        $current = $fromDate->copy();

        while ($current->lte($toDate)) {
            switch ($unit) {
                case 'days':
                    $labels[] = $current->format('d/m');
                    $count = Exemption::where('matricule', $matricule)
                        ->whereDate('created_at', $current)
                        ->count();
                    $current->addDay();
                    break;
                case 'weeks':
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = Exemption::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $weekEnd])
                        ->count();
                    $current->addWeek();
                    break;
                case 'months':
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = Exemption::where('matricule', $matricule)
                        ->whereBetween('created_at', [$current, $monthEnd])
                        ->count();
                    $current->addMonth();
                    break;
            }
            $data[] = $count;
        }

        return [
            'type' => $graphType,
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Nombre d\'exemptions',
                'data' => $data,
                'borderColor' => '#FFCE56',
                'backgroundColor' => $graphType === 'bar' ? '#FFCE56' : 'rgba(255, 206, 86, 0.2)',
                'tension' => 0.4
            ]]
        ];
    }
}
