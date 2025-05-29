<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\ListeRdv;
use App\Models\Convoncu;
use App\Models\Exemption;
use App\Models\Student;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Récupérer les statistiques des patients pour aujourd'hui
        $validPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 1)->count();
        $invalidPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 0)->count();

        // Récupérer les statistiques des rendez-vous pour aujourd'hui
        $consultationRdvToday = ListeRdv::whereDate('date', $today)
            ->where('motif', 'consultation')->count();
        $urgenceRdvToday = ListeRdv::whereDate('date', $today)
            ->where('motif', 'urgences')->count();

        // Récupérer les statistiques des convocations (totales, pas seulement aujourd'hui)
        $convocationsWithPsy = Convoncu::whereNotNull('psy')->count();
        $convocationsWithoutPsy = Convoncu::whereNull('psy')->count();

        // Récupérer les statistiques des exemptions par motif pour aujourd'hui
        $exemptionsToday = Exemption::whereDate('date_debut', $today)
            ->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get();

        return view('statistics.index', compact(
            'validPatientsToday',
            'invalidPatientsToday',
            'consultationRdvToday',
            'urgenceRdvToday',
            'convocationsWithPsy',
            'convocationsWithoutPsy',
            'exemptionsToday'
        ));
    }

    /**
     * Filtrer les statistiques par grade (année d'étude)
     */
    public function filter(Request $request)
    {
        try {
            $grade = $request->query('grade');
            $today = Carbon::today();

            // Validation du grade
            if (!in_array($grade, ['all', '1', '2', '3'])) {
                return response()->json(['error' => 'Grade invalide'], 400);
            }

            // Si "all" est sélectionné, on récupère toutes les données
            if ($grade === 'all') {
                return $this->getAllStatistics($today);
            }

            // Filtrer par grade spécifique
            return $this->getStatisticsByGrade($today, $grade);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du filtrage des statistiques: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Récupérer toutes les statistiques sans filtrage
     */
    private function getAllStatistics($today)
    {
        $validPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 1)->count();
        $invalidPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 0)->count();
        // $invalidPatientsToday = Patient::whereDate('created_at', $today)
        //     ->where('valider', 2)->count();

        $consultationRdvToday = ListeRdv::whereDate('date', $today)
            ->where('motif', 'consultation')->count();
        $urgenceRdvToday = ListeRdv::whereDate('date', $today)
            ->where('motif', 'urgences')->count();

        $convocationsWithPsy = Convoncu::whereNotNull('psy')->count();
        $convocationsWithoutPsy = Convoncu::whereNull('psy')->count();

        $exemptionsToday = Exemption::whereDate('date_debut', $today)
            ->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get()
            ->map(function ($item) {
                return [
                    'motif' => ucfirst($item->motif),
                    'count' => $item->count
                ];
            });

        return response()->json([
            'validPatientsToday' => $validPatientsToday,
            'invalidPatientsToday' => $invalidPatientsToday,
            'consultationRdvToday' => $consultationRdvToday,
            'urgenceRdvToday' => $urgenceRdvToday,
            'convocationsWithPsy' => $convocationsWithPsy,
            'convocationsWithoutPsy' => $convocationsWithoutPsy,
            'exemptionsToday' => $exemptionsToday
        ]);
    }

    /**
     * Récupérer les statistiques filtrées par grade
     */
    private function getStatisticsByGrade($today, $grade)
    {
        // Récupérer les matricules des étudiants du grade spécifié
        $studentMatricules = Student::where('grade', $grade)->pluck('matricule');

        // Patients filtrés par grade
        $validPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 1)
            ->whereIn('matricule', $studentMatricules)
            ->count();

        $invalidPatientsToday = Patient::whereDate('created_at', $today)
            ->where('valider', 0)
            ->whereIn('matricule', $studentMatricules)
            ->count();

        // Rendez-vous filtrés par grade
        $consultationRdvToday = ListeRdv::whereDate('date', $today)
            ->where('motif', 'consultation')
            ->whereIn('matricule', $studentMatricules)
            ->count();

        $urgenceRdvToday = ListeRdv::whereDate('date', $today)
            ->where('motif', 'urgences')
            ->whereIn('matricule', $studentMatricules)
            ->count();

        // Convocations filtrées par grade
        $convocationsWithPsy = Convoncu::whereNotNull('psy')
            ->whereIn('matricule', $studentMatricules)
            ->count();

        $convocationsWithoutPsy = Convoncu::whereNull('psy')
            ->whereIn('matricule', $studentMatricules)
            ->count();

        // Exemptions filtrées par grade
        $exemptionsToday = Exemption::whereDate('date_debut', $today)
            ->whereIn('matricule', $studentMatricules)
            ->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get()
            ->map(function ($item) {
                return [
                    'motif' => ucfirst($item->motif),
                    'count' => $item->count
                ];
            });

        return response()->json([
            'validPatientsToday' => $validPatientsToday,
            'invalidPatientsToday' => $invalidPatientsToday,
            'consultationRdvToday' => $consultationRdvToday,
            'urgenceRdvToday' => $urgenceRdvToday,
            'convocationsWithPsy' => $convocationsWithPsy,
            'convocationsWithoutPsy' => $convocationsWithoutPsy,
            'exemptionsToday' => $exemptionsToday
        ]);
    }
}
