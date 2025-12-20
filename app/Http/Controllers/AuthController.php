<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request) {
        $creds = $request->validate(['email' => 'required', 'password' => 'required']);
        if (Auth::attempt($creds)) {
            $request->session()->regenerate();
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.umkms.index') 
                : redirect()->route('home');
        }
        return back()->withErrors(['email' => 'Login Gagal.']);
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request) {
        $request->validate(['name'=>'required', 'email'=>'required|unique:users', 'password'=>'required|confirmed']);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengguna'
        ]);
        return redirect()->route('login')->with('success', 'Berhasil daftar!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}