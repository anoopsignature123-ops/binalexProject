<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Income;
use App\Models\TokenPricing;
use App\Models\User;
use App\Models\UserStake;
use App\Models\Withdrawal;

class DashboardController extends Controller
{
    public function index()
    {
        $userQuery = User::whereHas('role', fn($q) => $q->where('name', 'user'));
        $totalUsers = $userQuery->count();
        $activeUsersCount = User::whereHas('role', fn($q) => $q->where('name', 'user'))
            ->whereHas('stakes', fn($q) => $q->where('status', 'active'))
            ->count();
        $inactiveUsersCount = $totalUsers - $activeUsersCount;

        $totalStakedUsd = (float) UserStake::sum('amount_usd');
        $totalStakedCoins = (float) UserStake::sum('coins_staked');

        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $totalDepositsUsd = (float) Deposit::where('status', 'approved')->sum('amount_usd');

        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $totalPaidOutUsd = (float) Withdrawal::where('status', 'approved')->sum('net_usd');

        // Income Distributions
        $totalDirectDistributed = (float) Income::whereIn('type', ['direct_income', 'direct'])->sum('amount_coins');
        $totalLevelDistributed = (float) Income::whereIn('type', ['level_income', 'level'])->sum('amount_coins');
        $totalRoiDistributed = (float) Income::whereIn('type', ['daily_stake', 'roi'])->sum('amount_coins');
        $totalRewardDistributed = (float) Income::whereIn('type', ['award_reward', 'matching'])->sum('amount_coins');
        $totalIncomesCoins = $totalDirectDistributed + $totalLevelDistributed + $totalRoiDistributed + $totalRewardDistributed;

        $pricing = TokenPricing::first() ?? (object) [
            'total_pool_coins' => 13000000,
            'total_pool_usd' => 300000,
            'coin_rate_usd' => 0.0231
        ];

        $recentDeposits = Deposit::with('user')->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->latest()->take(5)->get();
        $recentUsers = User::whereHas('role', fn($q) => $q->where('name', 'user'))
            ->with(['sponsor', 'stakes'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsersCount',
            'inactiveUsersCount',
            'totalStakedUsd',
            'totalStakedCoins',
            'pendingDeposits',
            'totalDepositsUsd',
            'pendingWithdrawals',
            'totalPaidOutUsd',
            'totalDirectDistributed',
            'totalLevelDistributed',
            'totalRoiDistributed',
            'totalRewardDistributed',
            'totalIncomesCoins',
            'pricing',
            'recentDeposits',
            'recentWithdrawals',
            'recentUsers'
        ));
    }
}
