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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * SECURITY: Verify the authenticated user matches the route parameter officer.
     * This prevents IDOR (Insecure Direct Object Reference) attacks.
     */
    private function authorizeOfficerAccess($officer): void
    {
        $authenticatedUser = auth()->user();
        if (!$authenticatedUser || $authenticatedUser->id != $officer->id) {
            abort(403, 'Unauthorized: You can only access your own data');
        }
    }

    /**
     * SECURITY: Check if officer can access a specific report.
     */
    private function canAccessReport($officer, $report): bool
    {
        // Owner can always access
        if ($report->officer_id === $officer->id) {
            return true;
        }

        // Destination officer can access
        if ($report->destination === $officer->id) {
            return true;
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($officer, Request $request)
    {
        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);



        $query = $officer->reports()->with(['student']);

        // SECURITY: Validate search input to prevent XSS and SQL issues
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:all,refused,done,pending'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'is_medical' => ['nullable', 'boolean']
        ]);

        // Apply filters
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
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
                        'arret' => $report->arret,
                        'sanction' => $report->sanction,

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
                            'section_code' => $report->student->section ? $report->student->section->code() : 'N/A',
                            'companie' => $report->student->companie()
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
        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);

        return view('report.create', ["officer" => $officer]);
    }

    /**
     * Store a newly created report.
     */
    public function store(Officer $id, Request $request)
    {
        $officer = $id;

        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);

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
            $validated['destination'] ?? null
        )) {
            return response()->json([
                'message' => 'Erreur lors de l initialisation du rapport.',
            ], 500);
        }

        // Set is_medical explicitly (init doesn't set it)
        $report->is_medical = $isMedical;

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
        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);

        $report = Report::with(['student.section', 'sanction'])->findOrFail($report_id);

        // SECURITY: Verify officer can access this specific report
        if (!$this->canAccessReport($officer, $report)) {
            abort(403, 'Unauthorized: You do not have access to this report');
        }

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

            // Prepare arret data if sanction of type 'arret' exists
            $arretData = null;
            if ($report->sanction && $report->sanction->type === 'arret') {
                $arretData = [
                    'id' => $report->sanction->id,
                    'date_debut' => $report->sanction->date_debut,
                    'date_fin' => $report->sanction->date_fin,
                    'motif' => $report->sanction->motif,
                ];
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
                    'officer_id' => $report->officer_id,
                    'created_at' => $report->created_at->format('Y-m-d H:i'),
                    'avis_by_role' => $avisByRole,
                    'roles' => $roles,
                    'arret' => $arretData,
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

        // SECURITY: Verify authorization - officer must be the destination of the report
        $this->authorizeOfficerAccess($officer);

        // SECURITY: Check if the officer is authorized to give avis on this report
        if ($report->destination !== $officer->id && $report->officer_id !== $officer->id) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Non autorisé: Vous ne pouvez pas donner un avis sur ce rapport.',
                ], 403);
            }
            abort(403, 'Unauthorized: You cannot give avis on this report');
        }

        $avis = $request->input('avis');

        // if()

        $roleName = (string) $officer->role->name;
        $normalizedRole = Str::of($roleName)->ascii()->lower()->toString();
        if ($normalizedRole == 'medecin') {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Avis non supporte pour ce role.',
                ], 403);
            }

            abort(403, 'Avis non supporte pour ce role.');
        }

        $targetColumnKey = 'avis' . str_replace(' ', '_', $normalizedRole);
        $avisRole = null;
        foreach (Schema::getColumnListing('reports') as $column) {
            $normalizedColumn = Str::of($column)->ascii()->lower()->toString();
            if ($normalizedColumn === $targetColumnKey) {
                $avisRole = $column;
                break;
            }
        }

        if (!$avisRole) {

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Avis non supporte pour ce role.',
                ], 422);
            }

            abort(422, 'Avis non supporte pour ce role.');
        }

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

        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);

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
                            'section_code' => $report->student->section ? $report->student->section->code() : 'N/A',
                            'companie' => $report->student->companie()
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

        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);

        // SECURITY: Verify officer can refuse this report (must be destination)
        if ($report->destination !== $officer->id) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Non autorisé: Vous ne pouvez pas refuser ce rapport.',
                ], 403);
            }
            abort(403, 'Unauthorized: You cannot refuse this report');
        }

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

        // SECURITY: Verify authorization
        $this->authorizeOfficerAccess($officer);

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
                        'section_code' => $report->student->section ? $report->student->section->code() : 'N/A',
                        'companie' => $report->student->companie()
                    ]
                ];
            })
        ]);
    }
}
