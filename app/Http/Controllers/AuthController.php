<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // A single login page for all users
    }

    public function login(Request $request)

    {

        // dd($request->input('password'));



        $credentials = $request->validate([
            'id' => ['required'],
            'password' => ['required', 'min:8'],
        ]);
        // dd($credentials);

        // dd(Auth::guard('officer')->attempt($credentials));
        // s




        if ($credentials['id'] < 2000000) {
            if (Auth::guard('officer')->attempt($credentials,true)) {

                $officer = Auth::guard('officer')->user();

                request()->session()->regenerate();
                return redirect()->route('principale', $officer->id);
            }
        } else {

            // Try logging in as a student
            if (Auth::guard('student')->attempt($credentials, true)) {

                return redirect()->route('posts.index');
            }
        }

        // return back()->withInput($request->only('id', 'password'));
        return 1;
    }

    public function logout(Request $request)
    {

        Auth::guard('officer')->logout();
        Auth::guard('student')->logout();

        return redirect()->route("showLoginForm");
    }
}
