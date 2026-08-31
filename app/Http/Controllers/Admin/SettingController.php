<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TokenPricing;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $pricing = TokenPricing::firstOrCreate([], [
            'total_pool_usd' => 300000.00,
            'total_pool_coins' => 15000000.00,
            'coin_rate_usd' => 0.0200,
        ]);

        return view('admin.settings.index', compact('pricing'));
    }

    public function updatePricing(Request $request)
    {
        $request->validate([
            'total_pool_usd' => 'required|numeric|min:0',
            'total_pool_coins' => 'required|numeric|min:1',
            'coin_rate_usd' => 'required|numeric|min:0.0001',
        ]);

        $pricing = TokenPricing::firstOrCreate([]);
        $pricing->update([
            'total_pool_usd' => (float) $request->input('total_pool_usd'),
            'total_pool_coins' => (float) $request->input('total_pool_coins'),
            'coin_rate_usd' => (float) $request->input('coin_rate_usd'),
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'BINALEX Token Rate & Pool Settings updated successfully! Current Rate: $' . number_format($pricing->coin_rate_usd, 4) . ' USD.');
    }
}
