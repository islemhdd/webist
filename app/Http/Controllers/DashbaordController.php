<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashbaordController extends Controller
{   //hadoma ghir ll officers mazal madrtch ta3 les etudiantes
    public function cons($id)
    {
        return view('cons');
    }
    public function parametre($id)
    {
        return view('parametre');
    }
    public function principale($id)
    {
        return view('principale', compact("id"));

    }
    public function infermerie($id)
    {
        return view('infermerie');
    }
    public function weekends($id)
    {   //started working , just need to find all the students in the sections
        $officer = Officer::find($id);
        $isCC = false;
        if ($officer->role == 4) {
            $isCC = true;
            $companies = $officer->companie();
            $students = Student::whereIn('section_id', function ($query) use ($officer) {
                $query->select('id')
                      ->from('sections')
                      ->where('officer_id', $officer->id);
            })->orderBy('section_id', 'ASC')->get();



            return view('weekends', [
                'isCC' => $isCC,
                'officer' => $officer,
                'companies' => $companies,
                'students' => $students
            ]);
        }

        return view('weekends');
    }
}
