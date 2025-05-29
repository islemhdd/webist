<?php

namespace App\Http\Controllers;

use App\Models\Consigne;
use App\Models\Officer;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashbaordController extends Controller
{   //hadoma ghir ll officers mazal madrtch ta3 les etudiantes
    public function cons(Officer $id)
    {
        $nextThursday = Carbon::now()->next(Carbon::THURSDAY);

        $consignedStudents = Student::with('consigne')
            ->where('consigned', 1)
            ->whereHas('section', function ($query) use ($id) {
                $query->where('officer_id', $id->id);
            })
            ->whereHas('consigne', function ($query) use ($nextThursday) {
                $query->where('start', '>=', $nextThursday);
            })
            ->paginate(10); //the start is this weekende






        return view('brigade.consigne', compact($consignedStudents));
    }

    public function parametre($id)
    {
        return view('brigade.parametre');
    }
    public function principale($id)
    {
        return view('brigade.principale');
    }

    public function weekends(Officer $id)
    {   //started working , just need to find all the     students in the sections

        $officer = $id;
        $isCC = false;

        $locked = [];

        if ($officer->role->name == 'Chef de compagnie') // chef companie
        {
            $isCC = true;
            $lockResult = DB::select("SELECT status FROM list_lock WHERE id = $officer->bat"); //!officer->bat
            $locked = $lockResult[0]->status == 1 ? 'locked' : '';

            $students = Student::whereIn('section_id', function ($query) use ($officer) {
                $query->select('id')
                    ->from('sections')
                    ->where('officer_id', $officer->id);
            })->where('consigned', 0)
                ->orderBy('section_id', 'ASC')->get();
        } elseif ($officer->role->name == 'Chef de batallaint') { // chef de bataillon{
            $lockResult = DB::select("SELECT status FROM list_lock WHERE id = 3");
            $locked = $lockResult[0]->status == 1 ? 'locked' : '';
            $students = Student::where('grade', $officer->bat)->get();
        }


        return view('brigade.weekends', [
            'isCC' => $isCC,
            'officer' => $officer,

            'students' => $students,
            'lock' => $locked
        ]);
    }

    public function infermerie(Officer $id)
    {
        $officer = $id;

        // Cette méthode redirige vers la liste des patients de l'officier
        // selon le système existant
        return redirect()->route('brigade.list_patients', ['id' => $officer->id]);
    }
}
