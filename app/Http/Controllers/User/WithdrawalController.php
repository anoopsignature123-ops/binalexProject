<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $withdrawals = $user->withdrawals()->latest()->paginate(10);
        $rate = 0.10; // $0.10 per coin

        return view('user.withdrawal.index', compact('user', 'withdrawals', 'rate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coins_amount' => 'required|numeric|min:5',
            'wallet_address' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $coinsAmount = (float) $request->input('coins_amount');

        if ($coinsAmount < 5.0) {
            return back()->withErrors(['coins_amount' => 'Minimum withdrawal amount is 5 coins.']);
        }

        // Enforce Earning Wallet withdrawal restriction (ROI Wallet is non-withdrawable directly, only via P2P)
        if ((float) $user->working_coin_balance < $coinsAmount) {
            return back()->withErrors([
                'coins_amount' => 'Insufficient Earning Wallet balance (' . number_format($user->working_coin_balance, 2) . ' Coins). Note: ROI Yield Wallet coins cannot be directly withdrawn to crypto wallet, but can be transferred to members via P2P Transfer.'
            ]);
        }

        $conversionRate = 0.10; // $0.10 per coin
        $grossUsd = $coinsAmount * $conversionRate;
        $deductionPercent = 10.00; // 10% deduction
        $deductionUsd = ($grossUsd * $deductionPercent) / 100.0;
        $netUsd = $grossUsd - $deductionUsd;

        DB::transaction(function () use ($user, $coinsAmount, $conversionRate, $grossUsd, $deductionPercent, $deductionUsd, $netUsd, $request) {
            $user->working_coin_balance -= $coinsAmount;
            $user->usdt_wallet_address = $request->input('wallet_address');
            $user->save();

            Withdrawal::create([
                'user_id' => $user->id,
                'coins_amount' => $coinsAmount,
                'conversion_rate' => $conversionRate,
                'gross_usd' => $grossUsd,
                'deduction_percent' => $deductionPercent,
                'deduction_usd' => $deductionUsd,
                'net_usd' => $netUsd,
                'wallet_address' => $request->input('wallet_address'),
                'status' => 'pending',
            ]);
        });

        return redirect()->route('user.withdrawal.index')
            ->with('success', 'Withdrawal request submitted successfully! $' . number_format($netUsd, 2) . ' USDT (net after 10% fee) will be sent to your BEP20 wallet.');
    }
}
