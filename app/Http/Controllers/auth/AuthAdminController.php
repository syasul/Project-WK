<?php

namespace App\Http\Controllers\auth; // (Atau Auth jika foldernya huruf besar)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- 1. IMPORT YANG BENAR

class AuthAdminController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // <-- 2. TYPO DIPERBAIKI (Gunakan Auth::attempt)
        if (Auth::attempt($credentials)) {
            // Mencegah Session Fixation (Best Practice Security)
            $request->session()->regenerate(); 
            
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Saya tambahkan fungsi logout sekalian untuk web admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Sesuaikan dengan route login web kamu
    }
}