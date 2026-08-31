<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Models\UserStake;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessDailyStaking extends Command
{
    protected $signature = 'binalex:process-daily-staking';
    protected $description = 'Process daily coin payouts for active 300-day stakes in Binalex Ecosystem';

    public function handle(): int
    {
        $this->info("Starting Binalex Daily Staking Payout Process...");

        $activeStakes = UserStake::where('status', 'active')
            ->where('days_completed', '<', 300)
            ->with('user')
            ->get();

        $count = 0;

        foreach ($activeStakes as $stake) {
            DB::transaction(function () use ($stake) {
                $user = $stake->user;
                $dailyCoins = (float) $stake->daily_payout_coins;

                $user->coin_balance += $dailyCoins;
                $user->save();

                $stake->earned_coins += $dailyCoins;
                $stake->days_completed += 1;

                if ($stake->days_completed >= $stake->duration_days) {
                    $stake->status = 'completed';
                }
                $stake->save();

                Income::create([
                    'user_id' => $user->id,
                    'stake_id' => $stake->id,
                    'type' => 'daily_stake',
                    'amount_coins' => $dailyCoins,
                    'description' => "Daily Staking Return (Day " . $stake->days_completed . " of " . $stake->duration_days . ") for " . $stake->package_name,
                ]);
            });

            $count++;
        }
        $this->info("Successfully processed daily payouts for {$count} active stakes.");
        return Command::SUCCESS;
    }
}