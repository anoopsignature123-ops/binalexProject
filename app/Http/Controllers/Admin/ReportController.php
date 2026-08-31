<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\UserStake;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function roiIncomes(Request $request)
    {
        $search = $request->query('search');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::whereIn('type', ['daily_stake', 'roi'])
            ->with(['user', 'stake'])
            ->latest();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalRoiDistributed = (float) $query->clone()->sum('amount_coins');

        return view('admin.reports.roi', compact('incomes', 'search', 'fromDate', 'toDate', 'totalRoiDistributed'));
    }

    public function directIncomes(Request $request)
    {
        $search = $request->query('search');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::whereIn('type', ['direct_income', 'direct'])
            ->with(['user', 'fromUser', 'stake'])
            ->latest();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalDirectCommission = (float) $query->clone()->sum('amount_coins');

        return view('admin.reports.direct', compact('incomes', 'search', 'fromDate', 'toDate', 'totalDirectCommission'));
    }

    public function levelIncomes(Request $request)
    {
        $search = $request->query('search');
        $level = $request->query('level');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::whereIn('type', ['level_income', 'level'])
            ->with(['user', 'fromUser', 'stake'])
            ->latest();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        if ($level) {
            $query->where('level', $level);
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalLevelCommission = (float) $query->clone()->sum('amount_coins');

        return view('admin.reports.level', compact('incomes', 'search', 'level', 'fromDate', 'toDate', 'totalLevelCommission'));
    }

    public function rewardIncomes(Request $request)
    {
        $search = $request->query('search');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::whereIn('type', ['award_reward', 'matching'])
            ->with(['user', 'fromUser', 'stake'])
            ->latest();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalRewardCommission = (float) $query->clone()->sum('amount_coins');

        return view('admin.reports.rewards', compact('incomes', 'search', 'fromDate', 'toDate', 'totalRewardCommission'));
    }

    public function packageActivations(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = UserStake::with(['user'])->latest();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        if ($status && in_array($status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $stakes = $query->paginate(15)->withQueryString();
        $totalStakedUSD = (float) $query->clone()->sum('amount_usd');

        return view('admin.reports.packages', compact('stakes', 'search', 'status', 'fromDate', 'toDate', 'totalStakedUSD'));
    }

    public function transactions(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Income::with(['user', 'fromUser', 'stake'])->latest();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        if ($type && in_array($type, ['direct_income', 'level_income', 'daily_stake', 'award_reward', 'direct', 'level', 'roi', 'matching'])) {
            $query->where('type', $type);
        }
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);

        $incomes = $query->paginate(15)->withQueryString();
        $totalCoinsDistributed = (float) $query->clone()->sum('amount_coins');

        return view('admin.reports.transactions', compact('incomes', 'search', 'type', 'fromDate', 'toDate', 'totalCoinsDistributed'));
    }
}
