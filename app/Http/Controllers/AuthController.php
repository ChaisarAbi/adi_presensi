<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            // Invalidate semua session lama untuk user ini
            $user = Auth::user();
            
            // Regenerate session ID untuk mencegah session fixation
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->regenerate();
            
            // Login ulang user setelah regenerate session
            Auth::login($user, $request->remember);
            
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'guru':
                    return redirect()->route('guru.dashboard');
                case 'ortu':
                    return redirect()->route('ortu.dashboard');
                default:
                    Auth::logout();
                    return back()->with('error', 'Role tidak valid.');
            }
        }

        return back()->with('error', 'Email atau password salah.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
