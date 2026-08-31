<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Withdrawal::with('user')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            })->orWhere('wallet_address', 'like', "%{$search}%");
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals', 'status'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string|max:255',
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->withErrors(['withdrawal' => 'Withdrawal request has already been processed.']);
        }

        $newStatus = $request->input('status');

        DB::transaction(function () use ($withdrawal, $newStatus, $request) {
            $withdrawal->status = $newStatus;
            $withdrawal->admin_note = $request->input('admin_note');
            $withdrawal->save();

            // If rejected, refund coins back to user's working_coin_balance (Earning Wallet)
            if ($newStatus === 'rejected') {
                $user = $withdrawal->user;
                $user->working_coin_balance += $withdrawal->coins_amount;
                $user->save();
            }
        });

        $action = $newStatus === 'approved' ? 'approved for USDT crypto payout' : 'rejected and coins refunded to user\'s Earning Wallet';
        return redirect()->route('admin.withdrawals.index')
            ->with('success', "Withdrawal #{$withdrawal->id} has been {$action}.");
    }
}
