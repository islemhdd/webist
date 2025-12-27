<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Notifications\Report as NotificationsReport;


use App\Models\Report;
use App\Models\Student;
use App\Providers\AppServiceProvider;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($officer, Request $request)
    {



        $query = $officer->reports()->with(['student']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                // Search in report title
                $q->where('title', 'like', "%{$search}%")
                    // Search in student name
                    ->orWhereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->get('status') !== 'all') {
            $status = $request->get('status');
            if ($status === 'refused') {
                $query->where('refused', 1);
            } elseif ($status === 'done') {
                $query->where('status', 'DONE');
            } elseif ($status === 'pending') {
                $query->where('status', '!=', 'DONE')->where('refused', 0);
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if ($request->filled('is_medical')) {
            $query->where('is_medical', $request->get('is_medical'));
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'reports' => $reports->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'title' => $report->title,
                        'status' => $report->status,
                        'refused' => $report->refused,
                        'is_medical' => $report->is_medical,
                        'created_at' => $report->created_at->format('Y-m-d H:i'),
                        'student' => [
                            'matricule' => $report->student->matricule,
                            'nom' => $report->student->nom,
                            'prenom' => $report->student->prenom,
                            'section_code' => $report->student->section ? $report->student->section->code() : 'N/A'
                        ]
                    ];
                })
            ]);
        }

        return view('report.index', compact('reports', 'officer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($officer)
    {

        return view('report.create', ["officer" => $officer]);
    }

    /**
     * Store a newly created report.
     */
    public function store(Officer $id, Request $request)
    {
        $officer = $id;

        $validated = $request->validate([
            'mat' => ['required', 'exists:students,matricule'],
            'title' => ['required', 'min:3'],
            'corps' => ['required', 'min:10'],
            'is_medical' => ['nullable', 'boolean'],
            'destination' => ['nullable'],
        ]);

        $report = new Report();
        $isMedical = (bool) ($validated['is_medical'] ?? false);

        if (!$report->init(
            $validated['mat'],
            $officer->id,
            $officer->role->name,
            $validated['title'],
            $validated['corps'],
            $isMedical,
            $validated['destination'] ?? null
        )) {
            return response()->json([
                'message' => 'Erreur lors de l initialisation du rapport.',
            ], 500);
        }

        if ($report->existing()) {
            return response()->json([
                'message' => 'Un rapport similaire existe deja.',
            ], 409);
        }

        $report->save();
        $officer->officerNotify($report);

        $redirectUrl = route('report.show', [
            'report_id' => $report->id,
            'id' => $officer->id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Rapport cree avec succes.',
                'report_id' => $report->id,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->route('report.show', [
            'report_id' => $report->id,
            'id' => $officer->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // // public function store(Request $request)


    /**
     * Display the specified resource.
     */
    public function show($officer, $report_id)
    {


        $report = Report::with(['student.section'])->findOrFail($report_id);

        if (request()->ajax()) {
            $roles = [
                'Chef de compagnie',
                'Chef de batallaint',
                'Chef de brigade',
                'Chef division',
                'Medecin',
                'Directeur général',
            ];

            $avisByRole = [];
            foreach ($roles as $roleName) {
                if (!$report->is_medical && $roleName === 'Medecin') {
                    continue;
                }
                $avisKey = 'Avis' . str_replace(' ', '_', $roleName);
                $avisByRole[$roleName] = $report->$avisKey ?? null;
            }

            return response()->json([
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                    'corps' => $report->corps,
                    'status' => $report->status,
                    'refused' => $report->refused,
                    'motif' => $report->motif,
                    'is_medical' => $report->is_medical,
                    'created_at' => $report->created_at->format('Y-m-d H:i'),
                    'avis_by_role' => $avisByRole,
                    'roles' => $roles,
                    'student' => [
                        'matricule' => $report->student->matricule,
                        'nom' => $report->student->nom,
                        'prenom' => $report->student->prenom,
                        'grade' => $report->student->grade,
                        'section_code' => $report->student->section ? $report->student->section->code() : 'N/A',
                        'companie' => $report->student->companie()
                    ]
                ],
                'officer' => [
                    'id' => $officer->id,
                    'role_name' => $officer->role?->name,
                ],
            ]);
        }

        if ($officer)
            return view('report.show', ["report" => $report, "officer" => $officer]);
        return view('report.show', ["report" => $report]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Clear notification and redirect to report
     */
    public function unsetReportNotificationAndRedirect(Officer $id, Report $report_id)
    {
        $officer = $id;
        $report = $report_id;
        $officer->unreadNotifications()->where('data->report_id', $report->id)->delete();


        return redirect()->route('report.show', ['id' => $officer->id, 'report_id' => $report->id]);
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */

    public function avis(Officer $id, Report $report, Request $request)
    {


        $officer = $id;
        // TODO : check if the officer is the owner of the report
        $avis = $request->input('avis');

        // if()

        $avisRole = 'Avis' . str_replace(' ', '_',  $officer->role->name);

        $report->update([
            $avisRole => $avis,

        ]);
        $report->save();


        $officer->officerNotify($report);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Avis enregistre.',
            ]);
        }

        return redirect()->back();
    }
    public function received(Officer $id)
    {
        $officer = $id;
        $reports = Report::where('destination', $officer->id)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->ajax()) {
            return response()->json([
                'reports' => $reports->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'title' => $report->title,
                        'status' => $report->status,
                        'refused' => $report->refused,
                        'is_medical' => $report->is_medical,
                        'created_at' => $report->created_at->format('Y-m-d H:i'),
                        'student' => [
                            'matricule' => $report->student->matricule,
                            'nom' => $report->student->nom,
                            'prenom' => $report->student->prenom,
                            'section_code' => $report->student->section ? $report->student->section->code() : 'N/A'
                        ]
                    ];
                })
            ]);
        }
        return view('report.received', compact('reports', 'officer'));
    }
    public function refuse(Officer $id, Report $report, Request $request)
    {
        $officer = $id;
        $officer->refuse($report, $request->input('motif'));

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Rapport refuse.',
            ]);
        }

        return redirect()->back();
    }

    /**
     * Search for reports based on various criteria
     */
    public function search(Request $request, $id)
    {
        $officer = Officer::find($id);

        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'is_medical' => ['nullable', 'boolean']
        ]);

        $query = $officer->reports()->with(['student']);

        // Apply search filters
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                // Search in report title
                $q->where('title', 'like', "%{$search}%")
                    // Search in student name and matricule
                    ->orWhereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('matricule', 'like', "%{$search}%");
                    });
            });
        }

        // Apply status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $status = $request->get('status');
            switch ($status) {
                case 'refused':
                    $query->where('refused', 1);
                    break;
                case 'done':
                    $query->where('status', 'DONE');
                    break;
                case 'pending':
                    $query->where('status', '!=', 'DONE')->where('refused', 0);
                    break;
                default:
                    $query->where('status', $status);
                    break;
            }
        }

        // Apply date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Apply medical filter
        if ($request->filled('is_medical')) {
            $query->where('is_medical', $request->get('is_medical'));
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'reports' => $reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'status' => $report->status,
                    'refused' => $report->refused,
                    'is_medical' => $report->is_medical,
                    'created_at' => $report->created_at->format('d/m/Y H:i'),
                    'created_at_raw' => $report->created_at->toDateString(),
                    'student' => [
                        'matricule' => $report->student->matricule,
                        'nom' => $report->student->nom,
                        'prenom' => $report->student->prenom,
                        'section_code' => $report->student->section ? $report->student->section->code() : 'N/A'
                    ]
                ];
            })
        ]);
    }
}
