<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Patient;
use App\Models\Sanction;
use App\Models\Student;
use App\Models\Report;
use App\Models\Sortie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BrigadeStatisticsController extends Controller
{
    private function getStudentsQueryByRole(Officer $officer)
    {
        $query = Student::query();


        switch ($officer->role->name) {
            case 'Chef de compagnie': // Chef de compagnie - show only their sections

                return $query->whereIn('section_id', function ($q) use ($officer) {
                    $q->select('id')
                        ->from('sections')
                        ->where('officer_id', $officer->id);
                });
            case 'Chef de batallaint': // Chef de bataillon - show students in their battalion
                return $query->where('grade', $officer->bat);
            case 'Chef de brigade': // Chef de brigade
            case 'Chef division': // Division
                return $query; // Show all students
            default:
                return $query;
        }

    }


    private function getWeekendStats($studentMatricules)
    {
        return [
            'vendredi' => Student::whereIn('matricule', $studentMatricules)
                ->where('choix', 'ven')->count(),
            'samedi' => Student::whereIn('matricule', $studentMatricules)
                ->where('choix', 'sam')->count(),
            'h48' => Student::whereIn('matricule', $studentMatricules)
                ->where('choix', '48h')->count(),
            'h36' => Student::whereIn('matricule', $studentMatricules)
                ->where('choix', '36h')->count(),
            'total' => Student::whereIn('matricule', $studentMatricules)
                ->whereNotNull('choix')->count()
        ];
    }

    private function getSanctionsStats($studentMatricules)
    {
        $today = today();

        $weekendStart = $today->copy()->endOfWeek()->subDay(); // Saturday
        $weekendEnd = $today->copy()->endOfWeek(); // Sunday

        // Get weekend restrictions (consignes for this weekend)
        $weekendRestrictions = Sanction::whereIn('matricule', $studentMatricules)
            ->where('type', 'consigne')->count();
        // ->where(function ($q) use ($weekendStart, $weekendEnd) {
        //     $q->whereDate('date_debut', '<=', $weekendEnd)
        //         ->whereDate('date_fin', '>=', $weekendStart);
        // })->count();

        // Get active arrests (not yet finished)
        $activeArrests = Sanction::whereIn('matricule', $studentMatricules)
            ->where('type', 'arret')
            ->whereDate('date_fin', '>=', $today)

            ->count();

        // Get past arrests (already finished)
        $pastArrests = Sanction::whereIn('matricule', $studentMatricules)
            ->where('type', 'arret')
            ->where('date_fin', '<', $today)
            ->count();


        // Get warnings (avertissements)
        $warnings = Sanction::whereIn('matricule', $studentMatricules)
            ->where('type', 'avert')
            ->count();

        return [
            'weekendRestrictions' => $weekendRestrictions,
            'activeArrests' => $activeArrests,
            'pastArrests' => $pastArrests,
            'warnings' => $warnings,
            'total' => $weekendRestrictions + $activeArrests + $pastArrests + $warnings
        ];
    }

    private function getPatineStats($studentMatricules)
    {
        return [
            'pending' => Report::whereIn('student_id', $studentMatricules)
                ->where('status', '!=', 'DONE')
                ->where('status', '!=', 'REFUSED')
                ->count(),
            'rejected' => Report::whereIn('student_id', $studentMatricules)
                ->where('status', 'REFUSED')
                ->count(),
            'validated' => Report::whereIn('student_id', $studentMatricules)
                ->where('status', 'DONE')
                ->count(),
            'total' => Report::whereIn('student_id', $studentMatricules)
                ->count()
        ];
    }

    private function getPatientsStats($studentMatricules)
    {


        return [
            'notValidated' => Patient::whereIn('matricule', $studentMatricules)
                ->where('valider', 0)
                ->count(),
            'validated' => Patient::whereIn('matricule', $studentMatricules)
                ->where('valider', 1)
                ->count(),
            'deleted' => Patient::whereIn('matricule', $studentMatricules)
                ->where('valider', 2)
                ->count(),
            'total' => Patient::whereIn('matricule', $studentMatricules)
                ->count()
        ];
    }

    private function getTotalStudents($studentMatricules)
    {

        return Student::whereIn('matricule', $studentMatricules)->count();
    }

    public function index(Officer $id)
    {
        $officer = $id;

        // Get base query for students based on officer role
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        // Get statistics
        $weekendStats = $this->getWeekendStats($studentMatricules);
        $sanctionsStats = $this->getSanctionsStats($studentMatricules);
        $patineStats = $this->getPatineStats($studentMatricules);
        $patientsStats = $this->getPatientsStats($studentMatricules);


        $totalStudents = $this->getTotalStudents($studentMatricules);

        return view('brigade.statistics', compact(
            'weekendStats',
            'sanctionsStats',

            'patientsStats',
            'totalStudents',
            'officer'
        ));
    }

    public function filter(Request $request, Officer $id)
    {
        try {
            $grade = $request->query('grade');
            $officer = $id;

            // Get base query for students based on officer role
            $baseStudentsQuery = $this->getStudentsQueryByRole($officer);

            // If a specific grade is selected, add grade filter
            if ($grade !== 'all') {
                $baseStudentsQuery->where('grade', $grade);
            }

            // Get student matricules for filtered students
            $studentMatricules = $baseStudentsQuery->pluck('matricule');

            // Get all statistics
            $stats = [
                'weekendStats' => $this->getWeekendStats($studentMatricules),
                'sanctionsStats' => $this->getSanctionsStats($studentMatricules),
                'patineStats' => $this->getPatineStats($studentMatricules),
                'patientsStats' => $this->getPatientsStats($studentMatricules),
                'totalStudents' => $this->getTotalStudents($studentMatricules)
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Erreur lors du filtrage des statistiques: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    public function weekendDetails(Officer $id)
    {
        $officer = $id;
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        // Get sortie records (weekend permissions/exits) instead of just student choices
        $sorties = Sortie::whereIn('student_id', $studentMatricules)
            ->with(['student.section'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('brigade.weekend-details', compact('officer', 'sorties'));
    }

    public function sanctionsDetails(Officer $id)
    {
        $officer = $id;
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        $sanctions = Sanction::whereIn('matricule', $studentMatricules)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('brigade.sanctions-details', compact('officer', 'sanctions'));
    }

    public function reportsDetails(Officer $id)
    {
        $officer = $id;
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        $reports = Report::whereIn('student_id', $studentMatricules)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('brigade.reports-details', compact('officer', 'reports'));
    }

    public function studentsDetails(Officer $id)
    {
        $officer = $id;
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $students = $studentsQuery->with(['section'])->get();

        return view('brigade.students-details', compact('officer', 'students'));
    }

    public function patientsDetails(Officer $id)
    {
        $officer = $id;
        $studentsQuery = $this->getStudentsQueryByRole($officer);
        $studentMatricules = $studentsQuery->pluck('matricule');

        $patients = Patient::whereIn('matricule', $studentMatricules)
            ->with(['student.section'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('brigade.patients-details', compact('officer', 'patients'));
    }

    public function getGraphData(Request $request, Officer $id)
    {
        try {
            $officer = $id;

            // Handle both POST JSON and GET query parameters
            if ($request->isMethod('post')) {
                $type = $request->input('type');
                $from = $request->input('from');
                $to = $request->input('to');
                $unit = $request->input('unit');
            } else {
                $type = $request->query('type');
                $from = $request->query('from');
                $to = $request->query('to');
                $unit = $request->query('unit');
            }

            // Validate required parameters
            if (!$type || !$from || !$to || !$unit) {
                Log::error('Graph data request missing parameters', [
                    'type' => $type,
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
                Log::error('Invalid date format in graph request', [
                    'from' => $from,
                    'to' => $to,
                    'error' => $e->getMessage()
                ]);
                return response()->json(['error' => 'Invalid date format'], 400);
            }

            $studentsQuery = $this->getStudentsQueryByRole($officer);
            $studentMatricules = $studentsQuery->pluck('matricule');

            Log::info('Generating graph data', [
                'type' => $type,
                'from' => $from,
                'to' => $to,
                'unit' => $unit,
                'student_count' => $studentMatricules->count()
            ]);

            $data = $this->generateGraphData($type, $from, $to, $unit, $studentMatricules);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error generating graph data: ' . $e->getMessage(), [
                'officer_id' => $id->id ?? 'unknown',
                'type' => $request->input('type') ?? $request->query('type'),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to generate graph data'], 500);
        }
    }

    private function generateGraphData($type, $from, $to, $unit, $studentMatricules)
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
                    $count = $this->getCountForDate($type, $current, $studentMatricules);
                    $data[] = $count;
                    $current->addDay();
                }
                break;

            case 'weeks':
                $current = $fromDate->copy()->startOfWeek();
                while ($current->lte($toDate)) {
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('d/m') . ' - ' . $weekEnd->format('d/m');
                    $count = $this->getCountForPeriod($type, $current, $weekEnd, $studentMatricules);
                    $data[] = $count;
                    $current->addWeek();
                }
                break;

            case 'months':
                $current = $fromDate->copy()->startOfMonth();
                while ($current->lte($toDate)) {
                    $labels[] = $current->format('M Y');
                    $monthEnd = $current->copy()->endOfMonth();
                    $count = $this->getCountForPeriod($type, $current, $monthEnd, $studentMatricules);
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

    private function getCountForDate($type, $date, $studentMatricules)
    {
        switch ($type) {
            case 'weekend':
                // Count actual sorties from the sortie table for the given date
                return Sortie::whereIn('student_id', $studentMatricules)
                    ->whereDate('created_at', $date)
                    ->count();

            case 'sanctions':
                return Sanction::whereIn('matricule', $studentMatricules)
                    ->whereDate('created_at', $date)
                    ->count();

            case 'reports':
                return Report::whereIn('student_id', $studentMatricules)
                    ->whereDate('created_at', $date)
                    ->count();

            case 'patients':
                return Patient::whereIn('matricule', $studentMatricules)
                    ->whereDate('created_at', $date)
                    ->count();

            default:
                return 0;
        }
    }

    private function getCountForPeriod($type, $start, $end, $studentMatricules)
    {
        switch ($type) {
            case 'weekend':
                // Count actual sorties from the sortie table for the given period
                return Sortie::whereIn('student_id', $studentMatricules)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

            case 'sanctions':
                return Sanction::whereIn('matricule', $studentMatricules)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

            case 'reports':
                return Report::whereIn('student_id', $studentMatricules)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

            case 'patients':
                return Patient::whereIn('matricule', $studentMatricules)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

            default:
                return 0;
        }
    }
}
