<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Income;
use App\Models\TokenPricing;
use App\Models\User;
use App\Models\UserStake;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

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

    public function processRoi(Request $request)
    {
        try {
            $days = (int) $request->input('days', 1);
            $date = $request->input('date');

            $count = (new \App\Services\StakingService())->processDailyRoi($days, $date);

            return redirect()->back()->with('success', "Daily ROI Payout process completed successfully ({$count} payout record(s) processed for {$days} day(s))!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing daily ROI: ' . $e->getMessage());
        }
    }

    public function processAwards()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('binalex:process-awards');
            return redirect()->back()->with('success', 'Rank & Award Rewards scanning completed successfully for all active users!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing rank awards: ' . $e->getMessage());
        }
    }

    public function processAllCrons()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('binalex:process-all');
            return redirect()->back()->with('success', 'All Daily Crons (ROI Payouts & Rank Rewards) executed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error executing daily crons: ' . $e->getMessage());
        }
    }
}
