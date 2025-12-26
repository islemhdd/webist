<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HomeApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => 'Bienvenue',
            'description' => 'Systeme de gestion moderne',
            'features' => [
                'reports' => 'Rapports clairs',
                'sanctions' => 'Creation des sanctions',
                'weekends' => 'Gestion des weekends',
            ],
        ]);
    }
}
