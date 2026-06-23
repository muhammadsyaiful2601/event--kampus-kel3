<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

       public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();


            return redirect()->intended('/dashboard');
        }


        return back()->withErrors([
            'email' => 'Email atau password yang masukkan salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pengguna'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create the pengguna (password will be hashed by pengguna cast or Hash::make)
        Pengguna::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return redirect()->route('login')->with('status', 'Akun berhasil dibuat. Silakan login.');
    }

    public function showForgotPassword()
    {
        return view('auth.lupa_password');
    }
}
