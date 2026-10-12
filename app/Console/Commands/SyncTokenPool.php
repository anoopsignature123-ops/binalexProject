<?php

namespace App\Console\Commands;

use App\Models\TokenPricing;
use App\Models\UserStake;
use Illuminate\Console\Command;

class SyncTokenPool extends Command
{
    protected $signature = 'binalex:sync-token-pool';
    protected $description = 'Sync system total staked capital and update Token Pool metrics';

    public function handle(): int
    {
        $this->info("Syncing Binalex Token Pool & System Staked Capital...");

        $totalStakedUsd = (float) UserStake::sum('amount_usd');
        $totalStakedCoins = (float) UserStake::sum('coins_staked');

        $pricing = TokenPricing::first();
        if ($pricing) {
            $pricing->total_pool_usd = max($pricing->total_pool_usd, $totalStakedUsd);
            $pricing->save();
        }

        $this->info("Current System Metrics:");
        $this->info(" - Total Staked USD: $" . number_format($totalStakedUsd, 2));
        $this->info(" - Total Staked Coins: " . number_format($totalStakedCoins, 2) . " Coins");
        $this->info(" - Token Pool USD Raised: $" . number_format($pricing ? $pricing->total_pool_usd : 0, 2));
        $this->info(" - Current Coin Rate: $" . number_format($pricing ? $pricing->coin_rate_usd : 0.02, 4) . " USD");

        return Command::SUCCESS;
    }
}
