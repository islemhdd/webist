<?php

namespace App\Http\Controllers;

use App\Events\SortieLocked;
use App\Events\SortieUpdated;
use App\Models\Officer;
use App\Models\Sortie;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashbaordController extends Controller
{   //hadoma ghir ll officers mazal madrtch ta3 les etudiantes


    public function parametre($id)
    {
        todo("ajoute une page pour les parmettre");
        return view('brigade.parametre');
    }
    public function principale($id)
    {

        return view('brigade.principale');
    }

    public function weekends(Officer $id, Request $request)
    {   //started working , just need to find all the     students in the sections

        $officer = $id;
        $isCC = false;

        $locked = [];
        $lockStatus = (bool) DB::table('list_lock')->where('id', $officer->bat)->value('status');

        if ($officer->role->name == 'Chef de compagnie') // chef companie
        {
            $isCC = true;
            $locked = $lockStatus ? 'locked' : '';

            $students = Student::whereIn('section_id', function ($query) use ($officer) {
                $query->select('id')
                    ->from('sections')
                    ->where('officer_id', $officer->id);
            })->where('consigned', 0)
                ->orderBy('section_id', 'ASC')->get();
        } elseif ($officer->role->name == 'Chef de batallaint') { // chef de bataillon{
            $locked = $lockStatus ? 'locked' : '';
            $students = Student::where('grade', $officer->bat)->get();
        }

        $choice = $request->query('choice', 'all');
        $searchTerm = $request->query('searchTerm', '');
        $searchType = $request->query('searchType', 'matricule');

        if ($request->wantsJson()) {
            $query = Student::with('section')->where('grade', $officer->bat);

            if ($officer->role->name == 'Chef de compagnie') {
                $userSections = $officer->sections()->pluck('id');
                $query->whereHas('section', function ($q) use ($userSections) {
                    $q->whereIn('id', $userSections);
                });
            }

            if ($searchTerm) {
                $term = '%' . $searchTerm . '%';
                switch ($searchType) {
                    case 'matricule':
                        $query->where('matricule', 'like', $term);
                        break;
                    case 'nom':
                        $query->where('nom', 'like', $term);
                        break;
                    case 'grade':
                        $query->where('grade', 'like', $term);
                        break;
                    case 'companie':
                        $query->whereHas('section', function ($q) use ($term) {
                            $q->where('companie', 'like', $term);
                        });
                        break;
                    case 'section':
                        $query->whereHas('section', function ($q) use ($term) {
                            $q->where('id', 'like', $term);
                        });
                        break;
                }
            }

            if ($choice === 'pasMarquer') {
                $query->whereNull('choix');
            } elseif (in_array($choice, ['ven', 'sam', '48h', '36h'], true)) {
                $query->where('choix', $choice);
            }

            $students = $query->orderBy('section_id', 'ASC')->get();

            return response()->json([
                'lock' => $lockStatus,
                'bat' => $officer->bat,
                'role' => $officer->role->name,
                'students' => $students->map(function ($student) {
                    return [
                        'matricule' => $student->matricule,
                        'nom' => $student->nom,
                        'prenom' => $student->prenom,
                        'grade' => $student->grade,
                        'choix' => $student->choix,
                        'consigned' => (bool) $student->consigned,
                        'section' => $student->section ? [
                            'id' => $student->section->id,
                            'num' => $student->section->num,
                            'companie' => $student->section->companie,
                            'bat' => $student->section->bat,
                            'code' => $student->section->code(),
                        ] : null,
                    ];
                }),
            ]);
        }

        return view('brigade.weekends', [
            'isCC' => $isCC,
            'officer' => $officer,

            'students' => $students,
            'lock' => $locked
        ]);
    }

    public function toggleWeekendLock(Officer $id)
    {
        $officer = $id;

        if ($officer->role->name !== 'Chef de batallaint') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $current = (int) DB::table('list_lock')->where('id', $officer->bat)->value('status');
        $next = $current === 1 ? 0 : 1;

        DB::table('list_lock')->updateOrInsert(
            ['id' => $officer->bat],
            ['status' => $next]
        );

        broadcast(new SortieLocked($officer->bat, $next === 1))->toOthers();

        return response()->json([
            'lock' => $next === 1
        ]);
    }

    public function updateWeekendSortie(Officer $id, Request $request)
    {
        $officer = $id;

        $validated = $request->validate([
            'matricule' => 'required',
            'choix' => 'required|in:ven,sam,48h,36h',
        ]);

        $student = Student::with('section')->where('matricule', $validated['matricule'])->firstOrFail();

        if ($student->grade != $officer->bat) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if ($officer->role->name === 'Chef de compagnie') {
            $sectionIds = $officer->sections()->pluck('id')->toArray();
            if (!in_array($student->section_id, $sectionIds, true)) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        $lockStatus = (bool) DB::table('list_lock')->where('id', $officer->bat)->value('status');
        if ($lockStatus) {
            return response()->json(['error' => 'List is locked'], 423);
        }

        if ($student->consigned) {
            return response()->json(['error' => 'Student is consigned'], 403);
        }

        [$from, $to] = $this->buildWeekendRange($validated['choix']);
        $currentSortie = Sortie::where('matricule', $student->matricule)
            ->latest('created_at')
            ->first();

        if (!$currentSortie) {
            $sortie = Sortie::create([
                'matricule' => $student->matricule,
                'choix' => $validated['choix'],
                'from' => $from,
                'to' => $to,
            ]);
            $student->choix = $validated['choix'];
            $student->save();
        } elseif ($validated['choix'] !== $student->choix) {
            // Update the existing sortie
            $currentSortie->update([
                'choix' => $validated['choix'],
                'from' => $from,
                'to' => $to,
            ]);
            $student->choix = $validated['choix'];
            $student->save();
        } else {
            // Same choice - toggle off (delete)
            $currentSortie->delete();
            $student->choix = null;
            $student->save();
        }

        $payloadStudent = [
            'matricule' => $student->matricule,
            'nom' => $student->nom,
            'prenom' => $student->prenom,
            'grade' => $student->grade,
            'choix' => $student->choix,
            'consigned' => (bool) $student->consigned,
            'section' => $student->section ? [
                'id' => $student->section->id,
                'num' => $student->section->num,
                'companie' => $student->section->companie,
                'bat' => $student->section->bat,
                'code' => $student->section->code(),
            ] : null,
        ];

        broadcast(new SortieUpdated($officer->bat, $payloadStudent))->toOthers();

        return response()->json([
            'student' => $payloadStudent
        ]);
    }

    private function buildWeekendRange(string $choice): array
    {
        $now = Carbon::now('Africa/Algiers');

        switch ($choice) {
            case 'sam':
                $from = $now->copy()->next(Carbon::SATURDAY)->setTime(8, 0, 0);
                $to = $now->copy()->next(Carbon::SATURDAY)->setTime(22, 0, 0);
                break;
            case 'ven':
                $from = $now->copy()->next(Carbon::FRIDAY)->setTime(8, 0, 0);
                $to = $now->copy()->next(Carbon::FRIDAY)->setTime(22, 0, 0);
                break;
            case '48h':
                $from = $now->copy()->next(Carbon::THURSDAY)->setTime(8, 0, 0);
                $to = $now->copy()->next(Carbon::SATURDAY)->setTime(22, 0, 0);
                break;
            case '36h':
            default:
                $from = $now->copy()->next(Carbon::FRIDAY)->setTime(8, 0, 0);
                $to = $now->copy()->next(Carbon::SATURDAY)->setTime(20, 0, 0);
                break;
        }

        return [$from, $to];
    }
}
