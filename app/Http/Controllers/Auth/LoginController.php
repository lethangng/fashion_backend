<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Kreait\Laravel\Firebase\Facades\Firebase;

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
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'email.email' => 'Email không đúng định dạng.',
            'password.min' => 'Mật khẩu phải lớn hơn 6 ký tự.'
        ]);


        if (Auth::attempt($credentials) && Auth::user()->role == 0) {
            // $signInResult = Firebase::auth()->signInWithEmailAndPassword($request->email, $request->password);

            $request->session()->regenerate();
            return redirect()->route('admin.index');

            // $user = $signInResult->data();
            // if ($signInResult->data()) {
            //     return redirect()->route('admin.index');
            // } else {
            //     return redirect()->route('login');
            // }


        }
        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu không đúng.',
            'password' => 'Tài khoản hoặc mật khẩu không đúng.',
        ]);
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
