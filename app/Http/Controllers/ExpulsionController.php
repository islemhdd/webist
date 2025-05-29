<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expulsion;
use App\Models\Student;

class ExpulsionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $grade = $request->get('grade', 'all');

        // Toujours utiliser uniquement la date d'aujourd'hui
        $today = now()->toDateString();

        // Filtre par matricule
        $matriculeFilter = $request->get('matricule');

        $query = Expulsion::with(['student'])
            ->orderBy('date_expulsion', 'desc');

        // Filtrer par aujourd'hui uniquement
        $query->whereDate('date_expulsion', '=', $today);

        // Filtrer par matricule si fourni
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

        // Filtrer par grade si spécifié
        if ($grade !== 'all' && !empty($grade)) {
            $query->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $expulsions = $query->paginate(20);

        // Calculate statistics for each grade avec la date d'aujourd'hui uniquement
        // Appliquer les mêmes filtres que pour la requête principale (sauf le grade pour les stats)
        $baseStatsQuery = Expulsion::whereDate('date_expulsion', '=', $today);

        // Appliquer le filtre matricule aux statistiques aussi
        if ($matriculeFilter) {
            $baseStatsQuery->where(function($q) use ($matriculeFilter) {
                $q->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                  ->orWhereHas('student', function($subQ) use ($matriculeFilter) {
                      $subQ->where('matricule', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('nom', 'LIKE', '%' . $matriculeFilter . '%')
                           ->orWhere('prenom', 'LIKE', '%' . $matriculeFilter . '%');
                  });
            });
        }

        // Statistiques par grade avec les filtres appliqués
        $statsQuery1 = clone $baseStatsQuery;
        $statsQuery1->whereHas('student', function($q) {
            $q->where('grade', 1);
        });

        $statsQuery2 = clone $baseStatsQuery;
        $statsQuery2->whereHas('student', function($q) {
            $q->where('grade', 2);
        });

        $statsQuery3 = clone $baseStatsQuery;
        $statsQuery3->whereHas('student', function($q) {
            $q->where('grade', 3);
        });

        $grade1Count = $statsQuery1->count();
        $grade2Count = $statsQuery2->count();
        $grade3Count = $statsQuery3->count();

        $statistics = [
            'grade_1' => $grade1Count,
            'grade_2' => $grade2Count,
            'grade_3' => $grade3Count,
            'total' => $grade1Count + $grade2Count + $grade3Count
        ];

        // Get students for the modal form
        $students = Student::whereNull('deleted_at')
            ->orderBy('nom')
            ->get();

        return view('de.expulsions.index', compact('expulsions', 'grade', 'statistics', 'students', 'matriculeFilter'));
    }

    public function create()
    {
        $students = Student::whereNull('deleted_at')
            ->orderBy('nom')
            ->get();

        return view('de.expulsions.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'raison' => 'required|string|max:255'
        ]);

        // Get student's matricule
        $student = Student::findOrFail($request->student_id);

        // Create expulsion with properly mapped fields
        Expulsion::create([
            'matricule' => $student->matricule,
            'motif_expulsion' => $request->raison,
            'date_expulsion' => now(),
            'description' => $request->raison
        ]);

        return redirect()->route('de.expulsions.index')
            ->with('success', 'Expulsion enregistrée avec succès');
    }

    public function show($id)
    {
        $expulsion = Expulsion::with('student')->findOrFail($id);
        return view('de.expulsions.show', compact('expulsion'));
    }

    public function edit($id)
    {
        $expulsion = Expulsion::with('student')->findOrFail($id);

        // For API requests, return JSON with both student and expulsion data
        if (request()->expectsJson()) {
            return response()->json($expulsion);
        }

        $students = Student::whereNull('deleted_at')
            ->orderBy('nom')
            ->get();

        return view('de.expulsions.edit', compact('expulsion', 'students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'raison' => 'required|string|max:255'
        ]);

        // Get student's matricule
        $student = Student::findOrFail($request->student_id);

        $expulsion = Expulsion::findOrFail($id);
        $expulsion->update([
            'matricule' => $student->matricule,
            'motif_expulsion' => $request->raison,
            'date_expulsion' => $expulsion->date_expulsion ?: now(),
            'description' => $request->raison
        ]);

        return redirect()->route('de.expulsions.index')
            ->with('success', 'Expulsion mise à jour avec succès');
    }

    public function destroy($id)
    {
        $expulsion = Expulsion::findOrFail($id);
        $expulsion->delete();

        return redirect()->route('de.expulsions.index')
            ->with('success', 'Expulsion supprimée avec succès');
    }

    public function getStatistics(Request $request)
    {
        $grade = $request->get('grade', 'all');

        $query = Expulsion::query();

        if ($grade !== 'all') {
            $query->whereHas('student', function($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        $stats = $query->selectRaw('motif_expulsion, COUNT(*) as count')
            ->groupBy('motif_expulsion')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json($stats);
    }
}
