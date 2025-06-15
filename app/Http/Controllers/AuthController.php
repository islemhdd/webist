<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index()
    {
        return view('infermerie.login');
    }

    public function login(Request $request)
    {
        // Valide les champs
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Utilise le guard par défaut (web)
        if (Auth::attempt($credentials, true)) {  // Utilisation de Auth::attempt sans spécifier de guard
            $request->session()->regenerate();

            // Redirect based on user role
            $userRole = Auth::user()->role->name;

            if (in_array($userRole, ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])) {
                // Redirect all medical staff (including chief) to their specialized dashboard
                return redirect()->route('medical.dashboard');
            } elseif ($userRole == 'Directeur des etudes') {
                return redirect()->route('de.dashboard');
            } elseif (in_array($userRole, ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général'])) {
                // Check if user is an officer (has officer data)

                $officer = auth()->user()->isOfficer();
                if ($officer) {
                    // dd(1);
                    // Redirect officers using their user ID since officers table doesn't exist
                    return redirect()->route('brigade.statistics',['id'=>auth()->user()->id] );
                } else {
                    // If user has officer role but no officer data, logout and show error
                    Auth::logout();
                    return back()->withErrors(['username' => 'Compte d\'officier non configuré correctement.']);
                }
            } else {
                // For any other roles, try to redirect to principale if they have officer data
                $officer = auth()->user()->isOfficer();
                if ($officer) {
                    return redirect()->route('principale', ['id' => auth()->user()->id]);
                } else {
                    // If no officer data, logout and show error
                    Auth::logout();
                    return back()->withErrors(['username' => 'Type de compte non reconnu.']);
                }
            }
        }

        return back()
            ->withErrors(['username' => 'Identifiants incorrects.'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();  // Utilisation de Auth::logout() sans spécifier de guard
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
