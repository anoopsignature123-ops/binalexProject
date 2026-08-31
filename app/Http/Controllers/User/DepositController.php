<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $deposits = $user->deposits()->latest()->paginate(10);
        $adminBep20Address = config('app.usdt_bep20_address', '0x1234567890abcdef1234567890abcdef12345678');

        return view('user.deposit.index', compact('user', 'deposits', 'adminBep20Address'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount_usd' => 'required|numeric|min:1',
            'transaction_hash' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        Deposit::create([
            'user_id' => $user->id,
            'amount_usd' => $request->input('amount_usd'),
            'payment_method' => 'USDT_BEP20',
            'transaction_hash' => $request->input('transaction_hash'),
            'status' => 'pending',
        ]);

        return redirect()->route('user.deposit.index')
            ->with('success', 'USDT Deposit request submitted successfully! Admin will verify your transaction hash shortly.');
    }
}
