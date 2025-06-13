<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMedicalSpecialty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $specialty
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $specialty = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role->name ?? '';

        // Le médecin chef a accès à tout
        if ($userRole === 'Medecin') {
            return $next($request);
        }

        // Vérifier les spécialités
        $allowedSpecialties = $this->getAllowedSpecialties($userRole);

        if ($specialty && !in_array($specialty, $allowedSpecialties)) {
            abort(403, 'Accès non autorisé à cette spécialité médicale.');
        }

        return $next($request);
    }

    /**
     * Récupérer les spécialités autorisées selon le rôle
     */
    private function getAllowedSpecialties($userRole)
    {
        switch ($userRole) {
            case 'Psychologue':
                return ['psycho'];
            case 'Dentiste':
                return ['dentiste'];
            case 'Médecin général':
                return ['médecin générale'];
            case 'Medecin': // Médecin chef
                return ['psycho', 'dentiste', 'médecin générale'];
            default:
                return [];
        }
    }
}
