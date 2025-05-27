<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function index(Officer $id)
    {
        $officer = $id;
        // * patient
        $patQr = $officer->patients();
        $patValider = $patQr->where('valider', 1)->count();
        $patAttent = $patQr->where('valider', 0)->count();
        $patRefuser = $patQr->where('valider', 2)->count();
        $patTotal = $patQr->count();
        // *fin de patient


    }
}
