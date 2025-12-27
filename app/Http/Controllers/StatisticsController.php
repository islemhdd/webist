<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ListeRdv;
use App\Models\Convoncu;
use App\Models\Exemption;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Déterminer la date à utiliser (par défaut aujourd'hui)
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = Carbon::parse($request->date_fin);
        } elseif ($request->filled('date_debut')) {
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = $dateDebut->copy();
        } elseif ($request->filled('date_fin')) {
            $dateFin = Carbon::parse($request->date_fin);
            $dateDebut = $dateFin->copy();
        } else {
            $dateDebut = Carbon::today();
            $dateFin = Carbon::today();
        }

        // Filtre par matricule si spécifié
        $matriculeFilter = $request->filled('search_matricule') ? $request->search_matricule : null;

     

     

      

        // Récupérer les statistiques des rendez-vous
        $consultationRdvQuery = ListeRdv::whereBetween(\DB::raw('DATE(date)'), [$dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d')])
            ->where('motif', 'consultation');
        $urgenceRdvQuery = ListeRdv::whereBetween(\DB::raw('DATE(date)'), [$dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d')])
            ->where('motif', 'urgences');

        if ($matriculeFilter) {
            $consultationRdvQuery->where('matricule', 'LIKE', '%' . $matriculeFilter . '%');
            $urgenceRdvQuery->where('matricule', 'LIKE', '%' . $matriculeFilter . '%');
        }

        $consultationRdvToday = $consultationRdvQuery->count();
        $urgenceRdvToday = $urgenceRdvQuery->count();

        // Récupérer les statistiques des convocations
        $convocationsWithPsyQuery = Convoncu::whereNotNull('psy');
        $convocationsWithoutPsyQuery = Convoncu::whereNull('psy');

        if ($matriculeFilter) {
            $convocationsWithPsyQuery->where('matricule', 'LIKE', '%' . $matriculeFilter . '%');
            $convocationsWithoutPsyQuery->where('matricule', 'LIKE', '%' . $matriculeFilter . '%');
        }

        $convocationsWithPsy = $convocationsWithPsyQuery->count();
        $convocationsWithoutPsy = $convocationsWithoutPsyQuery->count();

        // Récupérer les statistiques des exemptions par motif
        $exemptionsTodayQuery = Exemption::whereBetween(\DB::raw('DATE(date_debut)'), [$dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d')]);

        if ($matriculeFilter) {
            $exemptionsTodayQuery->where('matricule', 'LIKE', '%' . $matriculeFilter . '%');
        }

        $exemptionsToday = $exemptionsTodayQuery->selectRaw('motif, COUNT(*) as count')
            ->groupBy('motif')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'consultationRdvToday' => $consultationRdvToday,
                'urgenceRdvToday' => $urgenceRdvToday,
                'convocationsWithPsy' => $convocationsWithPsy,
                'convocationsWithoutPsy' => $convocationsWithoutPsy,
                'exemptionsToday' => $exemptionsToday,
            ]);
        }

        return view('statistics.index', compact(
          
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
