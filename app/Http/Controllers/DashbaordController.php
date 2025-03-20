<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashbaordController extends Controller
{   //hadoma ghir ll officers mazal madrtch ta3 les etudiantes
    public function cons()
    {
        return view('cons');
    }
    public function parametre()
    {
        return view('parametre');
    }
    public function principale($id)
    {
        return view('principale', compact("id"));
        dd(1);
    }
    public function infermerie()
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
            $student = DB::select("SELECT * from students where section_id in (select id from sections where officer_id=$officer->id  order by section_id ASC)");
            // dd($student);

            return view('weekends', [
                'isCC' => $isCC,
                'officer' => $officer,
                'companies' => $companies,
                'student' => $student
            ]);
        }

        return view('weekends');
    }
}
