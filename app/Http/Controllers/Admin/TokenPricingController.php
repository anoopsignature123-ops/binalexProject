<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TokenPricingService;
use Illuminate\Http\Request;

class TokenPricingController extends Controller
{
    protected TokenPricingService $pricingService;

    public function __construct(TokenPricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    // Show Token Pricing Management Page
    public function index()
    {
        $pricing = $this->pricingService->getCurrentPricing();
        return view('admin.token-pricing.index', compact('pricing'));
    }

    // Update Token Pool and Rates
    public function update(Request $request)
    {
        $request->validate([
            'total_pool_usd' => 'required|numeric|min:1',
            'total_pool_coins' => 'required|numeric|min:1',
        ]);

        $this->pricingService->updatePricing([
            'total_pool_usd' => $request->total_pool_usd,
            'total_pool_coins' => $request->total_pool_coins,
        ]);

        return redirect()->route('admin.token-pricing.index')
            ->with('success', 'Token pricing and pool rate updated successfully.');
    }
}