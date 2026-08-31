<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\TokenPricing;
use App\Models\UserStake;
use App\Services\AwardRewardService;
use App\Services\StakingService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load(['stakes', 'incomes', 'directs']);

        $pricing = TokenPricing::first() ?? (object) ['coin_rate_usd' => 0.0231];

        $activeStakesCount = $user->stakes()->where('status', 'active')->count();
        $activeStakeModel = $user->stakes()->where('status', 'active')->first();
        $totalStakedCoins = $user->stakes()->sum('coins_staked');

        // Total Incomes
        $totalDailyIncome = (float) $user->incomes()->whereIn('type', ['daily_stake', 'roi'])->sum('amount_coins');
        $totalDirectIncome = (float) $user->incomes()->whereIn('type', ['direct_income', 'direct'])->sum('amount_coins');
        $totalLevelIncome = (float) $user->incomes()->whereIn('type', ['level_income', 'level'])->sum('amount_coins');
        $totalRewardIncome = (float) $user->incomes()->whereIn('type', ['award_reward', 'matching'])->sum('amount_coins');
        $totalEarningsCoins = $totalDailyIncome + $totalDirectIncome + $totalLevelIncome + $totalRewardIncome;

        // Today Incomes
        $today = now()->today();
        $todayDailyIncome = (float) $user->incomes()->whereIn('type', ['daily_stake', 'roi'])->whereDate('created_at', $today)->sum('amount_coins');
        $todayDirectIncome = (float) $user->incomes()->whereIn('type', ['direct_income', 'direct'])->whereDate('created_at', $today)->sum('amount_coins');
        $todayLevelIncome = (float) $user->incomes()->whereIn('type', ['level_income', 'level'])->whereDate('created_at', $today)->sum('amount_coins');
        $todayRewardIncome = (float) $user->incomes()->whereIn('type', ['award_reward', 'matching'])->whereDate('created_at', $today)->sum('amount_coins');

        $teamVolumes = $user->calculateLegVolumes();
        $powerLegVolume = $teamVolumes['power_leg'];
        $otherLegsVolume = $teamVolumes['other_legs'];
        $totalTeamVolume = $teamVolumes['total_volume'];

        $milestones = AwardRewardService::getMilestones();
        $claimedRewardTargets = $user->rewards()->pluck('target_usd')->toArray();

        $recentIncomes = $user->incomes()->latest()->take(5)->get();
        $packages = StakingService::getPackages();

        return view('user.dashboard', compact(
            'user',
            'pricing',
            'activeStakesCount',
            'activeStakeModel',
            'totalStakedCoins',
            'totalDailyIncome',
            'totalDirectIncome',
            'totalLevelIncome',
            'totalRewardIncome',
            'totalEarningsCoins',
            'todayDailyIncome',
            'todayDirectIncome',
            'todayLevelIncome',
            'todayRewardIncome',
            'powerLegVolume',
            'otherLegsVolume',
            'totalTeamVolume',
            'milestones',
            'claimedRewardTargets',
            'recentIncomes',
            'packages'
        ));
    }
}
