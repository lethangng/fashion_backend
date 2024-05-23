<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin.auth.auth-login');
    }

    public function handleLogin(Request $request)
    {
        // dd($request->all());
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        try {
            if (Auth::attempt($credentials) && Auth::user()->role == 0) {
                $signInResult = Firebase::auth()->signInWithEmailAndPassword($request->email, $request->password);

                // $user = $signInResult->data();
                if ($signInResult->data()) {
                    return redirect()->route('admin.index');
                } else {
                    return redirect()->route('login');
                }

                // $request->session()->regenerate();
            }
        } catch (\Exception $e) {
            dd($e);
        }
        return redirect()->route('login');
    }

    public function resetPassword()
    {
        return view('admin.auth.auth-recoverpw');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
