<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\StakingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StakingController extends Controller
{
    protected StakingService $stakingService;

    public function __construct(StakingService $stakingService)
    {
        $this->stakingService = $stakingService;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $packages = StakingService::getPackages();
        $stakes = $user->stakes()->latest()->paginate(10);

        return view('user.staking.index', compact('user', 'packages', 'stakes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_code' => 'nullable|string',
            'amount_usd' => 'nullable|numeric|min:0.1',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            if ($request->filled('amount_usd')) {
                $stake = $this->stakingService->purchaseCustomStake($user, (float) $request->input('amount_usd'));
            } else {
                $code = $request->input('package_code', 'A');
                $stake = $this->stakingService->purchaseStake($user, $code);
            }

            return redirect()->route('user.staking.index')
                ->with('success', "Congratulations! You have successfully staked $" . number_format($stake->amount_usd, 2) . " USD (" . number_format($stake->coins_staked, 2) . " BINALEX Coins credited).");
        } catch (Exception $e) {
            return back()->withErrors(['amount_usd' => $e->getMessage()]);
        }
    }
}
