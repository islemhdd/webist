<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Sanction;
use App\Models\Student;
use App\Models\Patient;
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
            case 'CC': // Chef de compagnie - show only their sections
                return $query->whereIn('section_id', function ($q) use ($officer) {
                    $q->select('id')
                        ->from('sections')
                        ->where('officer_id', $officer->id);
                });
            case 'CBt': // Chef de bataillon - show students in their battalion
                return $query->where('grade', $officer->bat);
            case 'CBr': // Chef de brigade
            case 'DIV': // Division
                return $query; // Show all students
            default:
                return $query->whereRaw('1 = 0'); // Return empty query for other roles
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
        $now = now();
        $weekendStart = $now->copy()->endOfWeek()->subDay(); // Saturday
        $weekendEnd = $now->copy()->endOfWeek(); // Sunday

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
            ->where('date_fin', '>=', $now)
            ->count();

        // Get past arrests (already finished)
        $pastArrests = Sanction::whereIn('matricule', $studentMatricules)
            ->where('type', 'arret')
            ->where('date_fin', '<', $now)
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
            'pending' => Patient::whereIn('matricule', $studentMatricules)
                ->where('valider', 0)
                ->count(),
            'rejected' => Patient::whereIn('matricule', $studentMatricules)
                ->where('valider', 2)
                ->count(),
            'validated' => Patient::whereIn('matricule', $studentMatricules)
                ->where('valider', 1)
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
