<?php

namespace App\Http\Controllers;

use App\Models\Officer;
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
            case 'Chef de compagnie':
                // Students in the officer's sections
                return $query->whereIn('section_id', function ($q) use ($officer) {
                    $q->select('id')
                        ->from('sections')
                        ->where('officer_id', $officer->id);
                });

            case 'Chef de brigade':
                // Students in the same battalion (grade)
                return $query->where('grade', $officer->bat);

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
            ->where('type', 'consigne')
            ->where(function ($q) use ($weekendStart, $weekendEnd) {
                $q->whereDate('date_debut', '<=', $weekendEnd)
                    ->whereDate('date_fin', '>=', $weekendStart);
            })->count();

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

        $totalStudents = $this->getTotalStudents($studentMatricules);

        return view('brigade.statistics', compact(
            'weekendStats',
            'sanctionsStats',
            'patineStats',
            'totalStudents',
            'officer'
        ));
    }

    public function filter(Request $request)
    {
        try {
            $grade = $request->query('grade');
            $officer = auth()->user()->officer;

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
                'totalStudents' => $this->getTotalStudents($studentMatricules)
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du filtrage des statistiques: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }
}
