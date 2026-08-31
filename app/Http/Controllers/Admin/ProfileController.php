<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'mobile' => 'required|string|max:20',
        ]);

        $admin->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Admin profile information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password provided is incorrect.']);
        }

        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Admin password changed successfully.');
    }
}
