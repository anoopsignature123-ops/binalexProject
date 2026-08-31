<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Deposit::with('user')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            })->orWhere('txn_hash', 'like', "%{$search}%");
        }

        $deposits = $query->paginate(15)->withQueryString();
        $allUsers = User::whereHas('role', fn($q) => $q->where('name', 'user'))->orderBy('name')->get();

        return view('admin.deposits.index', compact('deposits', 'status', 'allUsers'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string|max:255',
        ]);

        if ($deposit->status !== 'pending') {
            return back()->withErrors(['deposit' => 'Deposit status has already been processed.']);
        }

        $newStatus = $request->input('status');

        DB::transaction(function () use ($deposit, $newStatus, $request) {
            $deposit->status = $newStatus;
            $deposit->admin_note = $request->input('admin_note');
            $deposit->save();

            if ($newStatus === 'approved') {
                $user = $deposit->user;
                $user->usdt_balance += $deposit->amount_usd;
                $user->save();
            }
        });

        $action = $newStatus === 'approved' ? 'approved and user balance credited' : 'rejected';
        return redirect()->route('admin.deposits.index')
            ->with('success', "Deposit #{$deposit->id} has been {$action}.");
    }

    public function creditUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount_usd' => 'required|numeric|min:0.01',
            'txn_type' => 'required|in:credit,debit',
            'admin_note' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $amount = (float) $request->input('amount_usd');
        $type = $request->input('txn_type');
        $note = $request->input('admin_note');

        DB::transaction(function () use ($user, $amount, $type, $note) {
            if ($type === 'credit') {
                $user->usdt_balance += $amount;
                $user->save();

                Deposit::create([
                    'user_id' => $user->id,
                    'amount_usd' => $amount,
                    'payment_method' => 'Admin Credit',
                    'txn_hash' => 'ADMIN_CREDIT_' . strtoupper(uniqid()),
                    'status' => 'approved',
                    'admin_note' => $note,
                ]);
            } else {
                $user->usdt_balance = max(0, $user->usdt_balance - $amount);
                $user->save();

                Deposit::create([
                    'user_id' => $user->id,
                    'amount_usd' => -$amount,
                    'payment_method' => 'Admin Debit',
                    'txn_hash' => 'ADMIN_DEBIT_' . strtoupper(uniqid()),
                    'status' => 'approved',
                    'admin_note' => $note,
                ]);
            }
        });

        $msg = $type === 'credit' 
            ? "Successfully credited \${$amount} USDT to {$user->name}'s Deposit Wallet!" 
            : "Successfully debited \${$amount} USDT from {$user->name}'s Deposit Wallet.";

        return redirect()->route('admin.deposits.index')->with('success', $msg);
    }
}
