<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\User;
use App\Models\UserStake;
use App\Services\StakingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function incomes(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $type = $request->query('type', 'all');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = $user->incomes()->with(['fromUser', 'stake'])->latest();

        if ($type !== 'all' && in_array($type, ['daily_stake', 'direct_income', 'level_income', 'award_reward', 'direct', 'level', 'roi', 'matching'])) {
            $query->where('type', $type);
        }

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $incomes = $query->paginate(15)->withQueryString();

        return view('user.reports.incomes', compact('user', 'incomes', 'type', 'fromDate', 'toDate'));
    }

    public function roiIncomes(Request $request)
    {
        $user = Auth::user();
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::where('user_id', $user->id)
            ->whereIn('type', ['daily_stake', 'roi'])
            ->with(['stake'])
            ->latest();

        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalRoiEarned = (float) $query->clone()->sum('amount_coins');

        return view('user.reports.roi', compact('user', 'incomes', 'fromDate', 'toDate', 'totalRoiEarned'));
    }

    public function directIncomes(Request $request)
    {
        $user = Auth::user();
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::where('user_id', $user->id)
            ->whereIn('type', ['direct_income', 'direct'])
            ->with(['fromUser', 'stake'])
            ->latest();

        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalDirectCommission = (float) $query->clone()->sum('amount_coins');

        return view('user.reports.direct', compact('user', 'incomes', 'fromDate', 'toDate', 'totalDirectCommission'));
    }

    public function levelIncomes(Request $request)
    {
        $user = Auth::user();
        $level = $request->query('level');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::where('user_id', $user->id)
            ->whereIn('type', ['level_income', 'level'])
            ->with(['fromUser', 'stake'])
            ->latest();

        if ($level) {
            $query->where('level', $level);
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalLevelCommission = (float) $query->clone()->sum('amount_coins');

        return view('user.reports.level', compact('user', 'incomes', 'level', 'fromDate', 'toDate', 'totalLevelCommission'));
    }

    public function rewardIncomes(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::where('user_id', $user->id)
            ->whereIn('type', ['award_reward', 'matching'])
            ->with(['fromUser', 'stake'])
            ->latest();

        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalRewardCommission = (float) $query->clone()->sum('amount_coins');

        $legVolumes = $user->calculateLegVolumes();
        $milestones = \App\Services\AwardRewardService::getMilestones();
        $claimedTargets = \App\Models\UserReward::where('user_id', $user->id)->pluck('target_usd')->toArray();

        return view('user.reports.rewards', compact('user', 'incomes', 'fromDate', 'toDate', 'totalRewardCommission', 'legVolumes', 'milestones', 'claimedTargets'));
    }

    public function packageActivations(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = UserStake::where('user_id', $user->id)->latest();

        if ($status && in_array($status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $stakes = $query->paginate(15)->withQueryString();
        $totalStakedAmount = (float) $query->clone()->sum('amount_usd');

        return view('user.reports.packages', compact('user', 'stakes', 'status', 'fromDate', 'toDate', 'totalStakedAmount'));
    }

    public function team()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $directs = $user->directs()->withCount('directs')->with('stakes')->paginate(15);
        $levelConfigs = StakingService::getLevelConfigs();

        // 10 Level Downline Stats
        $levelCounts = [];
        $currentLevelUsers = $user->directs->pluck('id')->toArray();

        for ($level = 1; $level <= 10; $level++) {
            $levelCounts[$level] = count($currentLevelUsers);
            if (empty($currentLevelUsers)) {
                for ($next = $level + 1; $next <= 10; $next++) {
                    $levelCounts[$next] = 0;
                }
                break;
            }
            $currentLevelUsers = User::whereIn('sponsor_id', $currentLevelUsers)->pluck('id')->toArray();
        }

        $volumes = $user->calculateLegVolumes();

        return view('user.reports.team', compact('user', 'directs', 'levelConfigs', 'levelCounts', 'volumes'));
    }
}
