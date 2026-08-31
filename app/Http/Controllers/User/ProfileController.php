<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'wallet_address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'wallet_address' => $request->input('wallet_address'),
        ]);

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password provided is incorrect.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('user.profile.index')
            ->with('success', 'Account password changed successfully.');
    }
}
