<?php

namespace App\Services;

use App\Models\TokenPricing;

class TokenPricingService
{
    /**
     * Get current active token pricing configuration.
     */
    public function getCurrentPricing()
    {
        return TokenPricing::first() ?? TokenPricing::create([
            'total_pool_usd' => 1000000.00,
            'total_pool_coins' => 1000000000.00,
            'coin_rate_usd' => 0.0010,
        ]);
    }

    /**
     * Calculate how many coins a user gets for a specific USD amount.
     */
    public function calculateCoinsForUsd(float $usdAmount)
    {
        $pricing = $this->getCurrentPricing();
        
        // If 1 coin costs X USD, then Coins = UsdAmount / coin_rate_usd
        if ($pricing->coin_rate_usd > 0) {
            return $usdAmount / $pricing->coin_rate_usd;
        }

        return 0;
    }

    /**
     * Update pool and recalculate rates from Admin panel.
     */
    public function updatePricing(array $data)
    {
        $pricing = $this->getCurrentPricing();
        
        $totalUsd = $data['total_pool_usd'];
        $totalCoins = $data['total_pool_coins'];
        
        // Calculate coin rate in USD (USD per coin)
        $coinRateUsd = ($totalCoins > 0) ? ($totalUsd / $totalCoins) : 0;

        $pricing->update([
            'total_pool_usd' => $totalUsd,
            'total_pool_coins' => $totalCoins,
            'coin_rate_usd' => $coinRateUsd,
        ]);

        return $pricing;
    }
}