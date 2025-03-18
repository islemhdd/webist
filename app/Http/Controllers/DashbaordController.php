<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashbaordController extends Controller
{
    public function cons()
    {
        return view('cons');
    }
    public function parametre()
    {
        return view('parametre');
    }
    public function principale()
    {
        return view('principale');
    }
    public function infermerie()
    {
        return view('infermerie');
    }
    public function weekends()
    {
        return view('weekends');
    }
}
