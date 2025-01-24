<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            Auth::user()->update([
                'last_seen' => now() // Only update last_seen, don't change is_active
            ]);

            $request->session()->regenerate();
            
            // Log aktivitas login
            ActivityLogService::logLogin(Auth::user(), "Login ke sistem");
            
            // Redirect based on role
            if (auth()->user()->role === 'admin') {
                return redirect()->intended(route('dashboard'));
            } else {
                return redirect()->intended(route('petugas.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas'
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    public function logout(Request $request)
    {
        // Log aktivitas logout sebelum session dihapus
        if (Auth::check()) {
            ActivityLogService::logLogout(Auth::user(), "Logout dari sistem");
            Auth::user()->update([
                'last_seen' => null  // Set last_seen to null to make user appear offline
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
