<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // A single login page for all users
    }

    public function login(Request $request)
    {
        $credentials = $request->only('id', 'password');

        // Try logging in as an officer
        if (Auth::guard('officer')->attempt($credentials)) {
            $officer = Auth::guard('officer')->user();

            request()->session()->regenerate();
            return redirect()->route('officer.dashboard', $officer->id);
        }

        // Try logging in as a student
        if (Auth::guard('student')->attempt($credentials)) {

            return redirect()->route('posts.index');
        }

        return back()->withErrors(['id' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('officer')->logout();
        Auth::guard('student')->logout();

        return redirect('/login');
    }
}
