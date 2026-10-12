<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TokenPricing;
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
        $pricing = TokenPricing::first() ?? (object) [
            'contract_address' => '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138',
            'withdrawal_contract_address' => '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138',
            'usdt_contract_address' => '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138',
        ];

        return view('user.withdrawal.index', compact('user', 'withdrawals', 'rate', 'pricing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coins_amount' => 'required|numeric|min:5',
            'wallet_address' => 'required|string|max:255',
            'wallet_type' => 'nullable|string|in:all,working,roi',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $coinsAmount = (float) $request->input('coins_amount');
        $walletType = $request->input('wallet_type', 'all');

        if ($coinsAmount < 5.0) {
            return back()->withErrors(['coins_amount' => 'Minimum withdrawal amount is 5 coins.']);
        }

        $workingBal = (float) $user->working_coin_balance;
        $roiBal = (float) $user->coin_balance;
        $totalBal = $workingBal + $roiBal;

        if ($walletType === 'working' && $workingBal < $coinsAmount) {
            return back()->withErrors(['coins_amount' => 'Insufficient Working Wallet balance. You have ' . number_format($workingBal, 2) . ' coins in Working Wallet.']);
        }

        if ($walletType === 'roi' && $roiBal < $coinsAmount) {
            return back()->withErrors(['coins_amount' => 'Insufficient ROI Yield balance. You have ' . number_format($roiBal, 2) . ' coins in ROI Wallet.']);
        }

        if ($walletType === 'all' && $totalBal < $coinsAmount) {
            return back()->withErrors(['coins_amount' => 'Insufficient total balance. You have ' . number_format($totalBal, 2) . ' total coins available.']);
        }

        $conversionRate = 0.10; // $0.10 per coin
        $grossUsd = $coinsAmount * $conversionRate;
        $deductionPercent = 10.00; // 10% deduction
        $deductionUsd = ($grossUsd * $deductionPercent) / 100.0;
        $netUsd = $grossUsd - $deductionUsd;

        DB::transaction(function () use ($user, $coinsAmount, $conversionRate, $grossUsd, $deductionPercent, $deductionUsd, $netUsd, $request, $walletType) {
            if ($walletType === 'working') {
                $user->working_coin_balance -= $coinsAmount;
            } elseif ($walletType === 'roi') {
                $user->coin_balance -= $coinsAmount;
            } else {
                if ((float) $user->working_coin_balance >= $coinsAmount) {
                    $user->working_coin_balance -= $coinsAmount;
                } else {
                    $remainingToDeduct = $coinsAmount - (float) $user->working_coin_balance;
                    $user->working_coin_balance = 0;
                    $user->coin_balance = max(0, (float) $user->coin_balance - $remainingToDeduct);
                }
            }

            $user->usdt_wallet_address = trim($request->input('wallet_address'));
            $user->save();

            Withdrawal::create([
                'user_id' => $user->id,
                'coins_amount' => $coinsAmount,
                'conversion_rate' => $conversionRate,
                'gross_usd' => $grossUsd,
                'deduction_percent' => $deductionPercent,
                'deduction_usd' => $deductionUsd,
                'net_usd' => $netUsd,
                'wallet_address' => trim($request->input('wallet_address')),
                'status' => 'pending',
                'admin_note' => 'Wallet Source: ' . strtoupper($walletType),
            ]);
        });

        return redirect()->route('user.withdrawal.index')
            ->with('success', 'Withdrawal request of ' . number_format($coinsAmount, 2) . ' Coins ($' . number_format($netUsd, 2) . ' USDT Net) submitted successfully! Payout will be sent to your BEP20 wallet upon approval.');
    }
}