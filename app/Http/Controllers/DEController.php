<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Expulsion;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class DEController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $grade = $request->get('grade', 'all');

        // Utiliser uniquement la date d'aujourd'hui
        $today = now()->toDateString();

        // Statistiques pour les graphiques avec la date d'aujourd'hui seulement
        $infirmaryStats = $this->getInfirmaryStats($grade, $today, $today);
        $expulsionStats = $this->getExpulsionStats($grade, $today, $today);

        // Prepare expulsions by reason for chart
        $expulsionsByReason = [];
        foreach ($expulsionStats as $expulsion) {
            $expulsionsByReason[$expulsion->motif_expulsion] = $expulsion->count;
        }

        // Prepare stats array for the dashboard cards
        $stats = [
            'total_infirmerie' => $infirmaryStats['total'],
            'rhp_validated' => $infirmaryStats['validated'],
            'rhp_pending' => $infirmaryStats['non_validated'],
            'total_expulsions' => $expulsionStats->sum('count'),
            'expulsions_by_reason' => $expulsionsByReason
        ];

        return view('de.dashboard', compact('infirmaryStats', 'expulsionStats', 'grade', 'stats'));
    }

    public function infirmerie(Request $request)
    {
        $grade = $request->get('grade', 'all');
        $status = $request->get('status');

        // Toujours utiliser uniquement la date d'aujourd'hui
        $today = now()->toDateString();

        // Filtre par matricule
        $matriculeFilter = $request->get('matricule');

        // Montrer tous les patients (valider = 0, 1, 2)
        $query = Patient::with(['student'])
            ->whereIn('valider', [0, 1, 2]);

        // Filtrer par date (toujours aujourd'hui uniquement)
        $query->whereDate('created_at', '=', $today);

        // Filtrer par matricule avec recherche étendue
        if ($matriculeFilter) {
            $query->where(function($q) use ($matriculeFilter) {
                $q->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                  ->orWhereHas('student', function($subQ) use ($matriculeFilter) {
                      $subQ->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('nom', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('prenom', 'LIKE', '%' . $matriculeFilter . '%');
                  });
            });
        }

        // Filtrer par statut RHP si demandé
        if ($status !== null) {
            $query->where('valider_rhp', $status);
        }

        // Filtrer par grade si spécifié
        if ($grade !== 'all' && !empty($grade)) {
            $query->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate stats for all patients avec les mêmes filtres
        $allPatientsQuery = Patient::whereIn('valider', [0, 1, 2]);

        // Filtrer par aujourd'hui uniquement pour les statistiques
        $allPatientsQuery->whereDate('created_at', '=', $today);

        // Appliquer le filtre matricule pour les statistiques avec recherche étendue
        if ($matriculeFilter) {
            $allPatientsQuery->where(function($q) use ($matriculeFilter) {
                $q->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                  ->orWhereHas('student', function($subQ) use ($matriculeFilter) {
                      $subQ->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('nom', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('prenom', 'LIKE', '%' . $matriculeFilter . '%');
                  });
            });
        }

        // Filtrer par grade si spécifié pour les statistiques
        if ($grade !== 'all' && !empty($grade)) {
            $allPatientsQuery->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        // Calcul du nombre total de patients
        $totalAll = $allPatientsQuery->count();

        // Statistiques distinctes avec les mêmes filtres
        $pendingQuery = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 0);
        $validatedQuery = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 1);

        // Filtrer par aujourd'hui uniquement pour les statistiques
        $pendingQuery->whereDate('created_at', '=', $today);
        $validatedQuery->whereDate('created_at', '=', $today);

        // Appliquer le filtre matricule avec recherche étendue
        if ($matriculeFilter) {
            $pendingQuery->where(function($q) use ($matriculeFilter) {
                $q->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                  ->orWhereHas('student', function($subQ) use ($matriculeFilter) {
                      $subQ->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('nom', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('prenom', 'LIKE', '%' . $matriculeFilter . '%');
                  });
            });
            $validatedQuery->where(function($q) use ($matriculeFilter) {
                $q->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                  ->orWhereHas('student', function($subQ) use ($matriculeFilter) {
                      $subQ->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('nom', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('prenom', 'LIKE', '%' . $matriculeFilter . '%');
                  });
            });
        }

        if ($grade !== 'all' && !empty($grade)) {
            $pendingQuery->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });

            $validatedQuery->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $pending = $pendingQuery->count();
        $validated = $validatedQuery->count();

        // Logging pour le débogage
        \Log::info("Infirmerie stats: Total=$totalAll, Pending=$pending, Validated=$validated");

        $stats = [
            'pending' => $pending,
            'validated' => $validated,
            'total' => $totalAll,
            'deleted' => 0
        ];

        return view('de.infirmerie.index', compact('patients', 'grade', 'stats', 'matriculeFilter'));
    }

    public function infirmaryAbsences(Request $request)
    {
        $grade = $request->get('grade', 'all');

        $query = Patient::with(['student'])
            ->whereIn('valider', [0, 1, 2]);

        if ($grade !== 'all') {
            $query->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('de.infirmary-absences', compact('patients', 'grade'));
    }

    public function validateRHP(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        // Vérifier que le patient a un statut valider = 1 ou 2 (validé par l'infirmerie)
        if (!in_array($patient->valider, [1, 2])) {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les patients validés par l\'infirmerie (statut 1 ou 2) peuvent être validés par le RHP'
            ], 422);
        }

        $patient->update([
            'valider_rhp' => 1 // Marquer comme validé par RHP
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absence validée par RHP avec succès'
        ]);
    }

    public function classExpulsions(Request $request)
    {
        $grade = $request->get('grade', 'all');

        $query = Expulsion::with(['student'])
            ->orderBy('date_expulsion', 'desc');

        if ($grade !== 'all') {
            $query->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $expulsions = $query->paginate(20);

        return view('de.class-expulsions', compact('expulsions', 'grade'));
    }

    private function getInfirmaryStats($grade = 'all', $today = null, $unused = null, $matricule = null)
    {
        \Log::info("getInfirmaryStats called with params:", [
            'grade' => $grade,
            'today' => $today,
            'matricule' => $matricule
        ]);

        // Ne compter que les patients validés par l'infirmerie (valider = 1 ou 2)
        // Requêtes distinctes pour éviter les problèmes de cache
        $validatedQuery = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 1);
        $nonValidatedQuery = Patient::whereIn('valider', [1, 2])->where('valider_rhp', 0);

        // Appliquer le filtre de date pour aujourd'hui seulement
        if ($today) {
            $validatedQuery->whereDate('created_at', '=', $today);
            $nonValidatedQuery->whereDate('created_at', '=', $today);
        } else {
            // Par défaut, utiliser la date d'aujourd'hui
            $today = now()->toDateString();
            $validatedQuery->whereDate('created_at', '=', $today);
            $nonValidatedQuery->whereDate('created_at', '=', $today);
        }

        // Filtre par matricule
        if ($matricule) {
            $validatedQuery->where('matricule', 'LIKE', '%' . $matricule . '%');
            $nonValidatedQuery->where('matricule', 'LIKE', '%' . $matricule . '%');
        }

        if ($grade !== 'all') {
            $validatedQuery->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });

            $nonValidatedQuery->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $validated = $validatedQuery->count();
        $nonValidated = $nonValidatedQuery->count();
        $total = $validated + $nonValidated;

        // Logging pour le débogage
        \Log::info("Infirmary stats result:", [
            'validated' => $validated,
            'non_validated' => $nonValidated,
            'total' => $total,
            'grade' => $grade,
            'today' => $today,
            'matricule' => $matricule
        ]);

        return [
            'validated' => $validated,
            'non_validated' => $nonValidated,
            'total' => $total
        ];
    }

    private function getExpulsionStats($grade = 'all', $today = null, $unused = null, $matricule = null)
    {
        \Log::info("getExpulsionStats called with params:", [
            'grade' => $grade,
            'today' => $today,
            'matricule' => $matricule
        ]);

        $query = Expulsion::select('motif_expulsion', DB::raw('count(*) as count'))
            ->groupBy('motif_expulsion');

        // Appliquer le filtre de date pour aujourd'hui seulement
        if ($today) {
            $query->whereDate('date_expulsion', '=', $today);
        } else {
            // Par défaut, utiliser la date d'aujourd'hui
            $today = now()->toDateString();
            $query->whereDate('date_expulsion', '=', $today);
        }

        // Filtre par matricule
        if ($matricule) {
            $query->where('matricule', 'LIKE', '%' . $matricule . '%');
        }

        if ($grade !== 'all') {
            $query->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $result = $query->orderBy('count', 'desc')->get();

        // Log des résultats d'expulsions
        \Log::info("Expulsion stats result:", [
            'result_count' => $result->count(),
            'result_data' => $result->toArray(),
            'grade' => $grade,
            'today' => $today,
            'matricule' => $matricule
        ]);

        return $result;
    }

    public function getStatisticsData(Request $request)
    {
        $grade = $request->get('grade', 'all');
        $today = now()->toDateString();
        $matricule = $request->get('matricule');

        // Log incoming request for debugging
        \Log::info("Statistics data request received:", [
            'grade' => $grade,
            'today' => $today,
            'matricule' => $matricule,
            'all_params' => $request->all(),
            'route' => $request->route() ? $request->route()->getName() : 'unknown',
            'url' => $request->fullUrl()
        ]);

        // Statistiques infirmerie avec la date d'aujourd'hui seulement
        $infirmaryStats = $this->getInfirmaryStats($grade, $today, $today, $matricule);

        // Statistiques expulsions avec la date d'aujourd'hui seulement
        $expulsionStats = $this->getExpulsionStats($grade, $today, $today, $matricule);

        // Préparer les données d'expulsions pour le graphique
        $expulsionsByReason = [];
        $totalExpulsions = 0;
        
        foreach ($expulsionStats as $expulsion) {
            $expulsionsByReason[$expulsion->motif_expulsion] = $expulsion->count;
            $totalExpulsions += $expulsion->count;
        }

        // Log du résultat final pour debug
        \Log::info("Final statistics result:", [
            'infirmary_stats' => $infirmaryStats,
            'expulsions_by_reason' => $expulsionsByReason,
            'total_expulsions' => $totalExpulsions
        ]);

        return response()->json([
            'infirmary' => [
                'validated' => $infirmaryStats['validated'] ?? 0,
                'non_validated' => $infirmaryStats['non_validated'] ?? 0,
                'total' => $infirmaryStats['total'] ?? 0
            ],
            'expulsions' => $expulsionsByReason,
            'total_expulsions' => $totalExpulsions
        ]);
    }
}
