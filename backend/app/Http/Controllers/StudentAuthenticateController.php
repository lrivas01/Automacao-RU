<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illumnate\Support\Facades\Auth;

class StudentAuthenticateController extends Controller {
    
    public function authenticate(Request $request): RedirectResponse{

        $remember = false;
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials, $remember)){  // usar o Auth::viaRemember()
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email incorreto!'
        ])->onlyInput('email');
    }
}
