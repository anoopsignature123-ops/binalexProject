<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = User::whereHas('role', fn($q) => $q->where('name', 'user'))
            ->with(['sponsor', 'stakes'])
            ->withCount('directs')
            ->latest();

        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => 'required|string|max:20',
            'sponsor_code' => 'required|string|exists:users,referral_code',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'sponsor_code.required' => 'Sponsor Referral ID is mandatory.',
            'sponsor_code.exists' => 'Invalid Sponsor Referral ID. Please enter a valid registered referral code.',
            'email.unique' => 'This email address is already registered.',
        ]);

        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            $userRole = Role::create(['name' => 'user']);
        }

        $sponsor = User::where('referral_code', $validated['sponsor_code'])->firstOrFail();

        // Auto-generate Referral Code format: BT + 7 random digits (e.g. BT9876453)
        $referralCode = 'BT' . rand(1000000, 9999999);
        while (User::where('referral_code', $referralCode)->exists()) {
            $referralCode = 'BT' . rand(1000000, 9999999);
        }

        $user = User::create([
            'role_id' => $userRole->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
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

        return redirect()->route('admin.users.index')
            ->with('success', "New User '{$user->name}' created successfully with Referral Code: {$referralCode} and Inactive status.");
    }

    public function show(User $user)
    {
        $user->load(['sponsor', 'stakes', 'directs', 'deposits', 'withdrawals', 'incomes']);
        $volumes = $user->calculateLegVolumes();

        return view('admin.users.show', compact('user', 'volumes'));
    }

    public function edit(User $user)
    {
        $user->load(['sponsor', 'stakes', 'directs', 'deposits', 'withdrawals', 'incomes']);
        $volumes = $user->calculateLegVolumes();

        return view('admin.users.edit', compact('user', 'volumes'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'status' => $request->input('status'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        if ($request->has('usdt_balance') && is_numeric($request->input('usdt_balance'))) {
            $data['usdt_balance'] = (float) $request->input('usdt_balance');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name}'s account details updated successfully.");
    }

    public function tree($userId = null)
    {
        if ($userId) {
            $rootUser = User::with(['directs.directs.directs'])->findOrFail($userId);
        } else {
            $rootUser = User::whereHas('role', fn($q) => $q->where('name', 'user'))
                ->with(['directs.directs.directs'])
                ->first();
        }

        return view('admin.users.tree', compact('rootUser'));
    }

    public function directs(User $user)
    {
        $user->load(['sponsor']);
        $directs = User::where('sponsor_id', $user->id)
            ->with(['stakes'])
            ->withCount('directs')
            ->latest()
            ->paginate(15);

        return view('admin.users.directs', compact('user', 'directs'));
    }

    public function impersonate(User $user)
    {
        $adminId = Auth::user()?->isAdmin() ? Auth::id() : session('admin_session_user_id');
        session(['admin_session_user_id' => $adminId, 'impersonated_by' => $adminId]);
        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', "Logged into account: {$user->name} ({$user->email}) in new tab successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['user' => 'Cannot delete Administrator account.']);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}