<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    // Secret Admin Security Key required in URL parameter to unlock admin login page
    public const ADMIN_SECRET_KEY = 'binalex2026';

    public function showLogin(Request $request)
    {
        // STRICT SECURITY: Require exact secret URL key parameter on the GET request
        if ($request->query('key') !== self::ADMIN_SECRET_KEY) {
            abort(404);
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // STRICT SECURITY: Require exact secret URL key parameter on the POST request
        if ($request->query('key') !== self::ADMIN_SECRET_KEY && $request->input('secret_key') !== self::ADMIN_SECRET_KEY) {
            abort(404);
        }

        // 1. Honeypot Anti-Bot Shield
        if (!empty($request->input('website_security_honey'))) {
            return back()->withErrors(['email' => 'Security Lockout: Automated bot activity detected.']);
        }

        // 2. Brute-Force Throttling (Max 5 Failed Attempts per Minute per IP)
        $throttleKey = 'admin-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "SECURITY LOCKOUT: Too many failed login attempts from your IP. Please wait {$seconds} seconds before trying again."
            ])->onlyInput('email');
        }

        // 3. Form Input Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => strtolower(trim($request->input('email'))),
            'password' => $request->input('password'),
        ];

        $remember = $request->boolean('remember');

        // 4. Authentication Attempt
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if (!$user->role || $user->role->name !== 'admin') {
                Auth::logout();
                RateLimiter::hit($throttleKey, 60);
                return back()->withErrors(['email' => 'Access denied. Administrator privileges required.']);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Invalid admin credentials. Please check email and password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login', ['key' => self::ADMIN_SECRET_KEY]);
    }
}