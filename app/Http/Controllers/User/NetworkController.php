<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    public function directs()
    {
        $user = Auth::user();
        $directs = User::where('sponsor_id', $user->id)
            ->with(['stakes'])
            ->withCount('directs')
            ->latest()
            ->paginate(15);

        return view('user.network.directs', compact('user', 'directs'));
    }

    public function tree($userId = null)
    {
        $authUser = Auth::user();

        if ($userId) {
            $targetUser = User::with(['directs.directs.directs'])->find($userId);
            
            // Security Check: User can only view their own downline tree!
            if (!$targetUser || (!$authUser->isAdmin() && !$authUser->isAncestorOf($targetUser->id))) {
                return redirect()->route('user.network.tree')
                    ->with('error', 'You can only view genealogy trees within your own downline team.');
            }
            $rootUser = $targetUser;
        } else {
            $rootUser = User::with(['directs.directs.directs'])->find($authUser->id);
        }

        return view('user.network.tree', compact('rootUser', 'authUser'));
    }
}
