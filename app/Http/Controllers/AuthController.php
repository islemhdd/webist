<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('brigade.login');
    }

    public function login(Request $request)


    {

       

        // Validate inputs; allow either email or username.
        $request->validate([
            'email' => ['nullable', 'email'],
            'username' => ['nullable', 'string'],
            'password' => ['required'],
        ]);

        if (!$request->filled('email') && !$request->filled('username')) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Email ou nom d utilisateur requis.',
                ], 422);
            }

            return back()
                ->withErrors(['username' => 'Email ou nom d utilisateur requis.'])
                ->withInput($request->except('password'));
        }

        $credentials = [
            $request->filled('email') ? 'email' : 'username' => $request->filled('email')
                ? $request->input('email')
                : $request->input('username'),
            'password' => $request->input('password'),
        ];

        // Use default guard (web).
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $officer = $user ? $user->isOfficer() : null;
            $unreadReports = 0;
            if ($officer) {
                $unreadReports = $officer->unreadNotifications()
                    ->where('type', 'App\\Notifications\\ReportArival')
                    ->count();
            }
            if ($user && $user->role && $user->role->name != 'MED') {
                if ($officer && isset($officer->id)) {
                    $redirectUrl = route('brigade.statistics', ['id' => $officer->id]);
                    if ($request->wantsJson()) {
                        return response()->json([
                            'message' => 'Connexion reussie.',
                            'redirect' => $redirectUrl,
                            'role_id' => $user?->role_id,
                            'unread_reports' => $unreadReports,
                        ]);
                    }
                    return redirect()->route('brigade.statistics', ['id' => $officer->id]);
                }
            }

            $redirectUrl = route('statistics.index');
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Connexion reussie.',
                    'redirect' => $redirectUrl,
                    'role_id' => $user?->role_id,
                    'unread_reports' => $unreadReports,
                ]);
            }
            return redirect()->route('statistics.index');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Identifiants incorrects.',
            ], 401);
        }

        return back()
            ->withErrors(['username' => 'Identifiants incorrects.'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
