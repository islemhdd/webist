<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RHP;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class RHPController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Always show current week only (Sunday to Thursday)
        // Force Sunday as start of week (Carbon::SUNDAY = 0)
        $currentWeekStart = now()->startOfWeek(Carbon::SUNDAY); // Sunday
        $currentWeekEnd = now()->startOfWeek(Carbon::SUNDAY)->addDays(4); // Thursday

        // Récupérer les assignations de la semaine courante uniquement
        $rhps = RHP::with('officer')
            ->whereBetween('date_assignation', [$currentWeekStart, $currentWeekEnd])
            ->orderBy('date_assignation')
            ->get();

        // Generate the current week days (Sunday to Thursday only)
        $currentWeekDays = [];
        for ($i = 0; $i <= 4; $i++) { // 0 = Sunday, 4 = Thursday
            $currentWeekDays[] = $currentWeekStart->copy()->addDays($i);
        }

        // Organiser les assignations par date pour l'affichage
        $assignments = [];
        foreach ($currentWeekDays as $day) {
            $dateKey = $day->format('Y-m-d');
            $assignments[$dateKey] = [
                'date' => $day->copy(),
                'matin' => $rhps->where('date_assignation', $dateKey)->where('periode', 'matin')->first(),
                'apres_midi' => $rhps->where('date_assignation', $dateKey)->where('periode', 'apres_midi')->first(),
            ];
        }

        // Récupérer tous les officiers autorisés pour RHP
        $officers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['Chef de compagnie']); // Rôles autorisés pour RHP
        })->orderBy('username')->get();

        return view('de.rhp.index', compact('rhps', 'assignments', 'officers', 'currentWeekStart', 'currentWeekEnd', 'currentWeekDays'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'officer_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'period' => 'required|in:matin,apres_midi',
                'notes' => 'nullable|string|max:500'
            ], [
                'officer_id.required' => 'Veuillez sélectionner un officier responsable.',
                'officer_id.exists' => 'L\'officier sélectionné n\'est pas valide.',
                'date.required' => 'La date d\'assignation est requise.',
                'date.date' => 'La date fournie n\'est pas valide.',
                'period.required' => 'La période est requise.',
                'period.in' => 'La période doit être matin ou après-midi.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Convert form field names to database field names
            $date_assignation = $request->date;
            $periode = $request->period; // Now using French values directly

            // Only allow assignments for Sunday to Thursday of the current week
            $assignmentDate = Carbon::parse($date_assignation);
            $startOfWeek = now()->startOfWeek(Carbon::SUNDAY); // Sunday
            $endOfWeek = now()->startOfWeek(Carbon::SUNDAY)->addDays(4); // Thursday

            if ($assignmentDate->lt($startOfWeek) || $assignmentDate->gt($endOfWeek)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['date' => ['Vous ne pouvez assigner des RHP que pour la semaine en cours (Dimanche à Jeudi).']]
                ], 422);
            }

            // Vérifier s'il y a déjà une assignation pour cette date et période
            $existing = RHP::where('date_assignation', $date_assignation)
                ->where('periode', $periode)
                ->first();

            if ($existing) {
                // Mettre à jour l'assignation existante
                $existing->update([
                    'officer_id' => $request->officer_id,
                    'notes' => $request->notes
                ]);
                $message = 'Assignation mise à jour avec succès';
            } else {
                // Créer une nouvelle assignation
                RHP::create([
                    'officer_id' => $request->officer_id,
                    'date_assignation' => $date_assignation,
                    'periode' => $periode,
                    'notes' => $request->notes
                ]);
                $message = 'Assignation créée avec succès';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('RHP Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'officer_id' => 'required|exists:users,id',
                'date' => 'nullable|date',
                'period' => 'nullable|in:matin,apres_midi',
                'notes' => 'nullable|string|max:500'
            ], [
                'officer_id.required' => 'Veuillez sélectionner un officier responsable.',
                'officer_id.exists' => 'L\'officier sélectionné n\'est pas valide.',
                'date.date' => 'La date fournie n\'est pas valide.',
                'period.in' => 'La période doit être matin ou après-midi.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $rhp = RHP::findOrFail($id);

            $updateData = ['officer_id' => $request->officer_id];

            // Only update date and period if they are provided
            if ($request->filled('date')) {
                $updateData['date_assignation'] = $request->date;
            }

            if ($request->filled('period')) {
                $updateData['periode'] = $request->period; // Now using French values directly
            }

            if ($request->filled('notes')) {
                $updateData['notes'] = $request->notes;
            }

            $rhp->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Assignation mise à jour avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('RHP Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour'
            ], 500);
        }
    }

    public function edit($id)
    {
        $rhp = RHP::findOrFail($id);

        return response()->json([
            'id' => $rhp->id,
            'officer_id' => $rhp->officer_id,
            'date' => $rhp->date_assignation->format('Y-m-d'),
            'period' => $rhp->periode, // Return French values directly
            'notes' => $rhp->notes
        ]);
    }

    public function weekView()
    {
        // Get the current week's date range (Sunday to Thursday)
        $startDate = now()->startOfWeek(Carbon::SUNDAY); // Sunday
        $endDate = now()->startOfWeek(Carbon::SUNDAY)->addDays(4); // Thursday

        // Récupérer les assignations de la semaine
        $rhps = RHP::with('officer')
            ->whereBetween('date_assignation', [$startDate, $endDate])
            ->orderBy('date_assignation')
            ->get()
            ->map(function($rhp) {
                // Transform periode values to match the view expectations
                $rhp->period = $rhp->periode === 'matin' ? 'morning' : 'afternoon';
                return $rhp;
            });

        // Organiser les assignations par date
        $assignments = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateKey = $current->format('Y-m-d');
            $assignments[$dateKey] = [
                'date' => $current->copy(),
                'matin' => $rhps->where('date_assignation', $dateKey)->where('periode', 'matin')->first(),
                'apres_midi' => $rhps->where('date_assignation', $dateKey)->where('periode', 'apres_midi')->first(),
            ];
            $current->addDay();
        }

        return view('de.rhp.week-view', compact('assignments', 'startDate', 'endDate'));
    }

    public function destroy($id)
    {
        try {
            $rhp = RHP::findOrFail($id);
            $rhp->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assignation supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('RHP Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression'
            ], 500);
        }
    }
}
