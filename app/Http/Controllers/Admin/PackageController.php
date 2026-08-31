<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserStake;
use App\Services\StakingService;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = StakingService::getPackages();
        $totalStakesCount = UserStake::count();
        $activeStakesCount = UserStake::where('status', 'active')->count();
        $totalStakedVolumeUSD = (float) UserStake::sum('amount_usd');

        return view('admin.packages.index', compact('packages', 'totalStakesCount', 'activeStakesCount', 'totalStakedVolumeUSD'));
    }
}
