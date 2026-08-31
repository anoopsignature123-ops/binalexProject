<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        // 1. Honeypot Anti-Bot Shield
        if (!empty($request->input('website_security_honey'))) {
            return back()->withErrors(['email' => 'Security Lockout: Automated bot activity detected.']);
        }

        // 2. Brute-Force Throttling (Max 5 Failed Attempts per Minute per IP)
        $throttleKey = 'user-login:' . $request->ip();
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
            if ($user->role && $user->role->name === 'admin') {
                Auth::logout();
                RateLimiter::hit($throttleKey, 60);
                return back()->withErrors(['email' => 'Admin accounts must log in through the Admin Portal (/admin/login).']);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('user.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        $refCode = $request->query('ref', '');
        return view('user.auth.register', compact('refCode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'sponsor_code' => 'required|string|exists:users,referral_code',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'sponsor_code.required' => 'Sponsor Referral ID is mandatory to create an account.',
            'sponsor_code.exists' => 'Invalid Sponsor Referral ID. Please check and enter a valid registered Sponsor Referral Code.',
        ]);

        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            $userRole = Role::create(['name' => 'user']);
        }

        $sponsor = User::where('referral_code', $validated['sponsor_code'])->firstOrFail();

        $referralCode = 'BT' . rand(1000000, 9999999);
        while (User::where('referral_code', $referralCode)->exists()) {
            $referralCode = 'BT' . rand(1000000, 9999999);
        }

        $user = User::create([
            'role_id' => $userRole->id,
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'mobile' => $validated['mobile'],
            'referral_code' => $referralCode,
            'sponsor_id' => $sponsor->id,
            'status' => 'inactive',
            'password' => Hash::make($validated['password']),
            'usdt_balance' => 0.00,
            'coin_balance' => 0.00,
        ]);

        UserReferral::create([
            'user_id' => $user->id,
            'sponsor_id' => $sponsor->id,
            'sponsor_referral_code' => $sponsor->referral_code,
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Account registered successfully with Referral Code: ' . $referralCode . '! Status is currently INACTIVE. Purchase a staking package to activate your account.');
    }

    public function lookupSponsor($code)
    {
        $sponsor = User::where('referral_code', trim($code))->first();
        if ($sponsor) {
            return response()->json([
                'success' => true,
                'name' => $sponsor->name ?? 'Sponsor Member',
                'email' => $sponsor->email,
                'referral_code' => $sponsor->referral_code,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid Sponsor Referral ID.',
        ], 404);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}
